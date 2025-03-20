<?php

use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;


Route::post('api', [SensorController::class, 'store']); // Прием данных от датчиков
Route::get('api/history', [SensorController::class, 'history']); // Получение истории параметра

Route::get('/api', function (Request $request) {
    // Обработка запроса
    $sensor = $request->query('sensor');

    $param_t = $request->query('T');
    $param_p = $request->query('P');
    $param_v = $request->query('v');


    // Дальнейшая логика обработки запроса
    return response()->json(['message' => 'Запрос обработан']);
});

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
