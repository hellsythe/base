<?php

namespace Sdkconsultoria\Core\Models\Traits;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait LoadFromRequest
{
    protected $ignoredFields = [];

    public function getApiEndpoint()
    {
        return strtolower(Str::kebab(class_basename($this)));
    }

    public function loadDataFromCreateRequest(Request $request): void
    {
        $this->validateRequest($request, $this->getValidationRules());
        $attributes = $this->getModelAttributesFromRules();
        $valid_attributes = $this->loadValidFieldsFromRequest($request, $attributes);

        $this->loadDataFromRequest($request, $valid_attributes);
    }

    public function loadDataFromUpdateRequest(Request $request): void
    {
        $this->validateRequest($request, $this->getUpdateValidationRules());
        $attributes = $this->getModelAttributesFromRules('getUpdateValidationRules');
        $valid_attributes = $this->loadValidFieldsFromRequest($request, $attributes);

        $this->loadDataFromRequest($request, $valid_attributes);
    }

    public function loadDataFromRequest(Request $request, array $atributes): void
    {
        $this->assignValuesToModel($atributes, $this);
    }

    private function loadValidFieldsFromRequest(Request $request, array $attributes): array
    {
        $valid_values = [];

        foreach ($request->all() as $attribute => $value) {
            if (in_array($attribute, $attributes)) {
                $valid_values[$attribute] = $value;
            }
        }

        return $valid_values;
    }

    private function assignValuesToModel(array $values, EloquentModel &$model): EloquentModel
    {
        $this->removedIgnoredFields($values);

        foreach ($values as $attribute => $value) {
            $model->$attribute = $value;
        }

        return $model;
    }

    public function getModelAttributesFromRules(string $rules = 'getValidationRules', $request = ''): array
    {
        $rules_array = $this->$rules($request);
        $attributes = [];

        foreach ($rules_array as $attribute => $rule) {
            array_push($attributes, $attribute);
        }

        return $attributes;
    }

    protected function removedIgnoredFields(array &$values): array
    {
        foreach ($this->getIgnoredFields() as $field) {
            $this->ignoredFields[$field] = $values[$field];
            unset($values[$field]);
        }

        return $values;
    }
}
