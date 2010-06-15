/**
* Plotter2Arduino
*
* read a file made by PlotterDrawer and send the coordinates to the Arduino
*
* @author Arno Lambert <arno.lambert@gmail.com>
* @since 20/06/09
*
 */

String[] lines;
int index = 0;
import processing.serial.*;
Serial myPort;      // The serial port
String inByte = "";    // Incoming serial data

void setup() {
  size(400, 400);
  background(0);
  stroke(255);
  frameRate(100);
  lines = loadStrings("positions.txt");
  
   PFont myFont = createFont(PFont.list()[2], 14);
  textFont(myFont);

  // List all the available serial ports:
  println(Serial.list());

  // I know that the first port in the serial list on my mac
  // is always my  FTDI adaptor, so I open Serial.list()[0].
  // In Windows, this usually opens COM1.
  // Open whatever port is the one you're using.
  String portName = Serial.list()[0];
  myPort = new Serial(this, portName, 9600);
}

void draw() {
  if (index < lines.length) {
     myPort.write(lines[index]);
    //String[] pieces = split(lines[index], '\t');
    //if (pieces.length == 2) {
      //int x = int(pieces[0]);
      //int y = int(pieces[1]);
      //point(x, y);
    //}
    // Go to the next line for the next run through draw()
     background(0);
  text("Last Received: " + inByte, 10, 130);
  text("Last Sent: " + lines[index], 10, 100);
    index = index + 1;
  } 
}

void serialEvent(Serial myPort) {
  inByte = myPort.read();
}

