<?php

namespace Sdkconsultoria\Core\Models\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sdkconsultoria\Core\Models\Traits\Model as TraitBaseModel;

trait BaseModel
{
    use HasFactory;
    use TraitBaseModel;
    use Field;
    use Authorize;
    use LoadFromRequest;
    use SoftDeletes;
    use ValidateRequest;
    use Menu;
}
