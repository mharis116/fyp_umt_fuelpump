// src/wifi.h
#ifndef WIFI_H
#define WIFI_H

#include <ESP8266WiFi.h> // Correct library for ESP8266

class WiFiManager {
public:
  WiFiManager(const char* ssid, const char* password)
    : _ssid(ssid), _password(password) {
  }

  bool connect() {
    Serial.println("\nConnecting to WiFi...");
    Serial.print("SSID: ");
    Serial.println(_ssid);

    WiFi.begin(_ssid, _password);

    int retries = 0;
    while (WiFi.status() != WL_CONNECTED && retries < 40) { // Try for ~20 seconds
      delay(500);
      Serial.print(".");
      retries++;
    }

    return WiFi.status() == WL_CONNECTED;
  }

  String getMacAddress() {
    return WiFi.macAddress();
  }

private:
  const char* _ssid;
  const char* _password;
};

#endif // WIFI_H