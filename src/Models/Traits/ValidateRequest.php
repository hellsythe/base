<?php

namespace Sdkconsultoria\Core\Models\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Sdkconsultoria\Core\Exceptions\APIException;

trait ValidateRequest
{
    public function getValidationRules($request = ''): array
    {
        $rules = [];
        foreach ($this->getFields() as $field) {
            $rules[$field['name']] = $field['rules'];
        }

        return $rules;
    }

    public function getUpdateValidationRules($request = ''): array
    {
        $rules = [];
        foreach ($this->getFields() as $field) {
            $rules[$field['name']] = $field['rulesUpdate'];
        }

        return $rules;    }

    public function validateRequest(Request $request, array $rules)
    {
        $validator = Validator::make($request->all(), $rules, [], $this->getLabels());

        if ($validator->fails()) {
            throw new APIException($validator->errors()->toArray(), 400);
        }
    }
}
