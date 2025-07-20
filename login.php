<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Login Page</title>
</head>
<body>
    <div class="login-container">
        <!-- Image above the login form -->
        <img src="../resourses/img/pccoelogo.png" alt="Login Image" class="login-img">
        
        <div class="loginform">
            <h2>Faculty Login</h2>
            
            <!-- Your form content here -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                <button type="submit" class="btn" id="loginBtn">Login</button>

                </div>
            </form>
            
            <!-- Forgot Password and Create Account Links -->
            <div class="links">
                <a href="forgot_password.html" class="forgot-password">Forgot Password?</a>
                <a href="create_account.html" class="create-account">Create Account</a>
            </div>
        </div>
    </div>
    <script src="../js/jquery.js"></script>
    <script src="../js/login.js"></script>
</body>
</html>
