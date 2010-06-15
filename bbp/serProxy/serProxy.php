<?php
/**
 * this script will controll an arduino, which controlls 2 stepper motors and one servo
 * this is part of the BeerBottlePlotter project
 * The whole thing needs a serProxy to be running between the Arduino and the server
 * the script is based on a script by John Ryan
 *
 * @author Arno Lambert <arno.lambert@gmail.com>
 * @since 26/06/09
 *
 */

error_reporting(E_ALL);

/********************************SETTINGS*****************************************/
define('DEBUG',true);
define('DEBUGLEVEL', 1);
define('CONNECT', false);

$stepsPerCoordinate = 5;

$beginMsg = chr(10); // start Transmission
$endMsg =chr(13);   // end Transmission

$serProxyIP = '127.0.0.1';
$serProxyPort = 5331;

$printFile = 'files/positions.txt';

//starting point
$xOldCor = 0;
$yOldCor = 0;
$zDir = 2;

$answer = 'not ok';
$xAnswer = $answer;
$yAnswer = $answer;

/********************************FUNCTIONS*****************************************/

/**
 * get the answer from the serProxy server (Arduino)
 *
 * @return string $answer the answer
 */

function getAnswer(){
    global $fp;

    $ret=fgets($fp,3);
    $answer = $ret;
    if((int)$answer === 1){
        $answer = 'ok';
    }
    else{
        print "getting back $answer <br />";
        $answer = 'not ok';
    }
    return $answer;
}

/**
 * read the file with the coordinates, calculate the steps and send it to the arduino
 *
 * @param string $file the file with the coordinates
 *
 * @return bool
 */

function printAll($file){
    if(DEBUG){
        print 'entering printAll() <br />';
    }
    global $fp, $xOldCor, $yOldCor, $zDir, $beginMsg, $endMsg, $stepsPerCoordinate;

    //open the file
    $allLines = file($file);

    //read line by line
    foreach($allLines as $line){
        if(DEBUG){
            print "reading line $line<br />";
        }
        if(preg_match('/(\d+)\t(\d+)/',$line, $matches)){
            if(DEBUG && DEBUGLEVEL > 1){
                print "good line <br />";
                print_r($matches);
                print '<br />';
            }
            $xCor = $matches[1];
            $yCor = $matches[2];

            //calculate the steps
            $xDiff = $xCor - $xOldCor;
            if($xDiff > 0){
                $xDir = 1;
            }
            else{
                $xDir = 2;
                $xDiff = -$xDiff;
            }
            $xDiff *= $stepsPerCoordinate;
            
            $yDiff = $yCor - $yOldCor;
            if($yDiff > 0){
                $yDir = 1;
            }
            else{
                $yDir = 2;
                $yDiff = -$yDiff;
            }
            $yDiff *= $stepsPerCoordinate;
            
            $xCmd = 'x'.$xDir.sprintf("%03d", $xDiff);
            $yCmd = 'y'.$yDir.sprintf("%03d", $yDiff);
            //send to the arduino
            if(CONNECT){
                $msg = $beginMsg.$xCmd.$endMsg;
                fputs($fp,$msg);
                $xAnswer = getAnswer();
                $msg = $beginMsg.$yCmd.$endMsg;
                fputs($fp,$msg);
                $yAnswer = getAnswer();
                if($xAnswer == 'ok' && $yAnswer == 'ok'){
                    $xOldCor = $xCor;
                    $yOldCor = $yCor;
                }
                else{
                    die('something is wrong');
                }
            }
            else{
                $xOldCor = $xCor;
                $yOldCor = $yCor;
                print "message $xCmd - $yCmd <br />";
            }
            if($zDir == 2){
                //put down the marker
                $zDir = 1;
                $zCmd = 'z'.$zDir.'000';
                $msg = $beginMsg.'x2001'.$endMsg;
                if(CONNECT){
                    fputs($fp,$msg);
                    $answer = getAnswer();
                }
                else{
                    print "message $msg <br />";
                }

            }
        }
        elseif($line == 'new line'){
            $zDir = 2;
            $zCmd = 'z'.$zDir.'000';
            $msg = $beginMsg.'x2001'.$endMsg;
            if(CONNECT){
                
                fputs($fp,$msg);
                $answer = getAnswer();
            }
            else{
                print "message $msg <br />";
            }

        }
        else{
            print "ignoring line $line<br />";
        }
    }
}
?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' ' content='text/html; charset=iso-8859-1' />
<title>Control the BeerBottlePlotter with php</title>
<style type='text/css'>
* {
    padding: 0;
    margin: 0;
}

html,body {
    margin: 0;
    padding: 0
}

.ledon {
    display: block;
    position: absolute;
    top: 50px;
    left: 50px;
    width: 79px;
    height: 31px;
    background: url(images/rocker_79x62.gif) no-repeat 0px 0px;
}

.ledoff {
    display: block;
    position: absolute;
    top: 50px;
    left: 50px;
    width: 79px;
    height: 31px;
    background: url(images/rocker_79x62.gif) no-repeat 0px -31px;
}

.button {
    border-style: none;
}
</style>
</head>
<body>
<table width="300px" align="center">
    <tr>
        <td></td>
        <td><a class="button" href="<?=$_SERVER['PHP_SELF']?>?action=rotate_anticlockwise"><img class="button" alt="rotate anticlockwise" src="images/arrow_rotate_anticlockwise.png" /></a></td>
        <td></td>
    </tr>
    <tr>
        <td><a class="button" href="<?=$_SERVER['PHP_SELF']?>?action=left"><img class="button" alt="left" src="images/arrow_left.png" /></a></td>
        <td><a class="button" href="<?=$_SERVER['PHP_SELF']?>?action=print"><img class="button" alt="left" src="images/control_play.png" /></a></td>
        <td><a class="button" href="<?=$_SERVER['PHP_SELF']?>?action=right"><img class="button" alt="right" src="images/arrow_right.png" /></a></td>
    </tr>
    <tr>
        <td></td>
        <td><a class="button" href="<?=$_SERVER['PHP_SELF']?>?action=rotate_clockwise"><img class="button" alt="rotate clockwise" src="images/arrow_rotate_clockwise.png" /></a></td>
        <td></td>
    </tr>
<?php 
if(CONNECT){
    // open client connection to TCP server (serProxy)
    if(!$fp=fsockopen($serProxyIP,$serProxyPort,$errstr,$errno,30)){
        trigger_error('Error opening socket',E_USER_ERROR);
    }

    // write message to socket server
    //move one step forward and wait for the answer

    $msg = $beginMsg.'x1001'.$endMsg;
    fputs($fp,$msg);
    // get server response
    $ret=fgets($fp,1);
    $answer = $ret;
    $answer = (int)$answer;

    //move one step backwards and wait for the answer
    $msg = $beginMsg.'x2001'.$endMsg;
    fputs($fp,$msg);
    $answer = getAnswer();
}
else{
    print 'not connecting <br />';
}

//check if we need to do something or not
if (isset($_GET['action'])) {

    //Action required
    if(DEBUG){
        print 'action = '.$_GET['action'].'<br />';
    }
    //Do the request
    //format of the request:
    // first char x,y,z (for the different axises)
    // second char 1,2 (1 is forward, 2 is backward)
    // rest steps to make
    if ($_GET['action'] == 'left') {
        $midMsg = 'x1001';
    }
    elseif ($_GET['action'] == 'right') {
        $midMsg = 'x2001';
    }
    elseif($_GET['action'] == 'rotate_clockwise') {
        $midMsg = 'y1001';
    }elseif($_GET['action'] == 'rotate_anticlockwise') {
        $midMsg = 'y2001';
    }
    elseif($_GET['action'] == 'down') {
        $midMsg = 'z1000';
    }
    elseif($_GET['action'] == 'down') {
        $midMsg = 'z2000';
    }
    elseif($_GET['action'] == 'print') {
        if(DEBUG){
            print "calling printAll($printFile) <br />";
        }
        printAll($printFile);
    }
$msg = $beginMsg.$midMsg.$endMsg;
    if(CONNECT){
        // send the Message
        fputs($fp,$msg);
        $answer = getAnswer();
    }
    else{
        print "message $msg <br />";
    }
}

if(CONNECT){
    //We're done, so close socket connection
    fclose($fp);
}
?>
</body>
</html>