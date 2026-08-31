<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (empty($_SESSION['email'])) {
    header('Location: login_email.php');
    exit();
}

$email = $_SESSION['email'];
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if (empty($password)) {
        $error = "Please enter your password.";
    } else {
        // 1. Check User table
        $stmt_user = $con->prepare("SELECT id, name, email, password FROM user WHERE email = ?");
        $stmt_user->bind_param("s", $email);
        $stmt_user->execute();
        $res_user = $stmt_user->get_result();

        if ($res_user && $res_user->num_rows === 1) {
            $user_row = $res_user->fetch_assoc();
            if (verifyAndUpgradePassword($con, 'user', 'id', $user_row['id'], $password, $user_row['password'])) {
                $_SESSION['id'] = $user_row['id'];
                $_SESSION['user_id'] = $user_row['id'];
                $_SESSION['name'] = $user_row['name'];
                $_SESSION['user_name'] = $user_row['name'];
                $_SESSION['email'] = $user_row['email'];
                $_SESSION['user_email'] = $user_row['email'];
                $_SESSION['user_type'] = 'user';
                $stmt_user->close();
                header('Location: ../index.php');
                exit();
            }
        }
        $stmt_user->close();

        // 2. Check Lawyer table
        $stmt_lawyer = $con->prepare("SELECT id, name, `last name` as last_name, email, password, image FROM lawyer WHERE email = ?");
        $stmt_lawyer->bind_param("s", $email);
        $stmt_lawyer->execute();
        $res_lawyer = $stmt_lawyer->get_result();

        if ($res_lawyer && $res_lawyer->num_rows === 1) {
            $lawyer_row = $res_lawyer->fetch_assoc();
            if (verifyAndUpgradePassword($con, 'lawyer', 'id', $lawyer_row['id'], $password, $lawyer_row['password'])) {
                $_SESSION['id'] = $lawyer_row['id'];
                $_SESSION['user_id'] = $lawyer_row['id'];
                $_SESSION['name'] = trim($lawyer_row['name'] . ' ' . $lawyer_row['last_name']);
                $_SESSION['user_name'] = trim($lawyer_row['name'] . ' ' . $lawyer_row['last_name']);
                $_SESSION['email'] = $lawyer_row['email'];
                $_SESSION['user_email'] = $lawyer_row['email'];
                $_SESSION['image'] = $lawyer_row['image'];
                $_SESSION['user_image'] = $lawyer_row['image'];
                $_SESSION['user_type'] = 'lawyer';
                $stmt_lawyer->close();
                header('Location: ../index.php');
                exit();
            }
        }
        $stmt_lawyer->close();

        $error = "Invalid password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Password | Lawfirm</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 8px;
            font-weight: 500;
        }
        .user-badge {
            background-color: #f0f4f8;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            color: #4a5568;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <a href="../index.php">Law<span>firm.</span></a>
            </div>
            <h2>Welcome Back</h2>
            <div class="user-badge"><i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($email); ?> &nbsp;<a href="login_email.php" style="color:#007bff; text-decoration:none; font-size:12px;">(Change)</a></div>
            
            <form action="" method="POST">
                <div class="password-group <?php echo !empty($error) ? 'invalid' : ''; ?>">
                    <input type="password" id="password" name="password" placeholder=" " required autofocus>
                    <label for="password">Password</label>
                    <span id="toggle-password" class="input-icon fa fa-eye"></span>
                </div>
                <?php if (!empty($error)) : ?>
                    <p class="error"><i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <a href="reset_password.php">Forgot password?</a>
                <button type="submit">Sign In</button>
            </form>
            <div class="footer">
                <div class="dropdown">
                    <p>Don't have an account? <span style="color:#007bff; font-weight:600; cursor:pointer;">Create Account</span></p>
                    <div class="dropdown-content">
                        <a href="singup_user_name.php"><i class="fa fa-user"></i> Create Client Account</a>
                        <a href="./Lawyer%20Singup/singup_law_name.php"><i class="fa fa-gavel"></i> Join as Lawyer</a>
                    </div>
                </div>
            </div>
            <div class="footer-links">
                <a href="../about.php">About</a>
                <a href="../contact.php">Contact</a>
                <a href="../index.php">Home</a>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#toggle-password').click(function() {
                let passwordField = $('#password');
                let passwordFieldType = passwordField.attr('type');
                if (passwordFieldType === 'password') {
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
