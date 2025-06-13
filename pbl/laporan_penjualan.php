
<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit();
}
require_once "db_config.php";

// Contoh query: total transaksi, omzet, dan daftar penjualan
$sql = "SELECT p.id, p.tanggal, u.username, p.total
        FROM pembelian p
        JOIN login u ON p.user_id = u.id
        ORDER BY p.tanggal DESC";
$result = $conn->query($sql);

// Total omzet
$omzet = 0;
if ($result) {
    foreach ($result as $row) {
        $omzet += $row['total'];
    }
    // Reset pointer
    $result->data_seek(0);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 800px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 12px; }
        h2 { color: #1e3d59; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background: #3fa9f5; color: #fff; }
        tr:hover { background: #f1f7fa; }
        .back-link { display: inline-block; margin-top: 18px; color: #3fa9f5; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h2>Laporan Penjualan</h2>
    <p><strong>Total Omzet:</strong> Rp <?= number_format($omzet, 0, ',', '.') ?></p>
    <table>
        <tr>
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>User</th>
            <th>Total</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">Belum ada transaksi.</td></tr>
        <?php endif; ?>
    </table>
    <a href="index.php" class="back-link">&larr; Kembali ke Dashboard</a>
</div>
</body>
</html>