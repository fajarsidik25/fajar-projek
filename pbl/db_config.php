<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "function.php"; 

// Konfigurasi database
$host = "127.0.0.1";
$user = "root"; 
$pass = ""; 
$dbname = "db_latih"; 

// Koneksi ke database
list($conn, $status) = dbconnect($host, $user, $pass, $dbname);

// Cek status koneksi (untuk debugging, bisa dihapus di produksi)
if (!$conn) {
    die("Koneksi gagal: " . $status);
}
// Kalau berhasil, $conn akan dipakai global di class
?>
