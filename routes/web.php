<?php

use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'api'], function () {
    Route::post('', [SensorController::class, 'store']); // Прием данных от датчиков
    Route::get('history', [SensorController::class, 'history']); // Получение истории параметра
    Route::get('history/r', [SensorController::class, 'historyR']); // Получение истории параметра
});








Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
