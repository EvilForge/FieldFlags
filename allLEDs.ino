void allLEDs(byte modeLED, byte ownerLED) { //0=off, 1=white, 2=Red, 3=Blue, 4=Green, 5=Yellow, 6=Random
  if ((modeLED != 0 ) || (ownerLED != 0)) {
    digitalWrite(PS_PIN,0); // Turn on power - Its inverted.
  }
  cRGB mvalue;
  cRGB ovalue;
  modeLEDVal = modeLED;
  mvalue.r = 0;
  mvalue.g = 0;
  mvalue.b = 0;
  ovalue.r = 0;
  ovalue.g = 0;
  ovalue.b = 0;
  // We set all LEDs to one color a lot, so write a function to do that.
  // owner follow normal 0,1,2,3 clear green tan blue, 4,5 no color, 6 random, 7 red.
  switch (modeLED) {
    case 1:
      mvalue.r = 255;
      mvalue.g = 255;
      mvalue.b = 255;
    break;
    case 2:
      mvalue.r = 255;
    break;
    case 3:
      mvalue.b = 255;
    break;
    case 4:
      mvalue.g = 255;
    break;
    case 5:
      mvalue.r = 255;
      mvalue.g = 255;
    break;
    case 6:
      mvalue.r = random(0,255);
      mvalue.g = random(0,255);
      mvalue.b = random(0,255);
    break;
  }
  switch (ownerLED) {
    case 1:
      ovalue.g = 255;
    break;
    case 2:
      ovalue.r = 255;
      ovalue.g = 100;
    break;
    case 3:
      ovalue.b = 255;
    break;
    case 6:
      ovalue.r = random(0,255);
      ovalue.g = random(0,255);
      ovalue.b = random(0,255);
    break;
    case 7:
      ovalue.r = 255;
    break;
  }
  LED.set_crgb_at(0, ovalue); // Set value at LED found at index 0
  LED.set_crgb_at(1, ovalue); // Set value at LED found at index 1
  LED.set_crgb_at(2, ovalue); // Set value at LED found at index 2
  LED.set_crgb_at(3, ovalue); // Set value at LED found at index 3
  LED.set_crgb_at(4, ovalue); // Set value at LED found at index 4
  LED.set_crgb_at(5, mvalue); // Set value at LED found at index 5
  LED.set_crgb_at(6, mvalue); // Set value at LED found at index 6
  LED.set_crgb_at(7, mvalue); // Set value at LED found at index 7
  LED.set_crgb_at(8, mvalue); // Set value at LED found at index 8
  LED.set_crgb_at(9, mvalue); // Set value at LED found at index 9
  LED.sync(); // Sends the data to the LEDs
  delay(2);
}

