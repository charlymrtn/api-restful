<?php

namespace App\Policies;

use App\User;
use App\Models\Buyer;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminActions;

class BuyerPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the buyer.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Buyer  $buyer
     * @return mixed
     */
    public function view(User $user, Buyer $buyer)
    {
        return $user->uuid === $buyer->uuid;
    }

    /**
     * Determine whether the user can purchase something.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Buyer  $buyer
     * @return mixed
     */
    public function purchase(User $user, Buyer $buyer)
    {
        return $user->uuid === $buyer->uuid;
    }
}
