#include <ESP8266HTTPClient.h>
#include <ESP8266mDNS.h>
#include <ESP8266WiFi.h>
#include <EEPROM.h>
#include <SPI.h>
#include <FLAGSTAT.h>
#include <Base64x.h>

/**
 * The gateway takes and sends data to flag nodes based on game mode and POST responses from the web server.
 * The gateway connects to a hotspot for internet and uses nRF24L01 radios for flags.
 * Packed Flag data: byte 1 = bit0-enable/disable, bit123 owner, bit456 mode, bit7 button
 * Packed Flag data: byte 2 = Battery Level
 * Packed Flag data: byte 3 = Last Seen (secs)
 */

#define MY_DEBUG 
#define MY_BAUD_RATE 9600
#define MY_RADIO_NRF24
#define MY_RF24_PA_LEVEL RF24_PA_MAX
#define MY_GATEWAY_ESP8266

//#define MY_ESP8266_SSID "D14FieldOps"
//#define MY_ESP8266_PASSWORD "D14Gateway"
//#define MY_ESP8266_SSID "xxxxx"
//#define MY_ESP8266_PASSWORD "xxxxx"
//#define MY_IP_GATEWAY_ADDRESS 192,168,1,93
#define MY_ESP8266_SSID "xxxx"
#define MY_ESP8266_PASSWORD "xxxx"
#define MY_IP_GATEWAY_ADDRESS 10,10,30,60
#define MY_IP_SUBNET_ADDRESS 255,255,255,0
#define MY_ESP8266_HOSTNAME "D14GW"
#define MY_PORT 5003      
#define MY_GATEWAY_MAX_CLIENTS 2
// Controller ip address. Enables client mode (default is "server" mode). 
// Also enable this if MY_USE_UDP is used and you want sensor data sent somewhere. 
//#define MY_CONTROLLER_IP_ADDRESS 192, 168, 178, 68
#include <MySensors.h> // Always include this last, after all #defines.

unsigned long battReport;
unsigned long heartbeat; // 3 minutes, to check all flags for heartbeat.
unsigned long fieldUpdate;
unsigned long updPeriod = 10000; // Sets how often the GW updates the web server.
const char* wwwUserName = "admin";
const char* wwwPassword = "xxxx"; // TODO - store and retrieve this from EEPROM
const char* wwwServ = "http://www.d14airsoft.com/field/process/proc.php?"; //PRODUCTION
byte flagArray[20]; // Enabled,Mode,Owner,Button - this is what the flag sent us
byte battArray[20]; // battery 0-100
byte spwnArray[20]; // Spawn Delay time 0-255
byte servArray[20]; // Enabled,Mode,Owner,Button - but this is what the server sent us
bool throttled = false; // If true, gw sleeps as the entire field is disabled.
unsigned int flagTimes[20][4]; // Units: Seconds purpose: Timestamp of last contact, green held increment, tan held..., blue held
// The idea is current millis -timestamp gives time held, add it to the existing value controlled by the owner
MyMessage statusMsg(0, V_VAR1);
MyMessage spawnMsg(1, V_VAR2);
FLAGSTAT flagStat; // Initialize the class for working with flag status.

void postTimes(byte flag) {
  HTTPClient http;
  String postLine;
  postLine="a=ft&id=" + String(flag+1) + "&tg=" + String(flagTimes[flag][1]) + "&tt=" + String(flagTimes[flag][2]) + "&tb=" + String(flagTimes[flag][3]);
//  Serial.println(String(millis()) + " PostLine: " + postLine);
  http.begin(wwwServ);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST(postLine);
  String payload = http.getString();
  http.end();
//  Serial.println("HTTP response length:" + String(payload.length()));
//  Serial.println("HTTP response:" + payload);
}

void postFlagNode() {
  // This is for posting to web servers.
  HTTPClient http;
  String postLine;
  postLine="a=u&fa=";
  int inputLen = sizeof(flagArray);
  int encodedLen = base64_enc_len(inputLen);
  char encoded[encodedLen];
  base64_encode(encoded, (char *)flagArray, inputLen); 
  postLine+=encoded;
//  Serial.println(String(millis()) + " Flag PostLine: " + postLine);
  http.begin(wwwServ);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST(postLine);
  String payload = http.getString();
  http.end();
  if ((httpCode==200) && (payload.length()>=39) && (payload.length()<=79)) {
//    Serial.println("HTTP response length:" + String(payload.length()));
//    Serial.println("HTTP response:" + payload);
    bool fieldEnabled = false;
    const char c[2]=",";
    char *value;
    int i=0;
    byte sentArray[20];
    value = strtok(&payload[0],c); // Split the incoming string by the comma token and set a byte array to the individual values.
    while( value != NULL ) {
      if (i<20) { // If its more than 20, we will overflow our array, so just stop assigning.
        sentArray[i]=atoi(value);
      }
      value = strtok(NULL, c);
      i++;
    }
    if (i=20) { // If i got something other than 20 values, something is wrong, and dont assign the incoming data.
      for (i=0;i<20;i++) {
        servArray[i]=sentArray[i];
        // Check all flags for enabled. If none are enabled at end of loop, alter web checkin to 5 minutes.
        if (flagStat.enabled(servArray[i])) {
          fieldEnabled = true;
        }
        if ((servArray[i]!=flagArray[i])) { // Something is different.
          bool doSend = false;
          if (flagStat.getMode(servArray[i])!=flagStat.getMode(flagArray[i]))  {
            doSend = true; // if the mode changed, update.
            // If its Game Over, send the times up.
            if (flagStat.getMode(servArray[i]) == 6) {
              postTimes(i);
            }
            // If the mode is now sleep, clear the flag times for the flag. 
            if (flagStat.getMode(servArray[i]) == 0) {
              flagTimes[i][1] = 0; //green
              flagTimes[i][2] = 0; //tan
              flagTimes[i][3] = 0; //blue
            }
          }
          if (flagStat.enabled(servArray[i])!=flagStat.enabled(flagArray[i])) {
            doSend = true; // if the enabled changed, update.
            // Clear the flag times for the flag in question, if the mode is now off.
            if (flagStat.enabled(servArray[i])==false) {
              flagTimes[i][1] = 0; //green
              flagTimes[i][2] = 0; //tan
              flagTimes[i][3] = 0; //blue
            }
          }
          if ( (flagStat.getOwner(servArray[i])!=flagStat.getOwner(flagArray[i])) && (flagStat.getOwner(servArray[i])!=5) ) {
            doSend = true; // if the owner is different, and desired <> 5 dont care, update.
          }
          if (doSend && (transportGetRoute(i+1) != 255)) { // If we need to send data to a flag, and we know the route to it...
            statusMsg.setDestination(i+1);
            send(statusMsg.set(servArray[i]));
          }
        }
      }
    }
    if (fieldEnabled) {
      updPeriod = 10000;
      throttled = false;
    } else {
      updPeriod = 60000; // Should be 5 minutes if field is disabled - 300000.
      throttled = true;
      Serial.println("All Flags DISABLED. Web update is throttled!");
    }
  } else {
    Serial.println("Flag HTTP code:" + String(httpCode));
    Serial.println("Flag HTTP response:" + payload);      
  }
}

void postBatt() {
  HTTPClient http;
  String postLine;
  postLine="a=u&fb=";
  int inputLen = sizeof(battArray);
  int encodedLen = base64_enc_len(inputLen);
  char encoded[encodedLen];
  base64_encode(encoded, (char *)battArray, inputLen); 
  postLine+=encoded;
//  Serial.println(String(millis()) + " Batt PostLine: " + postLine);
  http.begin(wwwServ);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST(postLine);
  if (httpCode != 200) {
    Serial.println("Batt HTTP return:" + String(httpCode));
    Serial.println("Batt HTTP response:" + http.getString());      
  }
  http.end();
}

void getSpawnD() {
  HTTPClient http;
  String postLine;
  postLine="a=gsd";
//  Serial.println(String(millis()) + " Spwn PostLine: " + postLine);
  http.begin(wwwServ);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST(postLine);
  String payload = http.getString();
  if (httpCode != 200) {
    Serial.println("Spawn HTTP return:" + String(httpCode));
    Serial.println("Spawn HTTP response:" + payload);      
  }
  http.end();
  const char c[2]=",";
  char *value;
  int i=0;
  byte sentArray[20];
  value = strtok(&payload[0],c); // Split the incoming string by the comma token and set a byte array to the individual values.
  while( value != NULL ) {
    if (i<20) { // If its more than 20, we will overflow our array, so just stop assigning.
      sentArray[i]=atoi(value);
    }
    value = strtok(NULL, c);
    i++;
  }
  if (i=20) { // If i got something other than 20 values, something is wrong, and dont assign the incoming data.
    for (i=0;i<20;i++) {
      spwnArray[i]=sentArray[i];
    }
  }
}

void PingAll() { // Update all flags just to make sure they are alive.
  for (i=0;i<20;i++) {
    statusMsg.setDestination(i+1);
    send(statusMsg.set(servArray[i]));
  }
}

void setup() {
  // TODO fix voltage sense!
  pinMode(A0, INPUT);
  fieldUpdate = millis() + updPeriod;
}

void presentation() {
  // Present locally attached sensors here
  present(0, S_CUSTOM); // Present Sensor 0 as a custom type. (flag status)
  present(1, S_CUSTOM); // Present Sensor 0 as a custom type. (flag status)
}

void loop() {
  if (fieldUpdate < millis() ) {
    postFlagNode();
    // Post flag times if flag mode is 1-5
    byte tmpFlag;
    for (byte i=0;i<20;i++) {
      tmpFlag = flagStat.getMode(flagArray[i]);
      if ((tmpFlag > 0) && (tmpFlag < 6)) {
          postTimes(i);
      }
    }
    fieldUpdate = millis() + updPeriod;
  }
  if (battReport < millis() ) {
    postBatt(); // Send up battery values
    getSpawnD(); // ask for spawn delay times
    battReport = millis() + 60000;
    Serial.println("Batt-A0:" + String(analogRead(A0)/302) + "V");
  }
  if (heartbeat < millis()) {
    PingAll();
    heartbeat = millis() + 300000;
  }
  if (throttled) {
    Serial.println(String(millis()) + " Sleeping 10 secs.");
    sleep(20000);
  }
}

