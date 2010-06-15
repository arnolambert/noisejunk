/*
 * Toggle LED via php
 */

int incomingByte = -1;
int ledPin = 13;                // LED connected to digital pin 13
int  val = 0; 
char code[10]; 
int bytesread = 0;
int led1 = 1; // LED initial state is ON

void setup()                    // run once, when the sketch starts
{
  pinMode(ledPin, OUTPUT);      // sets the digital pin as output
  Serial.begin(9600);
  digitalWrite(ledPin, HIGH);   // sets the LED on
}

void loop()        {             // run over and over again
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
    digitalWrite(ledPin, HIGH);    // sets the LED off
    led1=1;
  }

  if (incomingByte == 48) {
    digitalWrite(ledPin, LOW);    // sets the LED off
    led1=0;
  }
  
  if (incomingByte == 50) { // php is asking for the LED state
      Serial.println(led1);
  }
  
}
