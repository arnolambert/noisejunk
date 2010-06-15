#include <AFMotor.h>
#include <ServoTimer1.h>

ServoTimer1 marker;

AF_Stepper xMotor(48, 1);
AF_Stepper yMotor(48, 2);

int incomingByte = -1;
int  val = 0;
char code[10];
int bytesread = 0;

//we will read 9 bytes
//1. xDir direction of the x motor
//2-4 xSteps steps for the x motor
//5 yDir direction of the y motor
//6-8 ySteps steps for the y motor
//9 zDir status of the servo (0 is up, 1 is down)
int maxBytes = 9;
int xDir = 0;
int xSteps = 0;
int yDir = 0;
int ySteps = 0;
int zDir = 0;

//how many steps will the motor turn per requested step
int xResolution = 5;
int yResolution = 5;

void setup() {
  Serial.begin(9600);           // set up Serial library at 9600 bps

  xMotor.setSpeed(150);  // 150 rpm   
  xMotor.release();
  yMotor.setSpeed(150);  // 150 rpm   
  yMotor.release();
  
  marker.attach(9);

}

void loop() {
  checkSerial();
}

void checkSerial() {
  if(Serial.available() > 0) {          // if data available
    if((val = Serial.read()) == 10) {   // check for header start
      bytesread = 0;
      while(bytesread<maxBytes) {              // read as many bytes as we want
        if( Serial.available() > 0) {
          val = Serial.read();
          if(val == 13) { // check for header end
            break;                       // stop reading
          }
          code[bytesread] = val;         // add the byte
          bytesread++;                   // ready to read next byte
        }
      }
      if(bytesread == maxBytes) {              // if read is complete
        xDir = int(code[0]) - 48;
        if(xDir == 1){
          xDir = BACKWARD;
        }
        else{
          xDir = FORWARD;
        }
        xSteps = ((int(code[1]) - 48)*100 + (int(code[2]) - 48)*10 + (int(code[3]) - 48)*1)*xResolution;
        yDir = int(code[4]) - 48;
        if(yDir == 1){
          yDir = BACKWARD;
        }
        else{
          yDir = FORWARD;
        }
        ySteps = ((int(code[5]) - 48)*100 + (int(code[6]) - 48)*10 + (int(code[7]) - 48)*1)*yResolution;
        zDir = int(code[8]) - 48;
        if(zDir == 1){
          zDir = 60;
        }
        else{
          zDir = 0;
        }
        xMotor.step(xSteps, xDir, SINGLE);
        yMotor.step(ySteps, yDir, SINGLE);
        marker.write(zDir);
        Serial.print("ok");
        
      }
      bytesread = 0;
      delay(50);                       // wait for a second
    }
  }
}
