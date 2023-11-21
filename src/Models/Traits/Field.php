<?php

namespace Sdkconsultoria\Core\Models\Traits;

trait Field
{
    public static array $fields = [];

    public function getFields(): array
    {
        if ($this::$fields) {
            return $this::$fields;
        }

        $this::$fields = [];

        foreach ($this->fields() as $index => $field) {
            $this::$fields[$field->name] = $field->getField();
            $this::$fields[$field->name]['value'] = $this->{$this::$fields[$field->name]['name']};
        }

        return $this::$fields;
    }

    public function getIndexFields()
    {
        $fields = [];
        foreach ($this->fields() as $field) {
            if ($field->visible_on_index) {
                $fields[] = $field->name;
            }
        }

        return $fields;
    }

    public function getIgnoredFields()
    {
        $fields = [];

        foreach ($this->getFields() as $field) {
            if (! $field['can_be_saved']) {
                $fields[] = $field['name'];
            }
        }

        return $fields;
    }
}
