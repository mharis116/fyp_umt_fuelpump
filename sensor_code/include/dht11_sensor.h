// src/dht11_sensor.h
#ifndef DHT11_SENSOR_H
#define DHT11_SENSOR_H

#include <Arduino.h>
#include <DHT.h> // The actual DHT sensor library

/* ===============================
   PIN DEFINITIONS FOR DHT SENSOR
   =============================== */
#define DHT_PIN 0   // Pin for DHT11 sensor (e.g., GPIO0 on NodeMCU, D3)
#define DHT_TYPE 11 // Type of DHT sensor (11 for DHT11, 22 for DHT22)

class DhtManager {
public:
  DhtManager(int pin, uint8_t type, long readInterval = 2000)
    : _dht(pin, type),
      _readInterval(readInterval),
      _lastReadMillis(0),
      _temperatureC(20.0), // Default values in case first read fails
      _humidity(50.0) {
  }

  void begin() {
    _dht.begin();
    Serial.print("DHT sensor initialized on pin: "); Serial.println(DHT_PIN);
  }

  // Call this in your loop to periodically read the sensor
  void readSensor() {
    unsigned long currentMillis = millis();
    if (currentMillis - _lastReadMillis >= _readInterval) {
      _lastReadMillis = currentMillis;

      float h = _dht.readHumidity();
      float t = _dht.readTemperature();

      if (isnan(h) || isnan(t)) {
        Serial.println("DHT Manager: Failed to read from DHT sensor!");
        // Keep previous valid readings
      } else {
        _humidity = h;
        _temperatureC = t;
        // Serial.print("DHT Manager: Temp: "); Serial.print(_temperatureC, 1); Serial.print("°C, Hum: "); Serial.print(_humidity, 1); Serial.println("%");
      }
    }
  }

  float getTemperature() const {
    return _temperatureC;
  }

  float getHumidity() const {
    return _humidity;
  }

private:
  DHT _dht;
  long _readInterval;
  unsigned long _lastReadMillis;
  float _temperatureC;
  float _humidity;
};

#endif // DHT11_SENSOR_H