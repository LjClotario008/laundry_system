<?php
session_start(); // Starts the session so the user stays logged in
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $conn) {
    // 1. Clean the input to prevent SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // 2. Look for the user in the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // 3. VERIFY THE HASHED PASSWORD
        // This function compares the plain text password with the $2y$10... hash
        if (password_verify($password, $user['password'])) {
            
            // Success! Create session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['username'] = $user['username'];

            // Redirect to homepage
            header("Location: homepage.php");
            exit();
        } else {
            // Wrong password
            header("Location: login.php?error=invalid");
            exit();
        }
    } else {
        // Username doesn't exist
        header("Location: login.php?error=no_account");
        exit();
    }
} else {
    // Database connection is missing
    header("Location: login.php?error=db_not_ready");
    exit();
}
?>