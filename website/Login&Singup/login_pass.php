<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['email'];
    $password = $_POST['password'];

    // Check user table
    $query_user = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $result_user = mysqli_query($conn, $query_user);

    if (mysqli_num_rows($result_user) == 1) {
        $_SESSION['email'] = $email;
        $_SESSION['user_type'] = 'user';
        header('Location: ../index.php'); // Redirect to user page
        exit();
    }

    // Check lawyer table
    $query_lawyer = "SELECT * FROM lawyer WHERE email='$email' AND password='$password'";
    $result_lawyer = mysqli_query($conn, $query_lawyer);

    if (mysqli_num_rows($result_lawyer) == 1) {
        $_SESSION['email'] = $email;
        $_SESSION['user_type'] = 'lawyer';
        header('Location: ../index.php'); // Redirect to lawyer page
        exit();
    }

    $error = "Invalid email or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .error {
            color: red;
        }

        .input-group.invalid input {
            border-color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <a href="../index.php">Law<span>firm.</span></a>
            </div>
            <h2>Sign in to Lawfirm</h2>
            <p>Use your Account</p>
            <form action="" method="POST">
                <div class="password-group <?php echo !empty($error) ? 'invalid' : ''; ?>">
                    <input type="password" id="password" name="password" placeholder="" required autofocus>
                    <label for="password">Password</label>
                    <span id="toggle-password" class="input-icon fa fa-eye"></span>
                </div>
                <?php if (!empty($error)) : ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <a href="reset_password.php">Forgot password?</a>
                <button type="submit">Next</button>
            </form>
            <div class="footer">
                <div class="dropdown">
                    <p>Create Account</p>
                    <div class="dropdown-content">
                        <a href="signup_user_name.php">Create User Account</a>
                        <a href="../Lawyer Singup/singup_law_name.php">Create Lawyer Account</a>
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
    <script>
        $(document).ready(function() {
            $('#toggle-password').click(function() {
                let passwordField = $('#password');
                let passwordFieldType = passwordField.attr('type');
                if (passwordFieldType == 'password') {
                    passwordField.attr('type', 'text');
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
</body>
</html>
