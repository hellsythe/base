<?php

namespace Sdkconsultoria\Core\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Sdkconsultoria\Core\Models\Traits\BaseModel as TraitBaseModel;

abstract class Model extends BaseModel
{
    use TraitBaseModel;

    public const DEFAULT_SEARCH = 'like';

    public const STATUS_DELETED = 0;

    public const STATUS_CREATION = 20;

    public const STATUS_ACTIVE = 30;

    public $canCreateEmpty = false;
}
