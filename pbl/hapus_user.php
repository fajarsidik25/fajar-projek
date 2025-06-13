<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit();
}
require_once "db_config.php";

$id = intval($_GET['id']);
if ($id > 0) {
    $sql = "DELETE FROM login WHERE id=$id";
    $conn->query($sql);
}
header("Location: manajemen_user.php");
exit();