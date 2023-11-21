<?php

namespace Sdkconsultoria\Core\Controllers;

use App\Http\Controllers\Controller;
use Sdkconsultoria\Core\Controllers\Traits\ApiControllerTrait;
use Sdkconsultoria\Core\Controllers\Traits\ResourceControllerTrait;

class SimpleResourceController extends Controller
{
    use ApiControllerTrait;
    use ResourceControllerTrait;

    protected $model = '';

    protected $view = 'base::back.default';
}
