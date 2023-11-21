<?php

namespace Sdkconsultoria\Core\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Permite crear REST API rapidamente.
 */
trait ApiControllerTrait
{
    use SearchableTrait;
    use OrderableTrait;
    use PaginationTrait;

    protected function customFilters($query, $request)
    {
        return $query;
    }

    public function viewAny(Request $request)
    {
        $model = new $this->model;
        $this->authorize('viewAny', $model);

        $query = $model::where('status', $model::STATUS_ACTIVE);
        $query = $this->searchable($query, $request);
        $query = $this->customFilters($query, $request);
        $query = $this->applyOrderByToQuery($query, $request->input('order'));

        return $this->setPagination($query, $request);
    }

    public function view(Request $request, $id)
    {
        $model = $this->model::findModel($id);
        $this->authorize('view', $model);

        return response()
            ->json(['model' => $model->getAttributes()]);
    }

    public function storage(Request $request)
    {
        $model = $this->model::findModelOrCreate();
        $this->authorize('create', $model);
        $model->loadDataFromCreateRequest($request);
        $model->status = $model::STATUS_ACTIVE;
        $model->save();
        $this->processFilesIfExist($model, $request);
        $model->processFieldsWithCustomSave();

        return response()
            ->json(['model' => $model->getAttributes()]);
    }

    public function update(Request $request, $id)
    {
        $model = $this->model::findModel($id);
        $this->authorize('update', $model);
        $model->loadDataFromUpdateRequest($request);
        $model->save();
        $this->processFilesIfExist($model, $request);
        $model->processFieldsWithCustomSave();

        return response()
            ->json(['model' => $model->getAttributes()]);
    }

    public function delete($id)
    {
        $model = $this->model::findModel($id);
        $this->authorize('delete', $model);
        $model->status = $model::STATUS_DELETED;
        $model->delete();

        return response()
            ->json(['model' => $model->getAttributes()]);
    }

    private function processFilesIfExist(&$model, $request)
    {
        foreach ($model->getFields() as $field) {

            if ($field['component'] == 'FileField') {
                $file = $request->file($field['name']);
                if ($file) {
                    Storage::disk($field['disk'])->putFileAs($field['folder'], $file, $model->id.'.'.$file->getClientOriginalExtension());

                    $model->{$field['name']} = Storage::disk($field['disk'])->url($field['folder'] . $model->id.'.'.$file->getClientOriginalExtension());
                    $model->save();
                }
            }
        }
    }
}
