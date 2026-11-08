<?php

use Illuminate\Support\Facades\Route;
Route::get('/', fn () => 'Trang admin')->name('dashboard');