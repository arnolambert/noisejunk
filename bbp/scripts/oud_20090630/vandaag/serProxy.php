<?php
/* Script for turning LED on and off over www using sockets */

/* Author John Ryan */

/* September 2008 */


// Ask Arduino if the LED is ON or OFF

// open client connection to TCP server
if(!$fp=fsockopen('127.0.0.1',5332,$errstr,$errno,30)){
    trigger_error('Error opening socket',E_USER_ERROR);
}
//sleep(2);

if (isset($_GET['action'])) {

    //Action required

    $msg = chr(10); // start Transmission

    //Do the request
    if ($_GET['action'] == "on") {
        $msg .= "1"; // turn LED ON
    } else if ($_GET['action'] == "off") {
        $msg .= "0"; // turn LED OFF
    }

    $msg .=chr(13); // end Transmission
    fputs($fp,$msg);  // send the Message
}
print 'herer 1<br />';

// write message to socket server
$msg = chr(10);
$msg .="2";
$msg .=chr(13);
fputs($fp,$msg);
// get server response


print 'hree 2<br />';

$ret=fgets($fp,1);
print "ret $ret <br />";
$led1 = $ret;
$led1 = (int)$led1;

$msg = chr(10);
$msg .="2";
$msg .=chr(13);
fputs($fp,$msg);
print 'hree 3<br />';

// get server response
$ret=fgets($fp,2);
print "ret $ret <br />";
print 'hree 4.1<br />';
$led1 = $ret;
print 'hree 4.2<br />';
$led1 = (int)$led1;
print 'hree 4.3<br />';

//We're done, so close socket connection
fclose($fp);


// Add some xhtml and CSS to display an ON/OFF button

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>";
echo "<html>";
echo "<head>";
echo "<meta http-equiv='Content-Type'' content='text/html; charset=iso-8859-1' />";
echo "<title>Toggle LED on Arduino</title>";
echo "<style type='text/css'>";
echo "* {padding:0;margin:0;}";
echo "html,body{margin:0;padding:0}";
echo ".ledon {";
echo "	display: block;";
echo "	position: absolute;";
echo "	top: 50px;";
echo "	left: 50px;";
echo "	width: 79px;";
echo "	height: 31px;";
echo "	background: url(images/rocker_79x62.gif) no-repeat 0px 0px;";
echo "}";
echo ".ledoff {";
echo "	display: block;";
echo "	position: absolute;";
echo "	top: 50px;";
echo "	left: 50px;";
echo "	width: 79px;";
echo "	height: 31px;";
echo "	background: url(images/rocker_79x62.gif) no-repeat 0px -31px;";
echo "}";
echo " ";
echo "</style>";
echo "</head>";
echo "<body>";

// if LED is OFF (0), then display the ON button
if ($led1 === 0) {
    echo "<p><a class='ledon' title='Click to turn OFF' href='$_SERVER[PHP_SELF]?action=on'></a></p>";
}

// if LED is ON (1), then display the OFF button
if ($led1 === 1) {
    echo "<p><a class='ledoff' title='Click to turn ON' href='$_SERVER[PHP_SELF]?action=off'></a></p>";
}
echo "<p>$led1</p>";
echo "</body>";
echo "</html>";


?>