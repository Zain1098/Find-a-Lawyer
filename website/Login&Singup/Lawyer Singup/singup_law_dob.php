<?php
include "../conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['dob'] = $_POST['dob'];
    header("Location: singup_law_pass.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="../styles1.css">
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <a href="../../index.php">Law<span>firm.</span></a>
            </div>
            <h1>Create a Lawyer Account</h1>
            <p>Enter your Date of Birth</p>
            <p class="instructions">Please enter your date of birth. This will be used for account verification and notifications.</p>
        </div>
        <div class="right-section">
            <form action="" method="POST">
                <div class="input-group">
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                <button type="submit">Next</button>
            </form>
        </div>
    </div>

</body>

</html>