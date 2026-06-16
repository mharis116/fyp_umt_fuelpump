// src/main.cpp

#include <Arduino.h> // Always include Arduino.h in main.cpp
#include "led.h"     // Custom LED Manager
#include "wifi.h"    // Custom WiFi Manager
#include "mqtt.h"    // Custom MQTT Client Manager
#include "distance_sensor.h" // Custom Distance Sensor functions and pins

/* ===============================
  WIFI & MQTT CREDENTIALS
  =============================== */
// Replace with your network credentials
// const char* WIFI_SSID = "Sultani Net";
// const char* WIFI_PASSWORD = "htsaxon1214";
const char* WIFI_SSID = "S24ultra haris";
const char* WIFI_PASSWORD = "haris123";

// HiveMQ Free MQTT Broker Details
const char* MQTT_SERVER = "broker.hivemq.com";
const int MQTT_PORT = 1883; // Use 1883 for unencrypted TCP
const char* MQTT_TOPIC = "fuel_1214_htsaxon"; // IMPORTANT: Make your topic unique!

/* ===============================
   PIN DEFINITIONS (Only non-sensor pins remain here)
   =============================== */
// TRIG_PIN and ECHO_PIN are now in distance_sensor.h
#define ONBOARD_LED LED_BUILTIN // Defined in platformio.ini to GPIO2 (D4)

/* ===============================
   CALIBRATION VALUES
   =============================== */
const float DISTANCE_AT_0_LITERS = 20.80f; // cm
const float DISTANCE_AT_N_LITERS = 2.97f; // cm
const float MAX_WATER_VOLUME_LITERS = 5.0f; // liters

/* ===============================
  GLOBAL OBJECTS
  =============================== */
// Global WiFiClient instance needed by PubSubClient
WiFiClient espClient;

// Instantiate your managers, passing constants
WiFiManager wifiManager(WIFI_SSID, WIFI_PASSWORD);
LedManager ledManager(ONBOARD_LED, true); // true for active LOW LED (typical for ESP8266 onboard LED)
MqttClientManager mqttClientManager(espClient, MQTT_SERVER, MQTT_PORT, MQTT_TOPIC);

long lastDataPublishMillis = 0; // To track last publish time for sensor data

/* ===============================
   SETUP
   =============================== */
void setup() {
  Serial.begin(115200);
  // Pin modes for TRIG and ECHO are now handled in the measure_distance() function's context if not defined globally
  // We'll add them here for explicit setup
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);
  digitalWrite(TRIG_PIN, LOW); // Ensure trigger is low initially


  ledManager.setup(); // Initialize LED pin

  Serial.println("\n--- Starting Water Level Monitoring & MQTT Client ---");

  // Connect to WiFi
  if (wifiManager.connect()) {
    Serial.println("\nWiFi connected successfully!");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
    ledManager.setMode(LED_WIFI_CONNECTED); // Set LED mode to Wi-Fi connected

    // Generate MQTT Client ID after WiFi is up to get MAC address
    char clientIDBuffer[30];
    sprintf(clientIDBuffer, "ESP8266_%s", wifiManager.getMacAddress().c_str());
    mqttClientManager.setClientId(clientIDBuffer);
    Serial.print("Generated MQTT Client ID: ");
    Serial.println(clientIDBuffer);

  } else {
    Serial.println("\nFailed to connect to WiFi. Restarting ESP...");
    delay(5000);
    ESP.restart();
  }

  Serial.println("\n--- Sensor Calibration Details ---");
  Serial.print("Empty container distance (0L): ");
  Serial.print(DISTANCE_AT_0_LITERS);
  Serial.println(" cm");
  Serial.print("Full container distance (8L): ");
  Serial.print(DISTANCE_AT_N_LITERS);
  Serial.println(" cm");
  Serial.print("Max water volume: ");
  Serial.print(MAX_WATER_VOLUME_LITERS);
  Serial.println(" liters");
  Serial.println("------------------------------------\n");
}

/* ===============================
   LOOP
   =============================== */
void loop() {
  ledManager.handleBlinking(); // Constantly manage LED state

  mqttClientManager.loop(); // Maintain MQTT connection and process messages

  unsigned long currentMillis = millis();
  // Publish data every 5 seconds (adjust as needed)
  if (currentMillis - lastDataPublishMillis > 5000) {
    lastDataPublishMillis = currentMillis;

    Serial.println("\n--- Performing Measurement and Publishing Data ---");

    // 1. Measure Distance
    float distance_cm = measure_distance(); // Function from distance_sensor.h
    Serial.print("Measured Distance: ");
    Serial.print(distance_cm, 2);
    Serial.println(" cm");

    // Handle invalid distance readings
    if (distance_cm < 0) {
        Serial.println("Skipping data calculation and MQTT publish due to invalid sensor reading.");
        Serial.println("------------------------------------\n");
        return; // Skip the rest of the loop to avoid publishing bad data
    }

    // 2. Calculate Water Depth
    float water_depth_cm = DISTANCE_AT_0_LITERS - distance_cm;
    Serial.print("Calculated Water Depth: ");
    Serial.print(water_depth_cm, 2);
    Serial.println(" cm");

    // 3. Calculate the range of distance that represents the water volume
    float measurable_distance_range = DISTANCE_AT_0_LITERS - DISTANCE_AT_N_LITERS;
    if (measurable_distance_range == 0) measurable_distance_range = 0.01f; // Prevent division by zero

    // 4. Calculate water volume in liters
    float water_liters = (water_depth_cm / measurable_distance_range) * MAX_WATER_VOLUME_LITERS;
    if (water_liters < 0) {
      water_liters = 0;
    } else if (water_liters > MAX_WATER_VOLUME_LITERS) {
      water_liters = MAX_WATER_VOLUME_LITERS;
    }
    Serial.print("Estimated Water Volume: ");
    Serial.print(water_liters, 2);
    Serial.println(" L");

    // 5. Calculate percentage fullness
    float fullness_percentage = (water_liters / MAX_WATER_VOLUME_LITERS) * 100.0f;
    if (fullness_percentage < 0) fullness_percentage = 0; // Clamp percentage
    if (fullness_percentage > 100) fullness_percentage = 100;

    Serial.print("Calculated Fullness: ");
    Serial.print(fullness_percentage, 1);
    Serial.println(" %");

    // 6. Prepare JSON payload for MQTT
    char msgBuffer[100];
    sprintf(msgBuffer, "{\"distance_cm\":%.2f,\"water_liters\":%.2f,\"fullness_percent\":%.1f}",
            distance_cm, water_liters, fullness_percentage);

    Serial.print("Prepared MQTT Payload: ");
    Serial.println(msgBuffer);
    Serial.print("Attempting to publish to topic: ");
    Serial.println(MQTT_TOPIC);

    // 7. Publish to MQTT
    if (mqttClientManager.publish(MQTT_TOPIC, msgBuffer)) {
      Serial.println("MQTT message published successfully! Triggering triple blink.");
      ledManager.triggerMqttSuccessBlink(); // Indicate successful publish with LED
    } else {
      Serial.print("Failed to publish MQTT message, client state: ");
      Serial.println(mqttClientManager.getClientState());
    }
    Serial.println("------------------------------------\n");
  }
}