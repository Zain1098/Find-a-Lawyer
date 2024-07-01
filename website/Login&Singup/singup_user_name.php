<?php
include "conn.php";
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['first-name'] = $_POST['first-name'];
    $_SESSION['last-name'] = $_POST['last-name'];
    header("Location: singup_user_email.php");
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
            <p>Enter your name</p>
            <p class="instructions">Please enter your first and last name. Your first name is required while the last name is optional.</p>
        </div>
        <div class="right-section">
            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" id="first-name" name="first-name" placeholder=" " required autofocus>
                    <label for="first-name">First name</label>
                    <small class="condition">e.g. John</small>
                </div>
                <div class="input-group">
                    <input type="text" id="last-name" name="last-name" placeholder=" ">
                    <label for="last-name">Last name (optional)</label>
                    <small class="condition">e.g. Doe</small>
                </div>
                <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                <button type="submit">Next</button>
            </form>
        </div>
    </div>
</body>

</html>