/**
 * Words. 
 * 
 * The text() function is used for writing words to the screen. 
 */


int x = 30;
String output = "";
String out;
PFont fontA;
int test = 555;
int orgTest = test;
  
void setup() 
{
  size(400, 200);
  background(102);

  // Load the font. Fonts must be placed within the data 
  // directory of your sketch. Use Tools > Create Font 
  // to create a distributable bitmap font. 
  // For vector fonts, use the createFont() function. 
  fontA = loadFont("Ziggurat-HTF-Black-32.vlw");

  // Set the font and its size (in units of pixels)
  textFont(fontA, 16);
  frameRate(100);
  noLoop();
}

void draw() {
  // Use fill() to change the value or color of the text
  for(int t = 256;t > 1;t = t/2){
    if(test >= t){
      out = "1";
      test = test - t;
    }
    else{
      out = "0";
    }
          output  = output + out;
  }
  if(test == 1){
    out = "1";
  }
  else {
    out = "0";
  }
 output  = output + out;
  fill(0);
  text(test, 30, 30);
  text(output, 60,30);
}
