#ifndef FLAGSTAT_H
#define FLAGSTAT_H

#include <Arduino.h>

class FLAGSTAT{
public:
	FLAGSTAT();
	~FLAGSTAT();
	byte enable(byte sentByte);
	byte disable(byte sentByte);
	bool enabled(byte sentByte);
	byte getMode(byte sentByte);
	byte setMode(byte sentMode,byte sentByte);
	byte getOwner(byte sentByte);
	byte setOwner(byte sentOwner,byte sentByte);
	bool getButton(byte sentByte);
	byte setButton(bool sentState, byte sentByte);
	String getModeS(byte sentByte);
	String getOwnerS(byte sentByte);
};

#endif