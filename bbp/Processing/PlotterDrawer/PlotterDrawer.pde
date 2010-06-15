/**
 * PlotterDrawer
 *
 * Make a file with coordinates to be used by Plotter2Arduino
 * Use a background image to guide you 
 *
 * @author Arno Lambert <arno.lambert@gmail.com>
 * @since 20/06/09
 *
 */

PrintWriter output;
PImage a;  // Declare variable "a" of type PImage
int oldMouseX = 0;
int oldMouseY = 0;
boolean markerDown = false;
boolean oldMarkerDown = false;

void setup() {
  size(800, 200);
  // Create a new file in the sketch directory
  output = createWriter("positions.txt");

  //background image must be the same size as the program!!!!
  a = loadImage("input2.png");  // Load the image into the program 
  background(a); 
  frameRate(100);
  output.println("new line");
}

void draw() 
{ 
  int currentMouseX = mouseX;
  int currentMouseY = mouseY;
  if (mousePressed && (currentMouseX != oldMouseX || currentMouseY != oldMouseY)) {
    ellipse(currentMouseX, currentMouseY,5,5);
    output.println(currentMouseX+"\t"+currentMouseY);
    oldMouseX = currentMouseX;
    oldMouseY = currentMouseY;
  }
}

void keyPressed() { 
  // Press a key to save the data or to start a new line
  if(key == 'n' || key == 'N'){
    output.println("new line");
  }
  else{
    output.flush(); // Write the remaining data
    output.close(); // Finish the file
    exit(); // Stop the program
  }
}

