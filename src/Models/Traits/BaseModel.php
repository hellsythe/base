<?php

namespace Sdkconsultoria\Core\Models\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sdkconsultoria\Core\Models\Traits\Model as TraitBaseModel;

trait BaseModel
{
    use Authorize;
    use Field;
    use HasFactory;
    use LoadFromRequest;
    use Menu;
    use SoftDeletes;
    use TraitBaseModel;
    use ValidateRequest;
}
