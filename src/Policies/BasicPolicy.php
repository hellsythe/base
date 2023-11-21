<?php

namespace Sdkconsultoria\Core\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;

abstract class BasicPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->validatePermission($user, 'viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, $model): bool
    {
        return $this->validatePermission($user, 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->validatePermission($user, 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $model): bool
    {
        return $this->validatePermission($user, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, $model): bool
    {
        return $this->validatePermission($user, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, $model): bool
    {
        return $this->validatePermission($user, 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, $model): bool
    {
        return $this->validatePermission($user, 'forceDelete');
    }

    protected function validatePermission(User $user, string $permission)
    {
        return $user->can($this->getModelName().':'.$permission);
    }

    protected function getModelName()
    {
        return Str::snake($this->getName());
    }

    private function getName() {
        $path = explode('\\', get_called_class());
        return str_replace('Policy', '', array_pop($path));
    }
}
