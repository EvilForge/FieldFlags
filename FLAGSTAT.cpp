#include "FLAGSTAT.h"

//<<constructor>> setup the flag. nothing to do really.
FLAGSTAT::FLAGSTAT(){/*nothing to construct*/}

//<<destructor>>
FLAGSTAT::~FLAGSTAT(){/*nothing to destruct*/}

byte FLAGSTAT::enable(byte sentByte){
	bitSet(sentByte,0);
	return sentByte;
}

byte FLAGSTAT::disable(byte sentByte){
	bitClear(sentByte,0);
	return sentByte;
}

bool FLAGSTAT::enabled(byte sentByte){
	if (bitRead(sentByte,0)==1) {
		return true;
	} else {
		return false;
	}
}

byte FLAGSTAT::getMode(byte sentByte){
	byte tmpByte = (sentByte >> 1)&7;
	return tmpByte;
}

byte FLAGSTAT::setMode(byte sentMode, byte sentByte){
	byte tmpByte = (sentMode&7) << 1; // Mask off just the first 3 bits, then shift left 1 place putting our mode into bits 123.
	sentByte &= 241; // clear out the 123 bits in existing status byte.
    sentByte |= tmpByte; // now OR bits from existing status and temp to update status.
	return sentByte;
}

byte FLAGSTAT::getOwner(byte sentByte){
	byte tmpByte = (sentByte >> 4)&7;
	return tmpByte;
}

byte FLAGSTAT::setOwner(byte sentOwner, byte sentByte){
	byte tmpByte = (sentOwner&7) << 4; // Mask off just the first 3 bits, then shift left 4 putting our mode into bits 456.
	sentByte &= 143; // clear out the 456 bits in existing status byte.
    sentByte |= tmpByte; // now OR bits from existing status and temp to update status.
	return sentByte;
}

bool FLAGSTAT::getButton(byte sentByte){
	if (bitRead(sentByte,7)==1) {
		return true;
	} else {
		return false;
	}
}

byte FLAGSTAT::setButton(bool sentState, byte sentByte){
	if (sentState) {
		bitSet(sentByte,7);
	} else {
		bitClear(sentByte,7);
	}
	return sentByte;
}

String FLAGSTAT::getModeS(byte sentByte) {
	byte tmpByte = (sentByte >> 1)&7;
	switch(tmpByte) {
		case 0:
		return "Sleeping";
		break;
		case 1:
		return "Standby";
		break;
		case 2:
		return "Game On";
		break;
		case 3:
		return "Two Minutes";
		break;
		case 4:
		return "Game End";
		break;
		case 5:
		return "Blind Man";
		break;
	}
}

String FLAGSTAT::getOwnerS(byte sentByte) {
	byte tmpByte = (sentByte >> 4)&7;
	switch (tmpByte) {
		case 0:
		return "No owner";
		break;
		case 1:
		return "Green";
		break;
		case 2:
		return "Tan";
		break;
		case 3:
		return "Blue";
		break;
  }
}
