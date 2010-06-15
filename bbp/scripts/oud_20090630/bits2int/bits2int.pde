

int outputPin = 13;
int val = 0;
int finalVal = 0;
void setup()
{
  Serial.begin(9600);
  pinMode(outputPin, OUTPUT);
}

void loop()
{
  if (Serial.available()) {
    while(val != 'H'){
    val = Serial.read();
    }
    for(int t=9;t>=0;t --){
      finalVal += val*(2^t);
    }
  }
}

/* Processing code for this example

// mouseover serial 
// by BARRAGAN <http://people.interaction-ivrea.it/h.barragan> 
 
// Demonstrates how to send data to the Arduino I/O board, in order to 
// turn ON a light if the mouse is over a rectangle and turn it off 
// if the mouse is not. 
 
// created 13 May 2004 
 
import processing.serial.*; 
 
Serial port; 
 
void setup() 
{ 
  size(200, 200); 
  noStroke(); 
  frameRate(10); 
 
  // List all the available serial ports in the output pane. 
  // You will need to choose the port that the Arduino board is 
  // connected to from this list. The first port in the list is 
  // port #0 and the third port in the list is port #2. 
  println(Serial.list()); 
 
  // Open the port that the Arduino board is connected to (in this case #0) 
  // Make sure to open the port at the same speed Arduino is using (9600bps) 
  port = new Serial(this, Serial.list()[0], 9600); 
} 
 
// function to test if mouse is over square 
boolean mouseOverRect() 
{ 
  return ((mouseX >= 50)&&(mouseX <= 150)&&(mouseY >= 50)&(mouseY <= 150)); 
} 
 
void draw() 
{ 
  background(#222222); 
  if(mouseOverRect())      // if mouse is over square 
  { 
    fill(#BBBBB0);         // change color 
    port.write('H');       // send an 'H' to indicate mouse is over square 
  } else { 
    fill(#666660);         // change color 
    port.write('L');       // send an 'L' otherwise 
  } 
  rect(50, 50, 100, 100);  // draw square 
} 
*/
