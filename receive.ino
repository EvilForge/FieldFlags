void receive(const MyMessage &message) {
//  Serial.println("Type:" + String(message.type));
//  Serial.println("Sender:" + String(message.sender));
//  Serial.println("Destination:" + String(message.destination));
//  Serial.println("Byte:" + String(message.getByte()));
//  Serial.println("Sensor:" + String(message.sensor));
//  Serial.println("mGetCmd:" + String(mGetCommand(message)));
  bool hasChanged = false;
  byte msgCMD = mGetCommand(message);
  byte flagMsg = message.getByte();
  byte flag = message.sender -1;
  String flagMessage = "Recvd Flag" + String(flag+1) + ": Data:" + String(flagMsg) + " Cmd:" + String(msgCMD) + " Own:" + flagStat.getOwner(flagMsg);
  if (msgCMD == 1) { // Node sent us an update. Its all data - owner, button and mode in a packed byte.
    // If server says a game is running (mode2,3,4,5)
      // and server says owner is 5 (dont care)
        // Add time to flag array owner time (time since last check-in), then set flag array
    if ((flagStat.getMode(servArray[flag])>=2) || (flagStat.getMode(servArray[flag])<=5)) { // if a game is running..
      Serial.println(" Game is running:" + String(flagStat.getMode(servArray[flag])));
      if (flagStat.getOwner(servArray[flag])==5) { // and server says track owners...
        unsigned long flagElapsed = (millis()/1000) - flagTimes[flag][0];
        byte flagOwner = flagStat.getOwner(flagArray[flag]);
        Serial.println("  Serv says track owner, Flag says Owner is:" + String(flagStat.getOwner(flagMsg)));
        Serial.println("  Last GW known Flag Owner:" + String(flagOwner));
        Serial.println("  Elapsed:" + String(flagElapsed));
        flagTimes[flag][0] = millis()/1000; // update this, finally, after we dont need the last time we talked to it.
        if (flagOwner!=0) {
          flagTimes[flag][flagOwner] = flagTimes[flag][flagOwner] + flagElapsed; // Add elapsed time since last seen to current owner elapsed.
        }
        if (flagOwner==1) {
          Serial.println("Adding GREEN time to flag" + String(flag + 1 ) + ": " + String(flagElapsed) );
          Serial.println(" Now accumulated: "+ String(flagTimes[flag][flagOwner]) );
        }
        if (flagOwner==2) {
          Serial.println("Adding TAN time to flag" + String(flag + 1 ) + ": " + String(flagElapsed) );
          Serial.println(" Now accumulated: "+ String(flagTimes[flag][flagOwner]) );
        }
        if (flagOwner==3) {
          Serial.println("Adding BLUE time to flag" + String(flag + 1 ) + ": " + String(flagElapsed) );
          Serial.println(" Now accumulated: "+ String(flagTimes[flag][flagOwner]) );
        }
      }
    }
    flagArray[flag] = flagMsg; // Update the flag array  
  }
  if (msgCMD == 2) { // Node is asking for data. Send it the server desired state.
    if (message.type == V_VAR1) {
      statusMsg.setDestination(flag+1);
      send(statusMsg.set(servArray[flag]));
      flagMessage += " QueryState";
    }
    if (message.type == V_VAR2) {
      spawnMsg.setDestination(flag+1);
      send(spawnMsg.set(spwnArray[flag]));
      flagMessage += " Sent SpDl: " + String(spwnArray[flag]);
    }
  }
  if (msgCMD == 3) { // Battery status message. Update local battArray, let periodic updates sync it.
    if (message.type==0) {
      flagMessage += " Pow: " + String(flagMsg) + "%";
      if ((battArray[flag] != flagMsg) && (flagMsg < 111)) {
        battArray[flag] = flagMsg;
      }
    }
  }
  Serial.println(flagMessage);
}


