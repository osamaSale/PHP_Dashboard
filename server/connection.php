<?php

$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "crud";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    //echo "Connected successfully";
}

?>