<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\MqttClientException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use App\Models\FuelSensorReading;
use App\Models\FuelSensor;
use Throwable;

class MqttFuelSubscriber extends Command
{
    // sudo systemctl status fuelpump-mqtt
    protected $signature = 'mqtt:subscribe:fuel';
    protected $description = 'Subscribes to fuel topic using php-mqtt/client v2.x';

    public function handle()
    {
        $topic = 'fuel_1214_htsaxon';

        $host     = env('MQTT_HOST', 'broker.hivemq.com');
        $port     = (int) env('MQTT_PORT', 1883);
        $clientId = 'laravel_fuel_' . time(); // Force uniqueness every run

        $this->info("Connecting to {$host}:{$port} with Client ID: {$clientId}");

        $mqtt = null;


        

        try {
            // 1. Setup Logger (6th param of MqttClient)
            $logFile = storage_path('logs/mqtt_debug.log');
            $mqttLogger = new Logger('mqtt');
            $mqttLogger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));

            // 2. Setup Connection Settings
            $connectionSettings = (new ConnectionSettings())
                ->setKeepAliveInterval(60)
                ->setConnectTimeout(10)
                ->setRetainLastWill(false); // Documentation confirms this name

            // 3. Instantiate MqttClient
            // Parameters: host, port, clientId, protocol, repository, logger
            $mqtt = new MqttClient($host, $port, $clientId, MqttClient::MQTT_3_1_1, null, $mqttLogger);

            // 4. Register Connected Hook (Explicitly in your provided docs)
            $mqtt->registerConnectedEventHandler(function (MqttClient $mqtt, bool $isAutoReconnect) {
                $this->info($isAutoReconnect ? "Auto-reconnected to Broker!" : "Connected to Broker!");
            });

            // 5. Connect
            $mqtt->connect($connectionSettings, true);

            // 6. Subscribe
            $mqtt->subscribe($topic, function (string $topic, string $message) use($mqtt) {
                $this->info("\n[Received] Topic: {$topic}");
                $this->line("Payload: {$message}");

                $payload = json_decode($message, true);
                if (json_last_error() === JSON_ERROR_NONE) {

                    $this->comment("Distance: " . ($payload['distance_cm'] ?? 'N/A') . " cm");
                    $this->comment("Fullness: " . ($payload['fullness_percent'] ?? 'N/A') . " %");
                    $this->comment("liters: " . ($payload['water_liters'] ?? 'N/A') . " ltrs");

                    $this->record_reading($payload);
                }
                
            }, 0);

            

            $this->info("Subscribed to '{$topic}'. Listening for messages...");

            // 7. Loop
            $mqtt->loop(true); // Blocking loop with short sleep to reduce CPU usage


        } catch (MqttClientException $e) {
            $this->error("MQTT Exception: " . $e->getMessage());
            return Command::FAILURE;
        } catch (Throwable $e) {
            $this->error("General Error: " . $e->getMessage());
            return Command::FAILURE;
        } finally {
            if ($mqtt && $mqtt->isConnected()) {
                $mqtt->disconnect();
            }
        }

        return Command::SUCCESS;
    }

    public function record_reading($payload)
    {
        try {
            $product_id = 14; // Example product ID
            $stock_id = 10; // Example product ID
            $sensor = FuelSensor::where('product_id', $product_id)->where('stock_id', $stock_id)->with(['stock'])->firstOrFail();
            // dd($sensor->toArray());

            $distance = $payload['distance_cm'] ?? 0.0;

            if($sensor?->stock){
                [$liters, $fullness] = $this->calculate_liters_fullness($sensor, $distance);
                $this->info("Calculated Liters: {$liters} ltrs, Fullness: {$fullness} %");
                FuelSensorReading::create([
                    'fuel_sensor_id' => $sensor->id, // Example sensor ID
                    'percentage_full' => $fullness,
                    'distance_from_fuel_level' => $distance,
                    'quantity_in_ltrs' => $liters,
                ]);
            }

            // $fullness = $payload['fullness_percent'] ?? 0.0;
            // $liters = $payload['water_liters'] ?? 0.0;




            
            $this->info("Reading recorded in database.");
        } catch (Throwable $e) {
            $this->error("Failed to record reading: " . $e->getMessage());
        }
    }

    public function calculate_liters_fullness($sensor, $distance){
        // const float DISTANCE_AT_0_LITERS = 20.80f; // cm
        // const float DISTANCE_AT_N_LITERS = 2.97f; // cm
        // const float MAX_WATER_VOLUME_LITERS = 5.0f; // liters

        $distanceAt0Liters = $sensor?->stock?->distance_at_0_liters; // cm
        $distanceAtNLiters = $sensor?->stock?->distance_at_n_liters; // cm
        // $maxWaterVolumeLiters = $sensor?->stock?->max_water_volume_liters; // liters
        $maxWaterVolumeLiters = $sensor?->stock?->stock_capacity; // liters

        $this->info("Using Sensor Config - Distance at 0L: {$distanceAt0Liters} cm, Distance at N L: {$distanceAtNLiters} cm, Max Volume: {$maxWaterVolumeLiters} ltrs");

        if ($distance < 0) {
            $this->warn("Invalid sensor reading: {$distance} cm. Skipping calculation.");
            // return [0.0, 0.0]; // Return zero values for invalid readings
            throw new \InvalidArgumentException("Invalid sensor reading: {$distance} cm");
        }

        $waterDepthCm = $distanceAt0Liters - $distance;
        $measurableDistanceRange = $distanceAt0Liters - $distanceAtNLiters;
        if ($measurableDistanceRange == 0) {
            $measurableDistanceRange = 0.01; // Prevent division by zero
        }

        $water_liters = ($waterDepthCm / $measurableDistanceRange) * $maxWaterVolumeLiters;

        if ($water_liters < 0) {
            $water_liters = 0;
        } elseif ($water_liters > $maxWaterVolumeLiters) {
            $water_liters = $maxWaterVolumeLiters;
        }

        $fullness_percentage = ($water_liters / $maxWaterVolumeLiters) * 100.0;
        if ($fullness_percentage < 0) {
            $fullness_percentage = 0; // Clamp percentage
        } elseif ($fullness_percentage > 100) {
            $fullness_percentage = 100;
        }

        return [round($water_liters, 2), round($fullness_percentage ,2)];
    }
}
