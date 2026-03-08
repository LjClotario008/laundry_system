<?php
// Configuration for your database connection
$host = "localhost";
$user = "root";     // Default for XAMPP
$pass = "";         // Default for XAMPP
$dbname = "laundry_system";

// The connection "bridge"
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Error handling if the database isn't ready yet
if (!$conn) {
    // This allows your other scripts to check if the connection exists
    $conn = false; 
}
?>