<?php

namespace Sdkconsultoria\Core\Models;

use Sdkconsultoria\Core\Fields\TextField;
use Sdkconsultoria\Core\Models\Traits\BaseModel as TraitBaseModel;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use TraitBaseModel;

    protected function fields()
    {
        return [
            TextField::make('name')
                ->label('Nombre')
                ->rules(['required']),
        ];
    }

    public static function fieldsX(): array
    {
        return [
            'name' => Field::make()
                ->label('Nombre')
                ->rules(['required']),
            'guard' => Field::make()
                ->label('Guard'),
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
