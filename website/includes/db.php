<?php
/**
 * Database Configuration & Connection Helper
 * Find-a-Lawyer Platform
 */

// Automatically load .env file if present
$env_paths = [__DIR__ . '/../../.env', __DIR__ . '/../.env', __DIR__ . '/.env'];
foreach ($env_paths as $env_file) {
    if (file_exists($env_file) && is_readable($env_file)) {
        $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) continue;
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val, " \t\n\r\0\x0B\"'");
            if (!isset($_ENV[$key])) $_ENV[$key] = $val;
            if (!getenv($key)) putenv("$key=$val");
        }
        break;
    }
}

// Detect environment: Localhost vs Live Server (InfinityFree)
$http_host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
$is_localhost = in_array($http_host, ['localhost', '127.0.0.1']) 
    || strpos($http_host, 'localhost:') === 0 
    || strpos($http_host, '127.0.0.1:') === 0;

if ($is_localhost) {
    // Local XAMPP Environment Defaults
    $default_host = 'localhost';
    $default_user = 'root';
    $default_pass = '';
    $default_name = 'e_project';
} else {
    // Live Server (InfinityFree) Production Defaults
    $default_host = 'sql310.infinityfree.com';
    $default_user = 'if0_42797048';
    $default_pass = '8DoAiTuQ4a';
    $default_name = 'if0_42797048_e_project';
}

if (!defined('DB_HOST')) {
    define('DB_HOST', getenv('DB_HOST') ?: $default_host);
}
if (!defined('DB_USER')) {
    define('DB_USER', getenv('DB_USER') ?: $default_user);
}
if (!defined('DB_PASS')) {
    define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : $default_pass);
}
if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('DB_NAME') ?: $default_name);
}

// Global connection instance
global $con, $conn;

if (!isset($con) || !($con instanceof mysqli) || @$con->ping() === false) {
    // Disable default mysqli exceptions for custom graceful handling
    mysqli_report(MYSQLI_REPORT_OFF);

    $con = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$con) {
        $conn_error = mysqli_connect_error();
        // Fallback or helpful error message
        error_log("Database Connection Failed: " . $conn_error);
    } else {
        mysqli_set_charset($con, 'utf8mb4');
    }
}

// Ensure $conn is always an alias of $con for legacy compatibility
$conn = $con;

/**
 * Returns active MySQLi connection
 * @return mysqli|false
 */
function getDbConnection() {
    global $con;
    return $con;
}

/**
 * Helper to escape string safely
 * @param string $str
 * @return string
 */
function escapeString($str) {
    global $con;
    if (!$con) return addslashes((string)$str);
    return mysqli_real_escape_string($con, (string)$str);
}

/**
 * Helper to sanitize user input for HTML output
 * @param mixed $data
 * @return string
 */
function sanitizeInput($data) {
    if (is_null($data)) return '';
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

/**
 * Execute a parameterized prepared query safely
 * @param string $sql
 * @param array $params
 * @param string $types e.g. "ssi"
 * @return mysqli_result|bool
 */
function dbExecute($sql, $params = [], $types = '') {
    global $con;
    if (!$con) return false;

    if (empty($params)) {
        return mysqli_query($con, $sql);
    }

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $con->error . " | SQL: " . $sql);
        return false;
    }

    if (!empty($params)) {
        if (empty($types)) {
            $types = str_repeat('s', count($params));
        }
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result === false && $stmt->errno === 0) {
        // For INSERT / UPDATE / DELETE return true or affected rows
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected >= 0 ? true : false;
    }

    $stmt->close();
    return $result;
}
