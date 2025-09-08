<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Daftarkan service di sini jika perlu
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Memaksa semua URL menggunakan HTTPS di production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Contoh kode lain yang mungkin sudah ada sebelumnya
        // Misal view composer, custom validation, dsb.
    }
}