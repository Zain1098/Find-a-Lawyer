<?php
include "../connection.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];
    header('Location: login_pass.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <a href="index.html">Law<span>firm.</span></a>
            </div>
            <h2>Sign in to Lawfirm</h2> 
            <p>Use your Account</p>
            <form action="" method="POST">
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder=" " required autofocus>
                    <label for="email">Email</label>
                    <small class="condition">e.g. example@domain.com</small>
                </div>
                <button type="submit">Next</button>
            </form>
            <div class="footer">
                <div class="dropdown">
                    <p>Create Account</p>
                    <div class="dropdown-content">
                        <a href="signup_admin_name.php">Create Account</a>
                    </div>
                </div>
            </div>
            <div class="footer-links">
                <a href="#">Help</a>
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
            </div>
        </div>
    </div>
</body>

</html>