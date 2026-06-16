// src/distance_sensor.h
#ifndef DISTANCE_SENSOR_H
#define DISTANCE_SENSOR_H

#include <Arduino.h>

/* ===============================
   PIN DEFINITIONS FOR ULTRASONIC SENSOR
   =============================== */
// #define TRIG_PIN D5   // GPIO14 (D5 on NodeMCU)
// #define ECHO_PIN D6   // GPIO12 (D6 on NodeMCU)
#define TRIG_PIN 14   // GPIO14 d5
#define ECHO_PIN 12   // GPIO12 d6
/* ===============================
   ULTRASONIC DISTANCE MEASUREMENT FUNCTION
   =============================== */
// float measure_distance() {
//   // Clear the TRIG_PIN by setting it LOW for 2 us
//   digitalWrite(TRIG_PIN, LOW);
//   delayMicroseconds(2);

//   // Set the TRIG_PIN HIGH for 10 us to send a pulse
//   digitalWrite(TRIG_PIN, HIGH);
//   delayMicroseconds(10);
//   digitalWrite(TRIG_PIN, LOW);

//   // Read the ECHO_PIN, returns the duration of the pulse in microseconds
//   // pulseIn will timeout after 0.5 seconds if no pulse is received
//   long duration = pulseIn(ECHO_PIN, HIGH, 500000);

//   if (duration == 0) {
//     // Sensor timed out or didn't receive an echo, return an invalid distance
//     return -1.0f; // Indicate an error or invalid reading
//   }

//   // Calculate distance in cm
//   // Speed of sound at 20°C is approximately 343 meters/second, or 0.0343 cm/microsecond.
//   // Divide by 2 because the sound travels to the object and back.
//   float distance_cm = duration * 0.0343 / 2;

//   // Add a small delay between measurements to prevent sensor interference
//   delay(50);

//   return distance_cm;
// }
float measure_distance() {
  const int numReadings = 7; // Take 7 readings
  long readings[numReadings];
  int validReadings = 0;

  for (int i = 0; i < numReadings; i++) {
    digitalWrite(TRIG_PIN, LOW); delayMicroseconds(2);
    digitalWrite(TRIG_PIN, HIGH); delayMicroseconds(10);
    digitalWrite(TRIG_PIN, LOW);
    long duration = pulseIn(ECHO_PIN, HIGH, 40000); // Shorter timeout for tank
    if (duration > 0 && duration < 30000) { // Filter out 0 (timeout) and excessively long durations
      readings[validReadings++] = duration;
    }
    delay(10); // Small delay between readings
  }

  if (validReadings == 0) {
    return -1.0f; // No valid readings
  }

  // Simple average (can be replaced with median filter for better outlier rejection)
  long sum = 0;
  for (int i = 0; i < validReadings; i++) {
    sum += readings[i];
  }
  long avgDuration = sum / validReadings;

  // Calculate distance with potentially temperature-compensated speed of sound
  // For this example, assuming a fixed speed (0.0343 cm/us at 20°C)
//   return avgDuration * 0.0343 / 2;
  return avgDuration * 0.0343 / 2;
}

#endif // DISTANCE_SENSOR_H