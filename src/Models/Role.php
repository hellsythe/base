<?php

namespace Sdkconsultoria\Core\Models;

use Sdkconsultoria\Core\Fields\TextField;
use Sdkconsultoria\Core\Models\Traits\BaseModel as TraitBaseModel;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use TraitBaseModel;

    public const DEFAULT_SEARCH = 'like';

    public const STATUS_DELETED = 0;

    public const STATUS_CREATION = 20;

    public const STATUS_ACTIVE = 30;

    protected function fields()
    {
        return [
            TextField::make('name')
                ->label('Nombre')
                ->rules(['required']),
        ];
    }

    public function getTranslations(): array
    {
        return [
            'singular' => 'Rol',
            'plural' => 'Roles',
        ];
    }
}
