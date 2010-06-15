<?php
/**
 * this script will control an arduino, which controls 2 stepper motors and one servo
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
define('XFORWARD', 0);
define('YFORWARD', 0);
define('XBACKWARD', 1);
define('YBACKWARD', 1);

$beginMsg = chr(10); // start Transmission
$endMsg =chr(13);   // end Transmission

$serProxyIP = '127.0.0.1';
$serProxyPort = 5331;

$printFile = 'files/positions.txt';

//starting point
$xOldCor = 0;
$yOldCor = 0;
$zDir = 0;

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
    global $fp, $xOldCor, $yOldCor, $zDir, $beginMsg, $endMsg;

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
                $xDir = XFORWARD;
            }
            else{
                $xDir = XBACKWARD;
                $xDiff = -$xDiff;
            }
            
            $yDiff = $yCor - $yOldCor;
            if($yDiff > 0){
                $yDir = YFORWARD;
            }
            else{
                $yDir = YBACKWARD;
                $yDiff = -$yDiff;
            }
            if(sendSteps($xDir,$xDiff,$yDir,$yDiff,$zCmd)){
                $xOldCor = $xCor;
                $yOldCor = $yCor;
            }
            else{
                die('something is wrong');
            }
            if(!$zCmd){
                //we need a second command to put down the marker on the new spot
                //this comes after a new line
                if(sendSteps(0,0,0,0,1)){
                    $zCmd = 1;
                }
                else{
                    die('something is wrong');
                }
            }
        }
        elseif(strstr($line, 'new')){
            //put the marker up
            $zCmd = 0;
            sendSteps(0,0,0,0,0);
        }
        else{
            print "ignoring line $line<br />";
        }
    }
}

/**
 * function to send the steps
 * if the steps are too big they will be cut in parts to be sent
 * 
 * @param int $xDiff x parameter to be sent
 * @param int $yDiff y parameter to be sent
 * @param int $zCmd  z parameter to be sent
 * 
 * @return boolean
 */
function sendSteps($xDir, $xDiff, $yDir, $yDiff, $zCmd){
    
    global $fp, $beginMsg, $endMsg;
    
    $xCmd = $xDir.sprintf("%03d", $xDiff);
    $yCmd = $yDir.sprintf("%03d", $yDiff);
    
    $msg = $beginMsg.$xCmd.$yCmd.$zCmd.$endMsg;
    if(CONNECT){
        fputs($fp,$msg);
        $answer = getAnswer();
        if($answer == 'ok'){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        print "message $msg <br />";
        return true;
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
        //move up the marker
        //first command does nothing (this is because the arduino expects some char before doing something
        $msg = $beginMsg.'000000000'.$endMsg;
        fputs($fp,$msg);

        //move the marker really up
        $msg = $beginMsg.'000000000'.$endMsg;
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
        //the request is split into three parts
        //1. x actions
        //  three digits
        //      1. direction 0/1
        //      2. steps (will be multiplied by 10)
        //      3. steps
        //2. y actions
        //  see x actions
        //3. z actions
        //  0 up, 1 down
        //examples:
        // 000100000 move x one step forward (0001), y does not move (0000), marker up (0)
        // 000001230 don't move x (000), y moves 023 steps backward (0123) , marker up (0)
        // 101100221 x moves 11 steps backwards (0111), y moves 22 steps forward (0022), marker down (1)
        if ($_GET['action'] == 'left') {
            $midMsg = '000100000';
        }
        elseif ($_GET['action'] == 'right') {
            $midMsg = '100100000';
        }
        elseif($_GET['action'] == 'rotate_clockwise') {
            $midMsg = '000000010';
        }elseif($_GET['action'] == 'rotate_anticlockwise') {
            $midMsg = '000010010';
        }
        elseif($_GET['action'] == 'down') {
            $midMsg = '000000001';
        }
        elseif($_GET['action'] == 'up') {
            $midMsg = '000000000';
        }
        elseif($_GET['action'] == 'print') {
            if(DEBUG){
                print "calling printAll($printFile) <br />";
            }
            printAll($printFile);
            //pull up the marker at the end
            $midMsg = '0000000';
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
