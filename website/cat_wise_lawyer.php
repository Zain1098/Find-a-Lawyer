<?php
$cat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($cat_id > 0) {
    header("Location: lawyer.php?cat=" . $cat_id);
    exit();
}
header("Location: lawyer.php");
exit();
?>