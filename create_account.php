<?php
session_start();
// If already logged in, redirect to home
if (isset($_SESSION["username"])) {
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Attendance System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .container {
            max-width: 400px;
            margin-top: 50px;
        }
        .alert {
            display: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Create Account</h2>
        <div id="message" class="alert"></div>
        <form id="registerForm">
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <script>
    $(document).ready(function() {
        $("#registerForm").submit(function(e) {
            e.preventDefault();

            let fullName = $("#fullName").val().trim();
            let username = $("#username").val().trim();
            let password = $("#password").val();
            let confirmPassword = $("#confirmPassword").val();
            let message = $("#message");

            // Client-side validation
            if (password !== confirmPassword) {
                message.removeClass("alert-success").addClass("alert-danger").text("Passwords do not match").show();
                return;
            }
            if (password.length < 6) {
                message.removeClass("alert-success").addClass("alert-danger").text("Password must be at least 6 characters").show();
                return;
            }

            $.ajax({
                url: "register.php",
                type: "POST",
                data: {
                    fullName: fullName,
                    username: username,
                    password: password
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        message.removeClass("alert-danger").addClass("alert-success").text(response.success).show();
                        setTimeout(() => {
                            window.location.href = "login.php";
                        }, 2000);
                    } else {
                        message.removeClass("alert-success").addClass("alert-danger").text(response.error).show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Registration error:", error, xhr.responseText);
                    message.removeClass("alert-success").addClass("alert-danger").text("An error occurred. Please try again.").show();
                }
            });
        });
    });
    </script>
</body>
</html>
