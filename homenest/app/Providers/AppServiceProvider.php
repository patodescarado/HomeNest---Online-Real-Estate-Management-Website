<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\PropertyType;
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
        View::composer('partials.navbar', function ($view) {
        $view->with(Cache::remember('menu.types', 3600, fn () => [
        'saleTypes' => PropertyType::where('for_sale', true)->orderBy('sort_order')->get(['name', 'slug']),
        'rentTypes' => PropertyType::where('for_rent', true)->orderBy('sort_order')->get(['name', 'slug']),
    ]));
});
    }
}
