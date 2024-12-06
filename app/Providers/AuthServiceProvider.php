<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Depo yönetimi yetkisi
        Gate::define('manage-warehouses', function ($user) {
            return in_array($user->role, ['Depo Sorumlusu', 'Müdür', 'Yönetici']);
        });

        // Stok yönetimi yetkisi
        Gate::define('manage-stocks', function ($user) {
            return in_array($user->role, ['Depo Sorumlusu', 'Müdür', 'Yönetici']);
        });

        // Transfer yönetimi yetkisi
        Gate::define('manage-transfers', function ($user) {
            return in_array($user->role, ['Müdür', 'Yönetici']);
        });

        // Kullanıcı yönetimi yetkisi
        Gate::define('manage-users', function ($user) {
            return $user->role === 'Yönetici';
        });

        // Genel ayarlar yetkisi
        Gate::define('manage-settings', function ($user) {
            return in_array($user->role, ['Depo Sorumlusu', 'Müdür', 'Yönetici']);
        });

        // Aktivite listesi görüntüleme yetkisi
        Gate::define('view-activity-list', function ($user) {
            return in_array($user->role, ['Depo Sorumlusu', 'Müdür', 'Yönetici']);
        });
    }
}
