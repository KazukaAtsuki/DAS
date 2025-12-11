<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourlyLog extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sensorConfig()
    {
        return $this->belongsTo(SensorConfig::class, 'sensor_config_id');
    }

    public function stackConfig()
    {
        return $this->belongsTo(StackConfig::class, 'stack_config_id');
    }
}