<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/../website/includes/auth.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: Signup/login_email.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $stmt = $con->prepare("DELETE FROM appointment WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: appointment.php?msg=Appointment+Deleted+Successfully");
exit();
