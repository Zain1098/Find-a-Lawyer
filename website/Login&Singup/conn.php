<?php

$conn = mysqli_connect("localhost", "root", "", "lawyer");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
