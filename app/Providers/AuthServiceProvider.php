<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Buyer' => 'App\Policies\BuyerPolicy',
        'App\Models\Seller' => 'App\Policies\SellerPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Models\Transaction' => 'App\Policies\TransactionPolicy',
        'App\Models\Product' => 'App\Policies\ProductPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(now()->addMinutes(30));
        Passport::refreshTokensExpireIn(now()->addDays(15));
        Passport::enableImplicitGrant();

        Passport::tokensCan([
          'purchase-product' => 'crear movimientos para comprar productos determinados',
          'manage-product' => 'crear, ver, actualizar productos',
          'manage-account' => 'obtener información de la cuenta, modificar datos básicos, no se puede eliminar',
          'read-general' => 'leer categorias, productos, ventas, información general'
        ]);
    }
}
