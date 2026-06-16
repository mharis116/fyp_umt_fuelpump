<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AnomalyDetectionService
{

    public static function predictAnomaly($data)
    {
        $data['variance'] *= -1; // model trained on opposit sign value
        $url = env("AI_AGENT_URL")."/predict/anomaly";
        try {
            $response = Http::timeout(30)->post($url, [
                'data' => $data,
            ]);

            if ($response->failed()) {
                return [];
            }
            return  $response->json() ?? [];

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function test(){
        $data = [
            "last_dip_qty"=>3.5,
            "elapsed_hours"=>24.1,
            "temperature"=>35.0,
            "humidity"=>45.0,
            "total_sales_qty"=>0.2,
            "total_sales_count"=>2,
            "total_purchase_qty"=>0.0,
            "total_purchase_count"=>0,
            "qty"=>0.8,
            "variance"=>2.5,
            "abs_variance"=>2.5
        ];


        $response = self::predictAnomaly($data);

        dd($response);
    }

}