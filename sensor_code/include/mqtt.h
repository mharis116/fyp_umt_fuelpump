// src/mqtt.h
#ifndef MQTT_H
#define MQTT_H

#include <Arduino.h>
#include <PubSubClient.h>
#include <ESP8266WiFi.h> // For WiFiClient

class MqttClientManager {
public:
  MqttClientManager(WiFiClient& espClient, const char* mqttServer, int mqttPort, const char* mqttTopic)
    : _client(espClient),
      _mqttServer(mqttServer),
      _mqttPort(mqttPort),
      _mqttTopic(mqttTopic) {
      _client.setServer(_mqttServer, _mqttPort);
      _clientId[0] = '\0'; // Initialize client ID to empty
  }

  void setClientId(const char* clientId) {
      strncpy(_clientId, clientId, sizeof(_clientId) - 1);
      _clientId[sizeof(_clientId) - 1] = '\0'; // Ensure null-termination
      Serial.print("MQTT Client ID set to: ");
      Serial.println(_clientId);
  }

  void loop() {
    if (!_client.connected()) {
      reconnect();
    }
    _client.loop(); // Must be called frequently to process MQTT messages
  }

  bool publish(const char* topic, const char* payload) {
    if (_client.connected()) {
      return _client.publish(topic, payload);
    } else {
      Serial.println("MQTT client not connected, cannot publish.");
      return false;
    }
  }

  int getClientState() {
      return _client.state();
  }

private:
  PubSubClient _client;
  const char* _mqttServer;
  int _mqttPort;
  const char* _mqttTopic; // Default topic
  char _clientId[30]; // Store client ID internally (e.g., "ESP8266_MACADDRESS")

  void reconnect() {
    while (!_client.connected()) {
      Serial.print("Attempting MQTT connection...");
      // Attempt to connect using the generated client ID
      if (_clientId[0] == '\0') {
        Serial.println("Error: MQTT Client ID not set. Cannot connect.");
        delay(5000); // Wait and retry
        return;
      }
      if (_client.connect(_clientId)) {
        Serial.println("MQTT connected!");
        // If you had subscriptions, you would resubscribe here:
        // _client.subscribe("your/subscription/topic");
      } else {
        Serial.print("MQTT connection failed, rc=");
        Serial.print(_client.state());
        Serial.println(" Retrying in 5 seconds...");
        delay(5000);
      }
    }
  }
};

#endif // MQTT_H