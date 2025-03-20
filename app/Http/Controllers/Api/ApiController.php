<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/example",
     *      summary="Пример запроса",
     *      description="Получение примера",
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ"
     *      )
     * )
     */
    public function example()
    {
        return response()->json(['message' => 'Пример данных']);
    }
}
