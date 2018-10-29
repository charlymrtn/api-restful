<?php

namespace App\Policies;

use App\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminActions;

class ProductPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can delete categories.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function deleteCategory(User $user, Product $product)
    {
        return $user->uuid === $product->seller->uuid;
    }

    /**
     * Determine whether the user can add categories.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function addCategory(User $user, Product $product)
    {
        return $user->uuid === $product->seller->uuid;
    }
}
