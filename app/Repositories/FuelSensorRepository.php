<?php

namespace App\Repositories;


use App\Models\FuelSensorReading;
use App\Models\FuelSensor;

class FuelSensorRepository
{
    public  function __construct()
    {
        // Constructor logic if needed
    }

    public function find($filters = [])
    {
        $query =  FuelSensorReading::query();

        
        if(isset($filters['id'])) {
            $query->where('id', $filters['id']);
        }
        if(isset($filters['fuel_sensor_id'])) {
            $query->where('fuel_sensor_id', $filters['fuel_sensor_id']);
        }

        if(isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'].' 00:00:00', $filters['end_date'].' 23:59:59']);
        }

        return $query;
    }

    public function get_last_reading_by_stock($stock_id)
    {
        $sensor = FuelSensor::where('stock_id', $stock_id)->first(); // always unique for every same product product has many stocks but stock belongs 1 product
        if(!$sensor){
            return [];
        }
        return $this->find([
            'fuel_sensor_id' => $sensor?->id
        ])
        // ->where('created_at', '>', now()->subtractMinutes(10))
        ->latest()
        ->first();

    }
}