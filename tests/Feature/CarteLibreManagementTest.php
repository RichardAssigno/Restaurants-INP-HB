<?php

namespace Tests\Feature;

use App\Models\Operateur;
use App\Policies\CarteLibrePolicy;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CarteLibreManagementTest extends TestCase
{
    private Operateur $operateur;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        $this->createSchema();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::table('directions')->insert([
            'id' => 1,
            'libelle' => 'Direction générale',
            'codeDirection' => 'DG',
            'supprimer' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->operateur = Operateur::query()->create([
            'nom' => 'ADMIN',
            'prenoms' => 'TEST',
            'login' => 'admin.test',
            'contact' => '0102030405',
            'password' => Hash::make('MotDePasseTest'),
            'actif' => true,
            'supprimer' => false,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('cartes-libres.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_operator_without_permission_is_forbidden(): void
    {
        $this->actingAs($this->operateur, 'operateur')
            ->get(route('cartes-libres.index'))
            ->assertForbidden();
    }

    public function test_authorized_operator_can_view_the_module_and_its_data(): void
    {
        $this->grant(CarteLibrePolicy::VIEW_ANY);
        DB::table('carteslibres')->insert($this->cardData());

        $this->actingAs($this->operateur, 'operateur')
            ->get(route('cartes-libres.index'))
            ->assertOk()
            ->assertSee('Cartes libres');

        $this->actingAs($this->operateur, 'operateur')
            ->getJson(route('cartes-libres.data'))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 1)
            ->assertJsonPath('data.0.libelle', 'Carte visiteurs');
    }

    public function test_invalid_creation_returns_validation_errors(): void
    {
        $this->grant(CarteLibrePolicy::CREATE);

        $this->actingAs($this->operateur, 'operateur')
            ->postJson(route('cartes-libres.store'), [
                'directions_id' => 999,
                'libelle' => '',
                'capacite' => 0,
                'dateDebut' => '21/08/2026',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['directions_id', 'libelle', 'capacite', 'dateDebut']);
    }

    public function test_authorized_operator_can_create_update_toggle_and_delete_a_card(): void
    {
        $this->grant(
            CarteLibrePolicy::VIEW_ANY,
            CarteLibrePolicy::CREATE,
            CarteLibrePolicy::UPDATE,
            CarteLibrePolicy::ACTIVATE,
            CarteLibrePolicy::DELETE,
        );

        $createResponse = $this->actingAs($this->operateur, 'operateur')
            ->postJson(route('cartes-libres.store'), [
                'directions_id' => 1,
                'libelle' => 'Carte invités',
                'capacite' => 12,
                'dateDebut' => '2026-08-21',
                'nombreJours' => 30,
            ])
            ->assertCreated()
            ->assertJsonPath('message', 'La carte libre a été créée et activée avec succès.');

        $cardId = $createResponse->json('data.id');

        $this->assertDatabaseHas('carteslibres', [
            'id' => $cardId,
            'libelle' => 'Carte invités',
            'dateDebut' => '21-08-2026',
            'actif' => true,
            'supprimer' => false,
        ]);

        $this->actingAs($this->operateur, 'operateur')
            ->putJson(route('cartes-libres.update', $cardId), [
                'directions_id' => 1,
                'libelle' => 'Carte partenaires',
                'capacite' => 20,
                'dateDebut' => null,
                'nombreJours' => null,
            ])
            ->assertOk();

        $this->actingAs($this->operateur, 'operateur')
            ->patchJson(route('cartes-libres.status', $cardId))
            ->assertOk()
            ->assertJsonPath('actif', false);

        $this->actingAs($this->operateur, 'operateur')
            ->deleteJson(route('cartes-libres.destroy', $cardId))
            ->assertOk();

        $this->assertDatabaseHas('carteslibres', [
            'id' => $cardId,
            'libelle' => 'Carte partenaires',
            'actif' => false,
            'supprimer' => true,
        ]);
    }

    public function test_card_linked_to_an_account_cannot_be_deleted(): void
    {
        $this->grant(CarteLibrePolicy::DELETE);
        $cardId = DB::table('carteslibres')->insertGetId($this->cardData());
        DB::table('comptesrestaux')->insert([
            'carteslibres_id' => $cardId,
            'supprimer' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($this->operateur, 'operateur')
            ->deleteJson(route('cartes-libres.destroy', $cardId))
            ->assertUnprocessable();

        $this->assertDatabaseHas('carteslibres', [
            'id' => $cardId,
            'supprimer' => false,
        ]);
    }

    private function grant(string ...$permissions): void
    {
        foreach ($permissions as $name) {
            $permission = Permission::findOrCreate($name, 'operateur');
            $this->operateur->givePermissionTo($permission);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function cardData(): array
    {
        return [
            'directions_id' => 1,
            'libelle' => 'Carte visiteurs',
            'capacite' => 5,
            'dateDebut' => null,
            'nombreJours' => null,
            'actif' => true,
            'supprimer' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function createSchema(): void
    {
        Schema::create('operateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('login')->unique();
            $table->string('contact')->nullable();
            $table->string('password');
            $table->boolean('actif')->default(true);
            $table->boolean('supprimer')->default(false);
            $table->unsignedBigInteger('userAdd')->nullable();
            $table->unsignedBigInteger('userUpdate')->nullable();
            $table->unsignedBigInteger('userDelete')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('directions', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('codeDirection');
            $table->boolean('supprimer')->default(false);
            $table->timestamps();
        });

        Schema::create('carteslibres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('directions_id');
            $table->string('libelle');
            $table->integer('capacite')->default(1);
            $table->string('dateDebut')->nullable();
            $table->integer('nombreJours')->nullable();
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('userAdd')->nullable();
            $table->unsignedBigInteger('userUpdate')->nullable();
            $table->unsignedBigInteger('userDelete')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('supprimer')->default(false);
            $table->timestamps();
        });

        Schema::create('comptesrestaux', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carteslibres_id')->nullable();
            $table->boolean('supprimer')->default(false);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['permission_id', 'role_id']);
        });
    }
}
