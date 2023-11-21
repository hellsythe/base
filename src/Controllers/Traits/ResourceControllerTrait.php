<?php

namespace Sdkconsultoria\Core\Controllers\Traits;

use Illuminate\Http\Request;

trait ResourceControllerTrait
{
    public function index(Request $request)
    {
        $model = new $this->model;
        $model->isAuthorize('viewAny');

        return view($this->view.'.index', [
            'model' => $model,
        ]);
    }

    public function create(Request $request)
    {
        $model = new $this->model;
        $model->isAuthorize('create');

        return view($this->view.'.create', [
            'model' => $model,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $model = $this->model::findModel($id);
        $model->isAuthorize('update');

        return view($this->view.'.edit', [
            'model' => $model,
        ]);
    }

    public function show(Request $request, $id)
    {
        $model = $this->model::findModel($id);
        $model->isAuthorize('view');

        return view($this->view.'.show', [
            'model' => $model,
        ]);
    }
}
