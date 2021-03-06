<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

use Mail;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;

use App\Transformers\UserTransformer;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    protected $table = 'users';

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'uuid';

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_NO_ADMINISTRADOR = 'false';

    public $transformer = UserTransformer::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'admin', 'verification_token'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at', 'email_verified_at', 'verification_token'
    ];

    public static function boot()
    {
      parent::boot();
      self::creating(function ($model){
        if(empty($model->uuid))
        {
          $model->uuid = Uuid::generate(4)->string;
        }
      });

      self::created(function ($user){
        retry(5, function() use ($user) {
          Mail::to($user->email)->send(new UserCreated($user));
        },100);
      });

      self::updated(function ($user){
        if ($user->isDirty('email')) {
          retry(5, function() use ($user) {
            Mail::to($user->email)->send(new UserMailChanged($user));
          },100);
        }

      });
    }

    public function setNameAttribute($value)
    {
      $this->attributes['name'] = strtolower($value);
    }

    public function setEmailAttribute($value)
    {
      $this->attributes['email'] = strtolower($value);
    }

    public function getNameAttribute($value)
    {
      return ucwords($value);
    }

    public function getVerificadoAttribute()
    {
      return $this->verified == User::USUARIO_VERIFICADO;
    }

    public function getAdministradorAttribute()
    {
      return $this->admin == User::USUARIO_ADMINISTRADOR;
    }

    public static function generateToken()
    {
      return str_random(40);
    }
}
