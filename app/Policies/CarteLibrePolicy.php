<?php

namespace App\Policies;

use App\Models\CarteLibre;
use App\Models\Operateur;

class CarteLibrePolicy
{
    public const VIEW_ANY = 'free-cards.viewAny';

    public const CREATE = 'free-cards.create';

    public const UPDATE = 'free-cards.update';

    public const ACTIVATE = 'free-cards.activate';

    public const DELETE = 'free-cards.delete';

    public function viewAny(Operateur $operateur): bool
    {
        return $operateur->checkPermissionTo(self::VIEW_ANY, 'operateur');
    }

    public function view(Operateur $operateur, CarteLibre $carteLibre): bool
    {
        return $this->viewAny($operateur);
    }

    public function create(Operateur $operateur): bool
    {
        return $operateur->checkPermissionTo(self::CREATE, 'operateur');
    }

    public function update(Operateur $operateur, CarteLibre $carteLibre): bool
    {
        return $operateur->checkPermissionTo(self::UPDATE, 'operateur');
    }

    public function activate(Operateur $operateur, CarteLibre $carteLibre): bool
    {
        return $operateur->checkPermissionTo(self::ACTIVATE, 'operateur');
    }

    public function delete(Operateur $operateur, CarteLibre $carteLibre): bool
    {
        return $operateur->checkPermissionTo(self::DELETE, 'operateur');
    }
}
