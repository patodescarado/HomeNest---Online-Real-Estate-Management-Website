<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MyPropertyController;
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
    Route::get('/yeu-thich', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/tin-dang/{property}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/thong-tin-ca-nhan',
        [ProfileController::class, 'show']
    )->name('profile.show');
});

Route::get('/my-properties/create', [MyPropertyController::class, 'create'])
    ->name('my-properties.create');

require __DIR__.'/auth.php';
