<?php
// Database credentials
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '12345678';
$db_name = 'user_data';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
