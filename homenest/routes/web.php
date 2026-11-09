<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tin-dang', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/mua-ban/{slug?}', [PropertyController::class, 'index'])->defaults('kind', 'sale')->name('sale.index');
Route::get('/cho-thue/{slug?}', [PropertyController::class, 'index'])->defaults('kind', 'rent')->name('rent.index');
Route::get('/tin-dang/{property}', [PropertyController::class, 'show'])->name('properties.show');

Route::get('/api/provinces/{province}/districts', [LocationController::class, 'districts']);

Route::middleware('auth')->group(function () {
    Route::resource('my-properties', MyPropertyController::class)
        ->except('show')->parameters(['my-properties' => 'property']);
    Route::patch('my-properties/{property}/hide',   [MyPropertyController::class, 'hide'])->name('my-properties.hide');
    Route::patch('my-properties/{property}/unhide', [MyPropertyController::class, 'unhide'])->name('my-properties.unhide');
    Route::patch('my-properties/{property}/renew',  [MyPropertyController::class, 'renew'])->name('my-properties.renew');
});

require __DIR__.'/auth.php';
