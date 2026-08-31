<?php
include "../conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['number'] = $_POST['number'];
    $_SESSION['address'] = $_POST['address'];
    header("Location: singup_law_dob.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="..//styles1.css">
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <a href="../../index.php">Law<span>firm.</span></a>
            </div>
            <h1>Create a Lawyer Account</h1>
            <p>Enter your Contact Information</p>
            <p class="instructions">Please enter valid contact information. This will be used for account verification and notifications.</p>
        </div>
        <div class="right-section">
            <form action="" method="POST">
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder=" " required autofocus>
                    <label for="email">Email</label>
                    <small class="condition">e.g., example@domain.com</small>
                </div>
                <div class="input-group">
                    <input type="tel" id="number" name="number" placeholder=" " pattern="[+]?[0-9]{1,4}[0-9]{7,10}" required>
                    <label for="number">Phone</label>
                    <small class="condition">e.g., +92 314 2364902</small>
                </div>
                <div class="input-group">
                    <input type="text" id="address" name="address" placeholder=" " required>
                    <label for="address">Address</label>
                    <small class="condition">e.g., North Nazmabad, Karachi.</small>
                </div>
                <div class="button-group">
                    <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                    <button type="submit">Next</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>