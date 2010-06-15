#include <AFMotor.h>
#include <Servo.h>

#include "WProgram.h"
void setup();
void loop();
Servo servo1;
//Servo servo2;

AF_Stepper motor(48, 2);

void setup() {
  Serial.begin(9600);           // set up Serial library at 9600 bps
  Serial.println("Stepper test!");

  motor.setSpeed(150);  // 10 rpm   

  //motor.step(100, FORWARD, SINGLE); 
  motor.release();
  //delay(1000);
   servo1.attach(10);
 // servo2.attach(9);
}

void loop() {
  
Serial.print("tick");
  servo1.write(90);
 // servo2.write(0);
  delay(5000);

  Serial.print("tock");
  servo1.write(50);
  //servo2.write(180);
  delay(5000);
  //motor.step(10, FORWARD, SINGLE); 
  motor.step(5, FORWARD, SINGLE); 
  delay(500);
  Serial.println("back");
  //motor.step(10, BACKWARD, SINGLE); 
  //motor.step(5, BACKWARD, SINGLE); 
  //delay(100);
  Serial.println("next");

  //motor.step(100, FORWARD, DOUBLE); 
  //motor.step(100, BACKWARD, DOUBLE);

  //motor.step(100, FORWARD, INTERLEAVE); 
  //motor.step(100, BACKWARD, INTERLEAVE); 

  //motor.step(100, FORWARD, MICROSTEP); 
  //motor.step(100, BACKWARD, MICROSTEP); 
}

int main(void)
{
	init();

	setup();
    
	for (;;)
		loop();
        
	return 0;
}

