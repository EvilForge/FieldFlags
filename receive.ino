void receive(const MyMessage &message) {
  lastGWMsg = millis(); // Reset the watchdog timer.
  String flagMessage;
  if (message.type == V_VAR1) {
    packedByte = message.getByte();    //  Retrieve the flag mode from the incoming request message, then set local vars to match.
    if (currentMode != flagStat.getMode(packedByte)) { // If we are in a different mode, clear timers.
      flagMessage += "Mode change from " + String(currentMode) + " to " + String(flagStat.getMode(packedByte)) +".";
      currentMode = flagStat.getMode(packedByte);
      nextTimerCheck = 0;
      modeStage = 0;
      if (currentMode > 1) {
        Serial.println("MP3 ON");
        digitalWrite(MP3_PIN,1); // Turn on power in anticipation of announcing mode.
        mp3PowerCut = millis() + 10000; // set power off in 10 secs for MP3 mpodule.
        announced = false;
        mp3Ready = millis() + 1000;
      }
      if ((currentMode==3) || (currentMode==4)) { // if its a spawn delay game, ask for limit.
        request( 0, V_VAR2 ); // Pull the gateway's current spawn delay
      }
    }
    byte sentOwner = flagStat.getOwner(packedByte);
    if (flagStat.enabled(packedByte)) {
      disabled = false;
    } else {
      disabled = true; // We are disabled, so clear out the variables and set currentMode out of bounds (10 works).
      currentMode = 10;
      owner = 0;
      allLEDs(0,0);
    }
    if (sentOwner < 4) {
      // Already handled 4,5, so set send owner..
      owner = sentOwner;
      ownerLocked = true;
    } else {
      ownerLocked = false;
    }
    if (sentOwner == 4) {
      // reset owner locally since reset was sent
      owner = 0;
      packedByte = flagStat.setOwner(0,packedByte);
    }
    packedByte = flagStat.setOwner(owner,packedByte);
    if (currentMode == 0) {
      allLEDs(0,0);
    } else {
      allLEDs(modeLEDVal,owner);
    }
    send(statusMsg.set(packedByte)); // Inform the gateway of the current status.
    flagMessage +=" Sent Own:" + String(owner) + " Mode:" + String(currentMode) + " GWOwn:" + flagStat.getOwner(packedByte) + " En:" + flagStat.enabled(packedByte);
    nextUpdate = millis() + 30000; // bump the next update by 30s.
    }
   if (message.type == V_VAR2) { // set spawn delay millis
    spawnDelay = (message.getByte()) * 1000;
    flagMessage += "SpwnDl:" + String(spawnDelay);
   }
   Serial.println(flagMessage);
}

