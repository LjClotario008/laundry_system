<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Fresh Wave Laundry</title>
    <link rel="stylesheet" href="register.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="auth_container">
    <i class="fas fa-user-plus" style="font-size: 50px; color: #4da3ff; margin-bottom: 20px;"></i>
    <h1>Create Account</h1>
    <p>Join Fresh Wave Laundry today</p>

    <form action="register_process.php" method="POST">
    <div class="form_group">
        <label>Full Name</label>
        <div class="input_wrapper">
            <input type="text" name="fullname" placeholder="Enter full name" 
                   pattern="[A-Za-z\s\.,]+" title="Letters and punctuation only." required>
        </div>
    </div>

    <div class="form_group">
        <label>Username</label>
        <div class="input_wrapper">
            <input type="text" name="username" placeholder="Choose username" 
                   pattern="[A-Za-z\s\.,]+" title="Letters, dots, and commas only. No numbers." required>
        </div>
          </div>

          <div class="form_group">
          <label>Password</label>
          <div class="input_wrapper">
              <input type="password" name="password" placeholder="6-12 chars, 1 Upper, 1 Special" 
                    pattern="(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,12}" 
                    title="Password must be 6-12 characters long, include at least one uppercase letter and one special character (!@#$%^&*)." 
                    required>
          </div>
      </div>

          <button type="submit" class="auth_btn">SIGN UP</button>
      </form>
          
          <div class="footer_links" style="margin-top: 20px;">
              <p>Already a member? <a href="login.php" style="color: #4da3ff; text-decoration: none;">Sign In</a></p>
              <p style="margin-top: 10px;"><a href="homepage.php" style="color: #4da3ff; text-decoration: none;">← Back to Home</a></p>
          </div>
      </div>

</body>
</html>