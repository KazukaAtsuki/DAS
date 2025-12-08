<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorConfig extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke StackConfig
    public function stackConfig()
    {
        return $table = $this->belongsTo(StackConfig::class, 'stack_config_id');
    }

    public function latestLog()
    {
        return $this->hasOne(DasLog::class, 'sensor_config_id')->latestOfMany('timestamp');
    }

    // Relasi ke Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

}
