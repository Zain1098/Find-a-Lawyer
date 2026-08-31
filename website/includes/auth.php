<?php
/**
 * Authentication and Session Helper
 * Find-a-Lawyer Platform
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

/**
 * Hash password securely using Bcrypt (PASSWORD_DEFAULT)
 * @param string $password
 * @return string
 */
function hashPassword($password) {
    return password_hash((string)$password, PASSWORD_DEFAULT);
}

/**
 * Verify password with automatic legacy plain-text upgrade
 * @param mysqli $db
 * @param string $table
 * @param string $idColumn
 * @param int|string $id
 * @param string $plainPassword
 * @param string $storedPassword
 * @return bool
 */
function verifyAndUpgradePassword($db, $table, $idColumn, $id, $plainPassword, $storedPassword) {
    if (empty($plainPassword) || empty($storedPassword)) {
        return false;
    }

    // 1. Check if stored password is a valid modern hash (e.g. $2y$...)
    if (password_verify($plainPassword, $storedPassword)) {
        // If password needs rehash due to algorithm updates, update it
        if (password_needs_rehash($storedPassword, PASSWORD_DEFAULT)) {
            $newHash = password_hash($plainPassword, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE `$table` SET `password` = ? WHERE `$idColumn` = ?");
            if ($stmt) {
                $stmt->bind_param("ss", $newHash, $id);
                $stmt->execute();
                $stmt->close();
            }
        }
        return true;
    }

    // 2. Legacy check: If stored password was plain-text (e.g. '1122' or 'ammar123456')
    if ($plainPassword === $storedPassword) {
        // Upgrade legacy plain-text password to secure hash immediately
        $newHash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE `$table` SET `password` = ? WHERE `$idColumn` = ?");
        if ($stmt) {
            $stmt->bind_param("ss", $newHash, $id);
            $stmt->execute();
            $stmt->close();
        }
        return true;
    }

    return false;
}

/**
 * Check if a user/lawyer/admin is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_type']) && (isset($_SESSION['email']) || isset($_SESSION['user_email']));
}

/**
 * Get current user role ('user', 'lawyer', 'admin', or false)
 * @return string|false
 */
function getCurrentUserType() {
    return $_SESSION['user_type'] ?? false;
}

/**
 * Get current user's email
 * @return string|false
 */
function getCurrentUserEmail() {
    return $_SESSION['email'] ?? ($_SESSION['user_email'] ?? false);
}

/**
 * Get current user's name
 * @return string
 */
function getCurrentUserName() {
    return $_SESSION['user_name'] ?? ($_SESSION['name'] ?? 'User');
}

/**
 * Get current user ID
 * @return int|false
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? ($_SESSION['id'] ?? false);
}

/**
 * Get current user avatar image
 * @return string
 */
function getCurrentUserImage() {
    return $_SESSION['user_image'] ?? ($_SESSION['image'] ?? '');
}

/**
 * Log out and destroy session
 * @param string $redirectUrl
 */
function logoutUser($redirectUrl = '../index.php') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: $redirectUrl");
    exit();
}
