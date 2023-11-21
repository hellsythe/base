<?php

namespace Sdkconsultoria\Core\Models\Traits;

use Illuminate\Support\Str;
use Sdkconsultoria\Core\Exceptions\APIException;

trait Authorize
{
    public function isAuthorize(string $action)
    {
        $auth_user = auth()->user();
        $permision = $this->getPermissionName($action);

        if ($auth_user->can($permision)) {
            return;
        }

        $is_super_admin = $auth_user->hasRole('super-admin');

        if ($is_super_admin) {
            return;
        }

        throw new APIException(['message' => __('core::responses.403')], 403);
    }

    public function getPermissionName(string $action): string
    {
        $resouce = Str::snake(class_basename($this));

        return "$resouce:$action";
    }
}
