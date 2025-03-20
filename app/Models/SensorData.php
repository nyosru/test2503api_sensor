<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_type',
        'value',
    ];

    protected $table = 'sensor_data';
    public $timestamps = false; // Отключение временных полей
}
