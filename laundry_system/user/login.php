<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Fresh Wave Laundry</title>
    <link rel="stylesheet" href="login.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth_container">
        <i class="fas fa-user-circle" style="font-size: 50px; color: #4da3ff; margin-bottom: 20px;"></i>
        <h1>Welcome Back</h1>
        <p>Sign in to your account</p>

        <form action="login_security.php" method="POST">
            <div class="form_group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" 
                       pattern="[A-Za-z\s]+" title="Username should only contain letters." required>
            </div>

            <div class="form_group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" class="auth_btn">SIGN IN</button>
        </form>

        <div class="footer_links">
            <p>Don't have an account? <a href="register.php">Sign Up</a></p>
            <p style="margin-top: 10px;"><a href="homepage.php">← Back to Home</a></p>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error') === 'invalid') {
            alert("Invalid credentials. Please try again.");
        } else if (urlParams.get('error') === 'no_account') {
            alert(" Account does not exist. Please sign up first.");
        }
    </script>
</body>
</html>