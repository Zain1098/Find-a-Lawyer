<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/../website/includes/auth.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: Signup/login_email.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$valid_statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

if ($id > 0 && in_array($status, $valid_statuses)) {
    $stmt = $con->prepare("UPDATE appointment SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: appointment.php?msg=Appointment+Status+Updated+to+" . ucfirst($status));
exit();
