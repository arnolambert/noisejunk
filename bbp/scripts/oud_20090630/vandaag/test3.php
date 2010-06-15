<?php
/* Script for turning LED on and off over www using sockets */

/* Author John Ryan */

/* September 2008 */


// Ask Arduino if the LED is ON or OFF

// open client connection to TCP server
if(!$fp=fsockopen('127.0.0.1',5332,$errstr,$errno,30)){
    trigger_error('Error opening socket',E_USER_ERROR);
}
passthru('whoami');

for ($t = 1; $t < 10; $t ++){

    $msg = chr(10); // start Transmission
    $msg .= "1"; // turn LED ON
    $msg .=chr(13); // end Transmission
print "on\n";
    fputs($fp,$msg);  // send the Message
	sleep(3);
    $msg = chr(10); // start Transmission
    $msg .= "0"; // turn LED OFF
    $msg .=chr(13); // end Transmission
print "off\n";
    fputs($fp,$msg);  // send the Message
	sleep(3);
}
fclose($fp);
?>
