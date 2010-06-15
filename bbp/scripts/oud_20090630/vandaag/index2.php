<?php
error_reporting(E_ALL);

echo "<h2>TCP/IP Connection</h2>\n";

/* Get the port for the WWW service. */
$service_port = 5331;

/* Get the IP address for the target host. */
$address = '127.0.0.1';

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

echo "sent 1<br />";
socket_write($socket, 1, 1);

echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}
sleep(5);

echo "sent 0<br />";
socket_write($socket, 0, 1);

echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}
sleep(5);

echo "sent 2<br />";
socket_write($socket, 2, 1);

echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}
sleep(5);

echo "Closing socket...";
socket_close($socket);
echo "OK.\n\n";
?>

