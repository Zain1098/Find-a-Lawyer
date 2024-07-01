<?php
include "../conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['degrees'] = $_POST['degrees'];
    $_SESSION['universities'] = $_POST['universities'];
    header("Location: singup_law_appoin.php");  
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Educational Background</title>
    <link rel="stylesheet" href="../styles1.css">
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <a href="../../index.php">Law<span>firm.</span></a>
            </div>
            <h1>Create a Lawyer Account</h1>
            <p>Enter your Educational Background</p>
            <p class="instructions">Please enter your degrees and the universities or colleges you attended.</p>
        </div>
        <div class="right-section">
            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" id="degrees" name="degrees" placeholder=" " required>
                    <label for="degrees">Degrees</label>
                    <small class="condition">e.g., LL.B., LL.M.</small>
                </div>
                <div class="input-group">
                    <input type="text" id="universities" name="universities" placeholder=" " required>
                    <label for="universities">Universities/Colleges Attended</label>
                    <small class="condition">e.g., Harvard Law School</small>
                </div>
                <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                <button type="submit">Next</button>
            </form>
        </div>
    </div>
</body>

</html>
