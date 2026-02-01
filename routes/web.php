<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\CommonPages\AdminViewController;

use App\Http\Controllers\Backend\KV\BrandController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [AdminViewController::class, 'dashboard'])->name('dashboard');

    Route::resources([
        'brands' => BrandController::class,
    ]);

});
