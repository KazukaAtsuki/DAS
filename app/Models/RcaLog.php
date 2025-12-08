<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcaLog extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi ke Sensor
    public function sensorConfig()
    {
        return $this->belongsTo(SensorConfig::class, 'sensor_config_id');
    }

    // Relasi ke Stack
    public function stackConfig()
    {
        return $this->belongsTo(StackConfig::class, 'stack_config_id');
    }
}
