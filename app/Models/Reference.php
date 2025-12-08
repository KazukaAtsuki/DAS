<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi ke Sensor Config
    public function sensorConfig()
    {
        return $this->belongsTo(SensorConfig::class, 'sensor_config_id');
    }
}
