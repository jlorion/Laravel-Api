<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use PhpParser\Node\Stmt\TraitUseAdaptation\Alias;

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
        Vite::prefetch(concurrency: 3);
        $loader = AliasLoader::getInstance();
        $loader->alias(\Laravel\Sanctum\PersonalAccessToken::class, \App\Models\PersonalAccessToken::class);
    }
}
