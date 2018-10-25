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
            'verification' => (boolean)$user->verified,
            'administrator' => ($user->admin === 'true'),
            'creation' => (string)$user->created_at->format('d/m/Y'),
            'links' => [
              [
                'rel' => 'self',
                'href' => route('users.show',$user->uuid)
              ]
            ]
        ];
    }

    public static function originalAttribute($index)
    {
      $attributes = [
        'identifier' => 'uuid',
        'full-name' => 'name',
        'mail' => 'email',
        'verification' => 'verified',
        'administrator' => 'admin',
        'creation' => 'created_at'
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformAttribute($index)
    {
      $attributes = [
        'uuid' => 'identifier',
        'name' => 'full-name',
        'email' => 'mail',
        'verified' => 'verification',
        'admin' => 'administrator',
        'created_at' => 'creation'
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
