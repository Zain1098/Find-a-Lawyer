<?php
include "../connection.php";
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login_email.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $email = $_SESSION['email'];

    // Check if passwords match
    if ($password === $confirmPassword) {
        // Update password in the database (Note: This is highly insecure and not recommended)
        $sql = "UPDATE admin SET password = '$password' WHERE email = '$email'";
        if (mysqli_query($con, $sql)) {
            // Clear session data and redirect
            session_unset();
            session_destroy();
            header("Location: ../index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }
    } else {
        $error = "Passwords do not match.";
        $error_class = "error";
        $input_error_class = "input-error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .error {
            color: red;
        }

        .input-group {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 10px 0;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            padding-right: 40px;
            /* Space for the icon */
            box-sizing: border-box;
        }

        .input-group label {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            pointer-events: none;
            transition: 0.2s;
        }

        .input-group input:focus+label,
        .input-group input:not(:placeholder-shown)+label {
            top: 0;
            font-size: 12px;
            color: #666;
        }

        .input-group .input-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <a href="../index.html">Law<span>firm.</span></a>
            </div>
            <h2>Reset Password</h2>
            <form action="" method="POST">
                <div class="input-group <?php echo isset($input_error_class) ? $input_error_class : ''; ?>">
                    <input type="password" id="password" name="password" required placeholder=" " autocomplete="off">
                    <label for="password">New Password</label>
                    <span class="input-icon" onclick="togglePasswordVisibility('password', 'eye-icon')">
                        <i class="fa fa-eye" id="eye-icon"></i>
                    </span>
                </div>
                <div class="input-group <?php echo isset($input_error_class) ? $input_error_class : ''; ?>">
                    <input type="password" id="confirm-password" name="confirm-password" required placeholder=" " autocomplete="off">
                    <label for="confirm-password">Confirm New Password</label>
                    <span class="input-icon" onclick="togglePasswordVisibility('confirm-password', 'eye-icon2')">
                        <i class="fa fa-eye" id="eye-icon2"></i>
                    </span>
                </div>
                <button type="submit">Reset Password</button>
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