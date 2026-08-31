<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (empty($_SESSION['first-name']) || empty($_SESSION['email'])) {
    header("Location: singup_user_name.php");
    exit();
}

$error = "";
$firstName = $_SESSION['first-name'] ?? '';
$lastName = $_SESSION['last-name'] ?? '';
$email = $_SESSION['email'] ?? '';
$fullName = trim($firstName . ' ' . $lastName);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists in user or lawyer table
        $check_stmt = $con->prepare("SELECT email FROM user WHERE email = ? UNION SELECT email FROM lawyer WHERE email = ?");
        $check_stmt->bind_param("ss", $email, $email);
        $check_stmt->execute();
        $check_res = $check_stmt->get_result();

        if ($check_res && $check_res->num_rows > 0) {
            $error = "An account with this email address already exists. Please sign in.";
            $check_stmt->close();
        } else {
            $check_stmt->close();

            // Hash password securely
            $hashedPassword = hashPassword($password);

            // Insert into user table using prepared statement
            $insert_stmt = $con->prepare("INSERT INTO user (name, email, password) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $fullName, $email, $hashedPassword);

            if ($insert_stmt->execute()) {
                $newUserId = $insert_stmt->insert_id;
                $insert_stmt->close();

                // Auto-login newly created user
                $_SESSION['id'] = $newUserId;
                $_SESSION['user_id'] = $newUserId;
                $_SESSION['name'] = $fullName;
                $_SESSION['user_name'] = $fullName;
                $_SESSION['email'] = $email;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_type'] = 'user';

                header("Location: ../index.php");
                exit();
            } else {
                $error = "Registration failed: " . htmlspecialchars($insert_stmt->error);
                $insert_stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Client Account - Password</title>
    <link rel="stylesheet" href="styles1.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 10px;
            font-weight: 500;
        }
        .user-preview {
            background-color: #f8fafc;
            border-left: 3px solid #007bff;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <a href="../index.php">Law<span>firm.</span></a>
            </div>
            <h1>Create a User Account</h1>
            <p>Set a secure password for your account.</p>
            <div class="user-preview">
                <strong>Name:</strong> <?php echo htmlspecialchars($fullName); ?><br>
                <strong>Email:</strong> <?php echo htmlspecialchars($email); ?>
            </div>
        </div>
        <div class="right-section">
            <form action="" method="POST">
                <div class="input-group password-input">
                    <input type="password" id="password" name="password" required placeholder=" " autofocus>
                    <label for="password">Password</label>
                    <span class="input-icon" onclick="togglePasswordVisibility('password', 'eye-icon')">
                        <i class="fa fa-eye" id="eye-icon"></i>
                    </span>
                    <div class="condition">Password must be at least 6 characters long.</div>
                </div>
                <div class="input-group password-input">
                    <input type="password" id="confirm-password" name="confirm-password" placeholder=" " required>
                    <label for="confirm-password">Confirm Password</label>
                    <span class="input-icon" onclick="togglePasswordVisibility('confirm-password', 'confirm-eye-icon')">
                        <i class="fa fa-eye" id="confirm-eye-icon"></i>
                    </span>
                </div>
                <?php if (!empty($error)) : ?>
                    <p class="error"><i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                    <button type="submit">Complete Registration</button>
                </div>
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
