<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public static function getCurrentWeather($lat, $lon)
    {
        $url = "https://api.open-meteo.com/v1/forecast";
        
        $timezone = env('WEATHER_TIMEZONE', 'Asia/Karachi');
        $response = Http::get($url, [
            'latitude' => $lat,
            'longitude' => $lon,
            'current' => 'temperature_2m,relative_humidity_2m',
            'timezone' => $timezone
        ]);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        return [
            'temperature' => $data['current']['temperature_2m'] ?? null,
            'humidity' => $data['current']['relative_humidity_2m'] ?? null,
        ];
    }
}