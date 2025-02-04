<?php 
session_start();

if (!isset($_SESSION["is_logged"]) || $_SESSION["is_logged"] !== true) {
    header("Location: /admin/login.php");
    exit();
}
?>