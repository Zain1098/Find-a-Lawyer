<?php
include "../conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($password!= $confirmPassword) {
        $error = "Passwords do not match";
        $error_class = "error";
    } else {
        $_SESSION['password'] = $password;
        header("Location: singup_law_detail.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="..//styles1.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <a href="../../index.php">Law<span>firm.</span></a>
            </div>
            <h1>Create a Lawyer Account</h1>
            <p>Enter your Password</p>
        </div>
        <div class="right-section">
            <form action="" method="POST">
                <div class="input-group password-input">
                    <input type="password" id="password" name="password" required placeholder=" ">
                    <label for="password">Password</label>
                    <span class="input-icon" onclick="togglePasswordVisibility('password', 'eye-icon')">
                        <i class="fa fa-eye" id="eye-icon"></i>
                    </span>
                    <div class="condition">Your password must be at least 8 characters long.</div>
                </div>
                <div class="input-group password-input <?php echo isset($input_error_class) ? $input_error_class : ''; ?>">
                    <input type="password" id="confirm-password" name="confirm-password" placeholder=" " required <?php echo isset($input_error_class) ? 'style="border: 1px solid red;"' : ''; ?>>
                    <label for="confirm-password">Confirm Password</label>
                    <span class="input-icon" onclick="togglePasswordVisibility('confirm-password', 'confirm-eye-icon')">
                        <i class="fa fa-eye" id="confirm-eye-icon"></i>
                    </span>
                </div>
                <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                <button type="submit">Next</button>
                <?php if (isset($error)) echo "<p class='$error_class'>$error</p>"; ?>
            </form>
        </div>
    </div>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
