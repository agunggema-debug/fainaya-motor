<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. GATE OWNER (Guild Master - Akses Mutlak)
        Gate::define('access-owner', function (User $user) {
            return $user->role === 'owner';
        });

        // 2. GATE KASIR (Merchant - Registrasi & Terima Uang)
        // Owner juga diberi izin akses fungsionalitas kasir jika diperlukan
        Gate::define('access-cashier', function (User $user) {
            return in_array($user->role, ['owner', 'cashier']);
        });

        // 3. GATE MEKANIK (Combatant - Kelola Progres & Pasang Part)
        Gate::define('access-mechanic', function (User $user) {
            return in_array($user->role, ['owner', 'mechanic']);
        });

        // 4. GATE GUDANG (Inventory Manager - Kelola Stok Sparepart)
        Gate::define('access-warehouse', function (User $user) {
            return in_array($user->role, ['owner', 'warehouse']);
        });
    }
}