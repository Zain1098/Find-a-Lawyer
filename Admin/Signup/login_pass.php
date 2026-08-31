<?php
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../../website/includes/auth.php';

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
        // Query admin table with prepared statement
        $stmt_admin = $con->prepare("SELECT id, name, email, password FROM admin WHERE email = ?");
        $stmt_admin->bind_param("s", $email);
        $stmt_admin->execute();
        $res_admin = $stmt_admin->get_result();

        if ($res_admin && $res_admin->num_rows === 1) {
            $admin_row = $res_admin->fetch_assoc();
            if (verifyAndUpgradePassword($con, 'admin', 'id', $admin_row['id'], $password, $admin_row['password'])) {
                $_SESSION['id'] = $admin_row['id'];
                $_SESSION['user_id'] = $admin_row['id'];
                $_SESSION['name'] = $admin_row['name'];
                $_SESSION['user_name'] = $admin_row['name'];
                $_SESSION['email'] = $admin_row['email'];
                $_SESSION['user_email'] = $admin_row['email'];
                $_SESSION['user_type'] = 'admin';
                $stmt_admin->close();
                header('Location: ../index.php');
                exit();
            }
        }
        $stmt_admin->close();

        $error = "Invalid administrator password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In - Password</title>
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
        .admin-badge {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <a href="../../website/index.php">Law<span>firm.</span> <small style="font-size:12px; color:#f59e0b;">Admin</small></a>
            </div>
            <h2>Admin Portal Sign In</h2>
            <div class="admin-badge"><i class="fa fa-shield-halved"></i> <?php echo htmlspecialchars($email); ?> &nbsp;<a href="login_email.php" style="color:#2563eb; text-decoration:none; font-size:12px;">(Change)</a></div>
            
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
                <button type="submit">Sign In to Dashboard</button>
            </form>
            <div class="footer">
                <div class="dropdown">
                    <p>New Administrator? <span style="color:#007bff; font-weight:600; cursor:pointer;">Create Account</span></p>
                    <div class="dropdown-content">
                        <a href="signup_admin_name.php">Create Admin Account</a>
                    </div>
                </div>
            </div>
            <div class="footer-links">
                <a href="../../website/index.php">Public Website</a>
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