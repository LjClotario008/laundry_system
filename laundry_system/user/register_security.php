<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $conn) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $password = $_POST['password'];

    // PHP Validation: Check if the username contains any numbers
    if (preg_match('/[0-9]/', $username)) {
        header("Location: register.php?error=no_numbers_allowed");
        exit();
    }

    // Check if user already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        header("Location: register.php?error=exists");
        exit();
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (fullname, username, password) VALUES ('$fullname', '$username', '$hashed')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?success=1");
        }
    }
}
?>