<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WooCommerceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\OrdersController;

// Autenticación
Auth::routes();

// Rutas principales
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas principales de Ajustes
Route::prefix('ajustes')->as('ajustes.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::post('/perfil', [SettingsController::class, 'updateProfile'])->name('perfil');
    Route::post('/preferencias', [SettingsController::class, 'updatePreferences'])->name('preferencias');

    // Rutas para WooCommerce (wc)
    Route::prefix('wc')->as('wc.')->group(function () {
        Route::get('/credenciales', [WooCommerceController::class, 'showSettings'])->name('credenciales');
        Route::post('/verificar', [WooCommerceController::class, 'verifyConnection'])->name('verificar');
        Route::post('/guardar', [WooCommerceController::class, 'saveSettings'])->name('guardar');
    });
});

// Rutas para los pedidos de WooCommerce
Route::middleware('auth')->group(function () {
    Route::get('/pedidos', [OrdersController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/data', [OrdersController::class, 'fetchData'])->name('pedidos.fetch');
});
