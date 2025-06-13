<?php
session_start();
require_once "db_config.php";

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET["id"])) {
    echo "ID transaksi tidak ditemukan.";
    exit();
}

$id_transaksi = (int)$_GET["id"];

// Ambil data transaksi
$stmt = $conn->prepare("SELECT * FROM transaksi WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id_transaksi, $_SESSION["login"]);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Transaksi tidak ditemukan atau Anda tidak memiliki akses.";
    exit();
}

$transaksi = $result->fetch_assoc();

// Ambil detail transaksi
$stmt2 = $conn->prepare("
    SELECT d.*, s.nama_sepatu, s.harga 
    FROM detail_transaksi d
    JOIN sepatu s ON d.id_sepatu = s.id
    WHERE d.id_transaksi = ?
");
$stmt2->bind_param("i", $id_transaksi);
$stmt2->execute();
$details = $stmt2->get_result();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Berhasil</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        h2 { color: #27ae60; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #2ecc71; color: white; }
        .info { margin-top: 20px; }
        .back { margin-top: 30px; text-align: center; }
        .back a {
            padding: 10px 25px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Transaksi Berhasil!</h2>
    <div class="info">
        <p><strong>ID Transaksi:</strong> <?= $transaksi['id'] ?></p>
        <p><strong>Tanggal:</strong> <?= $transaksi['tanggal'] ?></p>
        <p><strong>Metode Pembayaran:</strong> <?= strtoupper($transaksi['metode']) ?></p>
        <p><strong>Nomor/ID:</strong> <?= htmlspecialchars($transaksi['nomor']) ?></p>
    </div>

    <table>
        <tr>
            <th>Nama Sepatu</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($row = $details->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_sepatu']) ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h3 style="text-align:right; margin-top:20px;">Total: Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></h3>

    <div class="back">
        <a href="dashboard_user.php">Kembali ke Beranda</a>
    </div>
</div>
</body>
</html>
