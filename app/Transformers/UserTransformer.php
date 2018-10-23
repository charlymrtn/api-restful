<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (string)$user->uuid,
            'full-name' => (string)$user->name,
            'mail' => (string)$user->email,
            'verified' => (boolean)$user->verified,
            'administrator' => ($user->admin === 'true'),
            'creation' => (string)$user->created_at->format('d/m/Y')
        ];
    }
}
