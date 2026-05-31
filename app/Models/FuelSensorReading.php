<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelSensorReading extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the fuel_sensor that owns the FuelSensorReading
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fuel_sensor()
    {
        return $this->belongsTo(FuelSensor::class, 'fuel_sensor_id', 'id');
    }
}
