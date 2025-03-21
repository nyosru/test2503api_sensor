<?php

namespace App\Http\Controllers;

use App\Http\Resources\SensorDataResource;
use App\Models\SensorData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SensorController extends Controller
{

    /**
     * тащим данные истории сенсоров
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $parameter = $request->query('sensor_type');
        $from = $request->query('from');
        $to = $request->query('to');

        if (!$parameter || !$from || !$to || !in_array($parameter, ['T', 'P', 'v'])) {
            return response()->json(['message' => 'Некорректные параметры запроса'], 400);
        }

        $history = SensorData::
//        where('sensor_id', $sensorId)
//            ->
        where('sensor_type', $parameter)
            ->whereBetween('created_at', [new Carbon($from), new Carbon($to)])
            ->orderBy('created_at', 'asc')
            ->get(['created_at as timestamp', 'value']);
        return $history;
    }


    /**
     * @OA\Post(
     *     path="/api",
     *     summary="Прием данных от датчиков",
     *     description="API получает данные от датчика и сохраняет их в базе",
     *     operationId="storeSensorData",
     *     tags={"Sensor Data"},
     *     @OA\Parameter(
     *         name="sensor",
     *         in="query",
     *         required=true,
     *         description="Идентификатор датчика",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="text/plain",
     *             @OA\Schema(
     *                 type="string",
     *                 example="T=20"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Данные успешно сохранены",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Данные сохранены")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Некорректные данные",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Некорректные данные")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $sensorId = $request->query('sensor');
        $body = $request->getContent();

        if (!$sensorId || !$body || !preg_match('/^(T|P|v)=([\d.]+)$/', $body, $matches)) {
            return response()->json(['message' => 'Некорректные данные'], 400);
        }

        $sensorData = new SensorData();
//        $sensorData->sensor_id = $sensorId;
//        $sensorData->parameter = $matches[1]; // T, P или v
//        $sensorData->value = $matches[2];
        $sensorData->sensor_type = $matches[1];
        $sensorData->value = $matches[2];
        $sensorData->created_at = now();
        $sensorData->save();

        return response()->json(['message' => 'Данные сохранены']);
    }


    /**
     * @OA\Get(
     *     path="/api/history",
     *     summary="Получение истории параметра",
     *     description="Возвращает историю изменения параметра за определённый интервал",
     *     operationId="getSensorHistory",
     *     tags={"Sensor Data"},
     *     @OA\Parameter(
     *         name="sensor_type",
     *         in="query",
     *         required=true,
     *         description="Тип параметра (T - температура, P - давление, v - скорость вращения)",
     *         @OA\Schema(type="string", enum={"T", "P", "v"}, example="T")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=true,
     *         description="Начало интервала (формат: YYYY-MM-DD HH:MM:SS)",
     *         @OA\Schema(type="string", format="date-time", example="2025-03-20 12:00:00")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=true,
     *         description="Конец интервала (формат: YYYY-MM-DD HH:MM:SS)",
     *         @OA\Schema(type="string", format="date-time", example="2025-03-20 14:00:00")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="История параметра",
     *         @OA\JsonContent(
     *             @OA\Property(property="sensor_type", type="string", example="T"),
     *             @OA\Property(property="history", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="timestamp", type="string", format="date-time", example="2025-03-20 12:30:00"),
     *                     @OA\Property(property="value", type="number", example=20.5)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Некорректные параметры запроса",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Некорректные параметры запроса")
     *         )
     *     )
     * )
     */
    public function history(Request $request)
    {

        $history = $this->getData($request);
        $parameter = $request->query('sensor_type');

        return response()->json([
            'sensor_type' => $parameter,
            'history' => $history,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/history/r",
     *     summary="Получение истории параметра (используем API resource для подготовки ответа) ",
     *     description="Возвращает историю изменения параметра за определённый интервал ",
     *     operationId="getSensorHistoryR",
     *     tags={"Sensor Data"},
     *     @OA\Parameter(
     *         name="sensor_type",
     *         in="query",
     *         required=true,
     *         description="Тип параметра (T - температура, P - давление, v - скорость вращения)",
     *         @OA\Schema(type="string", enum={"T", "P", "v"}, example="T")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=true,
     *         description="Начало интервала (формат: YYYY-MM-DD HH:MM:SS)",
     *         @OA\Schema(type="string", format="date-time", example="2025-03-20 12:00:00")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=true,
     *         description="Конец интервала (формат: YYYY-MM-DD HH:MM:SS)",
     *         @OA\Schema(type="string", format="date-time", example="2025-03-20 14:00:00")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="История параметра",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="timestamp", type="string", format="date-time", example="2025-03-20 12:30:00"),
     *                     @OA\Property(property="value", type="number", example=20.5)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Некорректные параметры запроса",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Некорректные параметры запроса")
     *         )
     *     )
     * )
     */
    public function historyR(Request $request)
    {

        $history = $this->getData($request);
        $parameter = $request->query('sensor_type');

        return new SensorDataResource($history);

    }
}
