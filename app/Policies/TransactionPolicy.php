<?php

namespace App\Policies;

use App\User;
use App\Models\Transaction;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminActions;

class TransactionPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the transaction.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Transaction  $transaction
     * @return mixed
     */
    public function view(User $user, Transaction $transaction)
    {
        return $user->uuid === $transaction->buyer->uuid || $user->uuid === $transaction->product->seller->uuid;
    }

}
