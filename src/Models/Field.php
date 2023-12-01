<?php

namespace Sdkconsultoria\Core\Models;

use Sdkconsultoria\Core\Fields\TextField;
use Sdkconsultoria\Core\Models\Traits\BaseModel as TraitBaseModel;
use Spatie\Permission\Models\Role as BaseRole;

class Field
{
    private string $label;
    private array $rules = [];

    public static function make(): Field
    {
        return new static();
    }

    public function label(string $label): Field
    {
        $this->label = $label;
        return $this;
    }

    public function rules(array $rules): Field
    {
        $this->rules = $rules;
        return $this;
    }
}