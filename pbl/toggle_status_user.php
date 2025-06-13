<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin") exit;
require_once "db_config.php";

$id = intval($_POST['id']);
$status = $_POST['status'] === 'aktif' ? 'blokir' : 'aktif';

if ($id > 0) {
    $sql = "UPDATE login SET status='$status' WHERE id=$id";
    $conn->query($sql);
}
echo "OK";