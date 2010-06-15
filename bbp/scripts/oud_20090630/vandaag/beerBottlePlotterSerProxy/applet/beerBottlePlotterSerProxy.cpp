#include <AFMotor.h>
#include <Servo.h>

#include "WProgram.h"
void setup();
void loop();
void checkSerial();
void doLEDS();
Servo servo1;
//Servo servo2;

AF_Stepper motor(48, 2);

int incomingByte = -1;
int ledPin = 13;                // LED connected to digital pin 13
int  val = 0;
char code[10];
int bytesread = 0;
int led1 = 1; // LED initial state is ON


void setup() {
  Serial.begin(9600);           // set up Serial library at 9600 bps
  pinMode(ledPin, OUTPUT);      // sets the digital pin as output
  digitalWrite(ledPin, HIGH);   // sets the LED on

  motor.setSpeed(150);  // 10 rpm   
  motor.release();

}

void loop() {
  checkSerial();
}
 
void checkSerial() {
  if(Serial.available() > 0) {          // if data available
    if((val = Serial.read()) == 10) {   // check for header start
      bytesread = 0;
      while(bytesread<1) {              // read 1 digit code
        if( Serial.available() > 0) {
          val = Serial.read();
          if(val == 13) { // check for header end
            break;                       // stop reading
          }
          code[bytesread] = val;         // add the digit
          bytesread++;                   // ready to read next digit
        }
      }
      if(bytesread == 1) {              // if 1 digit read is complete
        incomingByte = int(code[0]);
        doLEDS();
      }
      bytesread = 0;
      delay(50);                       // wait for a second
    }
  }


}

void doLEDS()
{

  if (incomingByte == 49) {
    digitalWrite(ledPin, HIGH);    // sets the LED on
    motor.step(50, FORWARD, SINGLE); 
    led1=1;
  }

  if (incomingByte == 48) {
    digitalWrite(ledPin, LOW);    // sets the LED off
    motor.step(50, BACKWARD, SINGLE); 
    led1=0;
  }

  if (incomingByte == 50) { // php is asking for the LED state
      Serial.println(led1);
  }

}

int main(void)
{
	init();

	setup();
    
	for (;;)
		loop();
        
	return 0;
}

