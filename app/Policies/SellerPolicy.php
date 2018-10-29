<?php

namespace App\Policies;

use App\User;
use App\Models\Seller;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminActions;

class SellerPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the seller.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function view(User $user, Seller $seller)
    {
        return $user->uuid === $seller->uuid;
    }

    /**
     * Determine whether the user can sale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function sale(User $user, User $seller)
    {
        return $user->uuid === $seller->uuid;
    }

    /**
     * Determine whether the user can edit.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function editProduct(User $user, Seller $seller)
    {
        return $user->uuid === $seller->uuid;
    }

    /**
     * Determine whether the user can delete.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function deleteProduct(User $user, Seller $seller)
    {
        return $user->uuid === $seller->uuid;
    }

}
