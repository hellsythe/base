<?php

namespace Sdkconsultoria\Core\Models\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sdkconsultoria\Core\Models\Traits\Model as TraitBaseModel;

trait BaseModel
{
    public const DEFAULT_SEARCH = 'like';

    public const STATUS_DELETED = 0;

    public const STATUS_CREATION = 100;

    public const STATUS_ACTIVE = 200;

    use Field;
    use HasFactory;
    use LoadFromRequest;
    use SoftDeletes;
    use TraitBaseModel;
    use ValidateRequest;
}
