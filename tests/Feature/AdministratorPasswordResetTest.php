<?php

namespace Tests\Feature;

use App\Services\SmsService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class AdministratorPasswordResetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::create('operateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('login');
            $table->string('contact')->nullable();
            $table->string('password');
            $table->boolean('supprimer')->default(false);
            $table->unsignedBigInteger('userUpdate')->nullable();
            $table->timestamps();
        });

        DB::table('operateurs')->insert([
            'id' => 10,
            'nom' => 'DOE',
            'prenoms' => 'JANE',
            'login' => 'jane.doe',
            'contact' => '07 08 09 10 11',
            'password' => Hash::make('AncienMotDePasse'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withoutMiddleware();
    }

    public function test_an_administrator_password_can_be_reset_without_sms(): void
    {
        $response = $this->patchJson(route('comptes.mot-de-passe', 10), [
            'password' => 'NouveauMotDePasse',
            'password_confirmation' => 'NouveauMotDePasse',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('sms_envoye', null);

        $this->assertTrue(Hash::check(
            'NouveauMotDePasse',
            DB::table('operateurs')->where('id', 10)->value('password')
        ));
    }

    public function test_an_administrator_can_receive_the_new_password_by_sms(): void
    {
        $smsService = Mockery::mock(SmsService::class);
        $smsService->shouldReceive('formatRecipient')
            ->once()
            ->with('07 08 09 10 11')
            ->andReturn('2250708091011');
        $smsService->shouldReceive('send')
            ->once()
            ->withArgs(fn (string $recipient, string $message) => $recipient === '2250708091011'
                && str_contains($message, 'jane.doe')
                && str_contains($message, 'NouveauMotDePasse'))
            ->andReturn('OK 12345');
        $smsService->shouldReceive('isSuccessful')
            ->once()
            ->with('OK 12345')
            ->andReturnTrue();
        $this->app->instance(SmsService::class, $smsService);

        $response = $this->patchJson(route('comptes.mot-de-passe', 10), [
            'password' => 'NouveauMotDePasse',
            'password_confirmation' => 'NouveauMotDePasse',
            'notifier_sms' => true,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('sms_envoye', true);
    }
}
