<?php
include "conn.php";
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['email'] = $_POST['email'];
    header("Location: singup_user_pass.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <a href="../index.php">Law<span>firm.</span></a>
            </div>
            <h1>Create a User Account</h1>
            <p>Enter your email</p>
            <p class="instructions">Please enter a valid email address. This will be used for account verification and notifications.</p>
        </div>
        <div class="right-section">
            <form action="" method="POST">
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder=" " required autofocus>
                    <label for="email">Email</label>
                    <small class="condition">e.g. example@domain.com</small>
                </div>
                <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                <button type="submit">Next</button>
            </form>
        </div>
    </div>
</body>
</html>
