// src/led.h
#ifndef LED_H
#define LED_H

#include <Arduino.h>

enum LedMode {
  LED_OFF,
  LED_WIFI_CONNECTED,
  LED_MQTT_PUBLISH_SUCCESS
};

class LedManager {
public:
  LedManager(int ledPin, bool activeLow)
    : _ledPin(ledPin),
      _activeLow(activeLow),
      _currentLedMode(LED_OFF),
      _previousLedMillis(0),
      _ledPhysicalState(activeLow ? HIGH : LOW), // Initial physical state is OFF
      _mqttBlinkCount(0) {
  }

  void setup() {
    pinMode(_ledPin, OUTPUT);
    setLedState(false); // Ensure LED is off initially
  }

  void handleBlinking() {
    unsigned long currentMillis = millis();
    unsigned long interval = 0;

    switch (_currentLedMode) {
      case LED_OFF:
        setLedState(false);
        break;

      case LED_WIFI_CONNECTED:
        // Blink 2 times a second (toggle every 250ms)
        interval = 250;
        if (currentMillis - _previousLedMillis >= interval) {
          _previousLedMillis = currentMillis;
          // Toggle the internal state, then apply it
          setLedState(!(_ledPhysicalState == (_activeLow ? LOW : HIGH)));
        }
        break;

      case LED_MQTT_PUBLISH_SUCCESS:
        // Triple blink (fast toggle every 100ms)
        interval = 100;
        if (_mqttBlinkCount > 0) {
          if (currentMillis - _previousLedMillis >= interval) {
            _previousLedMillis = currentMillis;
            // Toggle the internal state, then apply it
            setLedState(!(_ledPhysicalState == (_activeLow ? LOW : HIGH)));
            _mqttBlinkCount--; // Decrement after each state change
            if (_mqttBlinkCount == 0) {
              // Sequence finished, revert to Wi-Fi mode
              setLedState(false); // Ensure LED is OFF at the end
              _currentLedMode = LED_WIFI_CONNECTED;
              _previousLedMillis = currentMillis; // Reset timer for smooth transition
            }
          }
        } else {
            // Fallback if mqttBlinkCount somehow goes to 0 mid-blink
            setLedState(false);
            _currentLedMode = LED_WIFI_CONNECTED;
        }
        break;
    }
  }

  void setMode(LedMode mode) {
    _currentLedMode = mode;
    if (mode == LED_OFF) {
      setLedState(false);
    } else {
      _previousLedMillis = millis(); // Reset timer for new mode
      setLedState(false); // Ensure starting from OFF so first toggle turns it ON
    }
  }

  void triggerMqttSuccessBlink() {
    _currentLedMode = LED_MQTT_PUBLISH_SUCCESS;
    _mqttBlinkCount = 6; // 3 blinks = 3 ON + 3 OFF cycles = 6 state changes
    _previousLedMillis = millis(); // Reset timer for the sequence
    setLedState(true); // Start by turning LED ON
  }

private:
  int _ledPin;
  bool _activeLow; // True if LED is active LOW (e.g., ESP8266), False for active HIGH (e.g., ESP32)
  LedMode _currentLedMode;
  unsigned long _previousLedMillis;
  int _ledPhysicalState; // Reflects HIGH/LOW to be written to pin (respects _activeLow)
  int _mqttBlinkCount;   // Counter for the triple blink sequence

  void setLedState(bool on) { // Helper function to abstract activeLow logic
    if (on) {
      digitalWrite(_ledPin, _activeLow ? LOW : HIGH); // ON
      _ledPhysicalState = (_activeLow ? LOW : HIGH);
    } else {
      digitalWrite(_ledPin, _activeLow ? HIGH : LOW); // OFF
      _ledPhysicalState = (_activeLow ? HIGH : LOW);
    }
  }
};

#endif // LED_H