// ProMini E-Flag Repeater. Copyright Reid Bush 2017
//#define MY_DEBUG // Keep Debug off, it uses tons of prog memory.
#define MY_REPEATER_FEATURE
#define MY_RADIO_NRF24
#define MY_RF24_PA_LEVEL RF24_PA_MAX
#define MY_RF24_CHANNEL 83
#define MY_NODE_ID 1
#define SN "Flag_Node"
#define SV "2.0"
#define MY_BAUD_RATE 9600

#define Start_Byte 0x7E // MP3 CMD Start Byte
#define Version_Byte 0xFF // MP3 CMD Version Byte
#define Command_Length 0x06 // MP3 CMD Length Byte
#define End_Byte 0xEF // MP3 CMD End Byte
#define Acknowledge 0x00 //Returns info with command 0x41 [0x01: info, 0x00: no info]

#define BTN_PIN 2 // Button Sense Pin
#define MP3_PIN 7 // PIN for MP3 power control.
#define VOLT_PIN 0 // Analog pin for volt reading.
#define LED_PIN 5 //  PIN for serial to ws2812s.
#define PS_PIN 6 // PIN for LED power control (inverted).
#include <WS2812.h>
#include <FLAGSTAT.h>
#include <SPI.h>
#include <MySensors.h>

MyMessage statusMsg(0, V_VAR1); // Invoke Mysensors Message for Status
MyMessage spawnMsg(0, V_VAR2); // Invoke Mysensors Message for Spawn Delay
int sleepLower = 25000; // Lower limit of sleep timeout.
int sleepUpper = 45000; // Upper limit of sleep timeout.
bool buttonStatus = false;  // False = Not pressed.
bool disabled = false; // Flag Disabled mode.
bool ledGreen = false; // Tracks LED flashing from green/yellow for spawn limits.
bool trapSet = false; // Tracks Spawn Trap.
bool ownerLocked = true; // Tracks whether owner changes are locked out. AKA not owner 5 from GW.
bool announced = false; // Tracks whether mode has been announced yet.
byte currentMode = 1;  // Current Flag Mode.
byte modeStage = 0; // What stage the mode is running.
byte modeLEDVal = 0; // Stores the mode LED value for owner changes.
byte owner = 0; // what color owns the flag.
byte packedByte = 0;
long rndSleep = 0; // value to hold random sleep period.
unsigned long mp3PowerCut = 0; // Millis when we want to power down the MP3 module.
unsigned long mp3Ready = 0; // Millis when we want to send MP3 commands (power on delay).
unsigned long nextTimerCheck = 0; // Millis when we want to start some other action.
unsigned long nextUpdate = 0; // When to turn off this LED and move to the next one.
unsigned long nextBattCheck = 0; // When to check the battery next time 120s.
unsigned long btnHoldOff = 0; // makes button routines wait for debounce.
unsigned long lastGWMsg = 0; // Stores the last time we saw a message from the gateway. After some time, assume we lost contact.
unsigned long spawnDelay = 30000; // Stores amount of time to wait for next spawn. Default 30s
unsigned long spawnLimit = 0; // Stores milis when next spawn is allowed.
unsigned long btnStart = 0; // Tracks when button was first pressed.
FLAGSTAT flagStat; // Initialize the class for working with flag status.
WS2812 LED(10); // Set num of LEDs to 10

void setup() {
  Serial.begin(9600); // NOTE Serial TX is used to control MP3 also!
  randomSeed(analogRead(7));
  pinMode(BTN_PIN, INPUT_PULLUP); // Set Button Sense pin to float high.
  pinMode(VOLT_PIN, INPUT); // Voltage Sense pin
  pinMode(PS_PIN, OUTPUT); 
  digitalWrite(PS_PIN,0); // Turn on power - Its inverted.
  pinMode(MP3_PIN, OUTPUT); 
  digitalWrite(MP3_PIN,1); // Turn on power.
  // LED setup section.
  LED.setColorOrderGRB();
  LED.setOutput(LED_PIN);
  wait(1000);
  execute_CMD(0x12, 1, 14); // Play file 30-1E
  allLEDs(1, 1);
  wait(1000);
  allLEDs(4, 4);
  mp3PowerCut = millis() + 2000; // Set mp3 power cutoff to 2 secs.
  wait(300);
  allLEDs(0, 0);
  wait(20);
  request( 0, V_VAR1 ); // Pull the gateway's current flag mode - switch to this when node powers up.
  request( 0, V_VAR2 ); // Pull the gateway's current spawn delay - switch to this when node powers up.
}

void presentation() {
  present(0, S_CUSTOM); // Present Sensor 0 as a custom type. (status)
  present(1, S_CUSTOM); // Present Sensor 1 as a custom type. (spawn)
  sendSketchInfo(SN, SV);
}

// DF serial command code
void execute_CMD(byte CMD, byte Par1, byte Par2) { // Excecute the command and parameters
  // Calculate the checksum (2 bytes)
  int16_t checksum = -(Version_Byte + Command_Length + CMD + Acknowledge + Par1 + Par2);
  // Build the command line
  byte Command_line[10] = { Start_Byte, Version_Byte, Command_Length, CMD, Acknowledge, Par1, Par2, checksum >> 8, checksum & 0xFF, End_Byte};
  //Send the command line to the module
  for (byte k=0; k<10; k++)
  {
    Serial.write( Command_line[k]);
  }
}

void loop() {
  if (nextUpdate < millis()) {
    packedByte = flagStat.setButton(buttonStatus, packedByte);
    packedByte = flagStat.setMode(currentMode, packedByte);
    packedByte = flagStat.setOwner(owner, packedByte);
    send(statusMsg.set(packedByte) );
    nextUpdate = millis() + 30000;
  }
  if (millis() > mp3PowerCut) {
    digitalWrite(MP3_PIN,0); // Turn off power.
  }
  if (nextBattCheck < millis()) {
    double battVoltage = (analogRead(VOLT_PIN) / 32.8);
    int battPct = (((battVoltage - 11.9)) * 130);
    if (battPct > 110) battPct = 110;
    Serial.println("Bat:" + String(battVoltage) + " " + String(battPct) + "%");
    if (battVoltage < 12) { // we get false low bat sometimes. double check it after 10 ms.
      wait(10);
      battVoltage = (analogRead(VOLT_PIN) / 32.8);
      battPct = (((battVoltage - 11.9)) * 130);
      if (battPct > 110) battPct = 110;
      Serial.println("LowBatVerify:" + String(battVoltage) + " " + String(battPct) + "%");
    }
    sendBatteryLevel(battPct);
    if (battVoltage < 12) { // If batt is critical, sleep for 5 minutes. otherwise use normal 25-45 secs.
      Serial.println(F("LowBatSleep"));
      currentMode = 0;
      sleepLower = 300000;
      sleepUpper = 300010;
      send( statusMsg.set(packedByte) ); // Inform the gateway of the current flag Mode (sleep).
    } else {
      sleepLower = 20000; // This should be 20 to 30 seconds normally.
      sleepUpper = 30000;
    }
    nextBattCheck = millis() + 120000;
  }
  if ((millis() - lastGWMsg) > 1800000) {
    // No contact for 30 minutes. Reset to sleep mode.
    currentMode = 0;
    owner = 0;
    allLEDs(0, 0);
    wait(100);
    digitalWrite(PS_PIN,1); // Turn off power - Its inverted.
    digitalWrite(MP3_PIN,0); // Turn off power.
    Serial.println(F("GW Timeout, sleeping."));
  }
  if ((millis() - lastGWMsg) > 3600000) {
    // No contact for 1 hour. Reset to disabled mode.
    disabled = true;
    Serial.println(F("GW Timeout, disabled."));
  }
  if (disabled) {
    Serial.println(F("Disabled."));
    wait(100);
    digitalWrite(PS_PIN,1); // Turn off power - Its inverted.
    digitalWrite(MP3_PIN,0); // Turn off power.
    wait(15000); // Wait 15 secs to bleed any child messages off.
    sleep(BTN_PIN, FALLING, 120000); // But - a repeater flag wont truly sleep.. :(
    request( 0, V_VAR1 ); // Pull the gateway's current flag mode - switch to this when node powers up.
    request( 0, V_VAR2 ); // Pull the gateway's current spawn delay - switch to this when node powers up.
  }
  switch (currentMode) {
    case 0: // Sleep Mode. Wait for around a second. No LED but we still process radio msgs.
      nextUpdate += 270000;
      allLEDs(0, 0);
      wait(100);
      digitalWrite(PS_PIN,1); // Turn off power - Its inverted.
      digitalWrite(MP3_PIN,0); // Turn off power.
      rndSleep = random(sleepLower, sleepUpper);
      Serial.println("Sleep " + String(rndSleep));
      wait(rndSleep);
      request( 0, V_VAR1 ); // Pull the gateway's current flag mode - switch to this when node powers up.
      request( 0, V_VAR2 ); // Pull the gateway's current spawn delay - switch to this when node powers up.
      break;
    case 1: // Standby (pre game)
      if ((modeStage == 0) && (nextTimerCheck < millis()))  {
        Serial.println(F("StandBy"));
        allLEDs(5, owner);
        nextTimerCheck = millis() + 1000;
        modeStage ++;
      }
      if ((modeStage == 1) && (nextTimerCheck < millis()) ) {
        nextTimerCheck  = millis() + 1000;
        modeStage = 0;
        allLEDs(3, owner);
      }
      break;
    case 2: // Game On
      if ((!announced) && (mp3Ready < millis())) {
        execute_CMD(0x12, 0, 2); // Play file 2-02
        announced = true;
      }
      if ((nextTimerCheck < millis()) ) { // Game on is always green but we dont try forcing the color every pass. 
        Serial.println(F("GameOn"));
        allLEDs(4, owner);
        nextTimerCheck = millis() + 10000;
      }
      break;
    case 3: // Spawn Point
    case 4: // Spawn Trap
      if ((!announced) && (mp3Ready < millis())) {
        execute_CMD(0x12, 0, 2); // Play file 2-02
        announced = true;
      }
      if ((modeStage == 0) && (nextTimerCheck < millis())) { // show green wait for press.
//        Serial.println(String(millis()) + " ms:0");
        allLEDs(4, owner);
        nextTimerCheck = millis() + 150;
        if (digitalRead(BTN_PIN) == LOW) {
          if (trapSet && (currentMode = 4)) {
            modeStage = 3;
            nextTimerCheck = 0;
            spawnLimit = millis() + (spawnDelay * 3);
          } else {
            modeStage ++;
            btnStart = millis();
          }
        }
      }
      if ((modeStage == 1) && (nextTimerCheck < millis())) { // wait for release.
//        Serial.println(String(millis()) + " ms:1 BtnWait");
        nextTimerCheck = millis() + 150;
        if ((digitalRead(BTN_PIN) == HIGH) && ((millis() - btnStart) < 20000)) {
            // btn released before trap set. Goto stage 2
            modeStage ++;
            nextTimerCheck = 0;
            spawnLimit = millis() + spawnDelay;
//            Serial.println("SpawnLImit:" + String(spawnLimit));
        }
        if ( ((millis() - btnStart) > 20000) && (currentMode==4) ) {
            // btn held past trap set point.
            trapSet = true;
            modeStage = 4;
            nextTimerCheck = 0;
            spawnLimit = millis() + 5000;
          }
      }
      if ((modeStage == 2) && (nextTimerCheck < millis())) { // if < 20s flash yellow till end of timeout.
//        Serial.println(String(millis()) + " ms:2 Spawn waiting");
        nextTimerCheck = millis() + 150;
        if (ledGreen) {
          allLEDs(0,owner);
          ledGreen = false;
        } else {
          allLEDs(5,owner);
          ledGreen = true;
        }
        if (spawnLimit < millis()) {
          // delay met, allow spawn.
          Serial.println(F("Spawn Allowed."));
          allLEDs(4,owner);
          modeStage = 0;
          nextTimerCheck = millis() + 3000;
        }
      }
      if ((modeStage == 3) && (nextTimerCheck < millis())) { // trap set off
//        Serial.println(String(millis()) + " ms:3 Triggered");
        allLEDs(2,6);
        nextTimerCheck = millis() + 50;
        if (spawnLimit < millis()) {
          modeStage = 0;
          trapSet = false;
          nextTimerCheck = 0;
        }
      }
      if ((modeStage == 4) && (nextTimerCheck < millis())) { // trap Set Acknowledged
//        Serial.println(String(millis()) + " ms:4 Trap Ack");
        nextTimerCheck = millis() + 150;
        if (ledGreen) {
          allLEDs(4,owner);
          ledGreen = false;
        } else {
          allLEDs(5,owner);
          ledGreen = true;
        }
        if (spawnLimit < millis()) {
          modeStage = 0;
          nextTimerCheck = 0;
        }
      }
      break;
    case 5: //Two Minute Warning
      if ((!announced) && (mp3Ready < millis())) {
        execute_CMD(0x12, 0, 3); // Play file 3-03
        announced = true;
      }
      if (modeStage == 0) { // Mode 0 - Start of mode.
        Serial.println(F("2-Min"));
        allLEDs(1, owner);
        modeStage ++; // White 200ms flash.
        nextTimerCheck = millis() + 200;
      }
      if ( (modeStage == 1) && (nextTimerCheck < millis()) ) {
        allLEDs(0, owner);
        modeStage ++; // Off 100ms.
        nextTimerCheck = millis() + 100;
      }
      if ((modeStage == 2) && (nextTimerCheck < millis()) ) {
        allLEDs(1, owner);
        modeStage ++; // White 200ms flash.
        nextTimerCheck = millis() + 200;
      }
      if ( (modeStage == 3) && (nextTimerCheck < millis()) ) {
        allLEDs(0, owner); // Off 100ms.
        nextTimerCheck = millis() + 100;
        modeStage ++;
      }
      if ((modeStage == 4) && (nextTimerCheck < millis()) ) {
        allLEDs(4, owner); // Green 10s.
        nextTimerCheck = millis() + 7000;
        modeStage ++;
      }
      if ((modeStage == 5) && (nextTimerCheck < millis()) ) {
        allLEDs(4, owner); // Restart Flash Sequence.
        nextTimerCheck = millis();
        modeStage = 0;
      }
      break;
    case 6: // Game End
      if ((!announced) && (mp3Ready < millis())) {
        execute_CMD(0x12, 0, 4); // Play file 4-04
        announced = true;
      }
      if (modeStage == 0) { // Mode 0 - Start of mode.
        Serial.println(F("Game End"));
        allLEDs(2, 0);
        nextTimerCheck = millis() + 180000;
        modeStage = 1; // Mode 1 - waiting 3m.
      }
      if ((modeStage == 1) && (nextTimerCheck < millis())) { // Game End > 3m, turn off lights.
        nextTimerCheck  = 0;
        allLEDs(0, 0);
      }
      break;
    case 7: // Blind Man
      if ((!announced) && (mp3Ready < millis())) {
        execute_CMD(0x12, 0, 5); // Play file 5-05
        mp3Ready = millis() + 10000;
        mp3PowerCut = millis() + 20000;
        announced = false;
      }
      if ((modeStage == 0) && (nextTimerCheck < millis())) { // Mode 0 - Start of mode.
        Serial.println(F("Blind Man!"));
        allLEDs(2, 2);
        nextTimerCheck  = millis() + 200;
        modeStage ++; // Mode 1 - waiting .5 seconds.
      }
      if ((modeStage == 1) && (nextTimerCheck < millis())) {
        nextTimerCheck  = millis() + 200;
        modeStage = 0;
        allLEDs(5, 7);
      }
      break;
  }
  if (btnHoldOff < millis()) {
    if ((digitalRead(BTN_PIN) == LOW) && (!buttonStatus)) {
      buttonStatus = true;
      packedByte = flagStat.setButton(buttonStatus, packedByte);
      send(statusMsg.set(packedByte) );
      Serial.println(F("BDown"));
      btnHoldOff = millis() + 150;
      digitalWrite(MP3_PIN,1); // Turn on power in anticipation of announcing mode.
      mp3PowerCut = millis() + 10000; // set power off in 10 secs for MP3 mpodule.
      mp3Ready = millis() + 1000;
    }
    if ((digitalRead(BTN_PIN) == HIGH) && (buttonStatus)) {
      buttonStatus = false;
      packedByte = flagStat.setButton(buttonStatus, packedByte);
      Serial.println(F("BUp"));
      btnHoldOff = millis() + 150; // need to verify its not spawn trap/limit, if so dont increment.
      if ( (currentMode > 1 ) && (currentMode < 6) && (!ownerLocked) ) {
        owner++;
        if (owner > 3) owner = 0;
        execute_CMD(0x12, 0, owner+6); // Play file 6+owner
        Serial.println("Play "+String(owner+6));
      }
      allLEDs(modeLEDVal, owner);
      packedByte = flagStat.setOwner(owner, packedByte);
      send(statusMsg.set(packedByte));
    }
  }
}

