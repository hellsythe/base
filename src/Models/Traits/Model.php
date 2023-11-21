<?php

namespace Sdkconsultoria\Core\Models\Traits;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Sdkconsultoria\Core\Exceptions\APIException;
use Date;
trait Model
{
    public static $keyId = 'id';

    public function save(array $options = [])
    {
        parent::save($options);
    }

    public function processFieldsWithCustomSave()
    {
        $fields = $this->fields();

        foreach ($fields as $key => $field) {
            if ($field->saveAsFunction) {
                if ($this->ignoredFields[$field->name]) {
                    $function = $field->saveAsFunction;
                    $function($this, $this->ignoredFields[$field->name]);
                }
            }
        }
    }

    public static function findModel($id)
    {
        $class = get_called_class();
        $model = $class::where('id', $id)->first();

        if ($model) {
            return $model;
        }

        throw new APIException(['message' => __('core::responses.404')], 404);
    }

    public static function findModelOrCreate(): EloquentModel
    {
        $model = get_called_class()::where('created_by', auth()->user()->id)
            ->where('status', get_called_class()::STATUS_CREATION)
            ->first();

        if ($model) {
            return $model;
        }

        return get_called_class()::createEmptyModel();
    }

    protected static function createEmptyModel()
    {
        $called_class = get_called_class();
        $model = new $called_class;
        $model->created_by = auth()->user()->id;
        $model->status = $model::STATUS_CREATION;

        if ($model->canCreateEmpty) {
            $model->save();
        }

        return $model;
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function hasColumn($column)
    {
        if (in_array($column, $this->getTableColumns())) {
            return true;
        }

        return false;
    }

    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function updatedBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

    public function deletedBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

    public function getLabels(): array
    {
        $labels = [];
        foreach ($this->getFields()  as $field) {
            $labels[$field['name']] = $field['label'];
        }

        return $labels;
    }

    public function getFilters(): array
    {
        $filters = [];
        foreach ($this->getFields()  as $field) {
            if ($field['searchable']) {
                $filters[] = $field['filter'];
            }
        }

        return $filters;
    }

    public function getParseSearchFilters()
    {
        $filters = [];

        foreach ($this->getFilters() as $key => $filter) {
            if (is_array($filter)) {
                $filters[] = ['field' => $key];
            } else {
                $filters[] = ['field' => $filter];
            }
        }

        return $filters;
    }

    public function getTranslations(): array
    {
        return [];
    }

    public function gender(): string
    {
        $gender = $this->getTranslations()['gender'] ?? '';
        $singular = $this->getTranslations()['singular'] ?? '';

        if ($gender) {
            return $gender;
        }

        return strtolower(substr($singular, -1)) == 'a' ? 0 : 1;
    }

    public function getDefaultTranslations(): array
    {
        $gender = $this->gender();
        $singular = $this->getTranslations()['singular'] ?? '';

        return [
            'create' => __('core::models.create', ['model' => $singular]),
            'edit' => __('core::models.update', ['model' => $singular]),
            'show' => __('core::models.show', ['model' => $singular]),
            'delete' => __('core::models.delete', ['model' => $singular]),
            'delete_question' => trans_choice('core::models.delete_question', $gender, ['item' => $singular]),
            'showed' => trans_choice('core::models.showed', $gender, ['item' => $singular]),
            'created' => trans_choice('core::models.created', $gender, ['item' => $singular]),
            'edited' => trans_choice('core::models.edited', $gender, ['item' => $singular]),
            'deleted' => trans_choice('core::models.deleted', $gender, ['item' => $singular]),
            'add_element' => trans_choice('core::models.add_element', $gender, ['item' => $singular]),
            'id' => __('core::models.id'),
            'status' => __('core::models.status'),
            'created_at' => __('core::models.created_at'),
            'updated_at' => __('core::models.updated_at'),
            'deleted_at' => __('core::models.deleted_at'),
            'created_by' => __('core::models.created_by'),
            'updated_by' => __('core::models.updated_by'),
            'deleted_by' => __('core::models.deleted_by'),
            'grid' => [
                'advanced_search' => __('core::models.grid.advanced_search'),
                'empty' => __('core::models.grid.empty'),
                'not_assigned' => __('core::models.grid.not_assigned'),
                'search' => __('core::models.grid.search'),
                'clear' => __('core::models.grid.clear'),
            ],
            'save' => 'Guardar',
            'close' => 'Cerrar',
            'continue' => 'Continuar',
            'cancel' => 'Cancelar',
            'pagination' => [
                'showing' => __('core::models.pagination.showing'),
                'of' => __('core::models.pagination.of'),
                'results' => __('core::models.pagination.results'),
                'next' => __('core::models.pagination.next'),
                'previous' => __('core::models.pagination.previous'),
            ],
        ];
    }

    public function getFullTranslations(): array
    {
        return array_merge($this->getTranslations(), $this->getDefaultTranslations(), $this->getLabels());
    }

    public static function make()
    {
        $called_class = get_called_class();

        return new $called_class;
    }

    public function getTranslation(string $label): string
    {
        return $this->getFullTranslations()[$label] ?? $label;
    }

    public function getRoute(string $name, $params = [])
    {
        return route($this->getClassName('kebab').'.'.$name, $params);
    }

    public function getRouteApi(string $name, $params = [])
    {
        return route('api.'.$this->getClassName('kebab').'.'.$name, $params);
    }

    public function getIndexRoutes(): array
    {
        return [
            'resource' => $this->getRoute('index'),
            'api' => $this->getRouteApi('index'),
        ];
    }

    public function getClassName(string $type = '', bool $plural = false)
    {
        $class = get_class()::$route ?? (new \ReflectionClass(get_called_class()))->getShortName();

        switch ($type) {
            case 'kebab':
                $class = Str::kebab($class);
                break;

            case 'snake':
                $class = Str::snake($class);
                break;
        }

        $class = strtolower($class);

        if ($plural) {
            return Str::plural($class);
        }

        return $class;
    }

    public function getKeyId()
    {
        return $this->{$this::$keyId};
    }

    public function getCreatedAtAttribute($value): string
    {
        return  Date::createFromDate($value)->setTimezone('America/Mexico_city')->format('l j F Y \a \l\a\s H:i');
    }
}
