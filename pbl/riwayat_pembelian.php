<?php
session_start();
require_once "db_config.php";

// Cek login
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION["login"];

// Prepare query untuk ambil riwayat pembelian user
$sql = "
    SELECT 
        t.id AS transaksi_id, 
        t.tanggal, 
        s.nama_sepatu AS produk, 
        dt.jumlah, 
        (dt.jumlah * s.harga) AS total, 
        t.status
    FROM transaksi t
    JOIN detail_transaksi dt ON t.id = dt.id_transaksi
    JOIN sepatu s ON dt.id_sepatu = s.id
    WHERE t.user_id = ?
    ORDER BY t.tanggal DESC
";

$stmt = $conn->prepare("
    SELECT t.id AS transaksi_id, t.tanggal, s.nama_sepatu AS produk, dt.jumlah, 
           (dt.jumlah * s.harga) AS total
    FROM transaksi t
    JOIN detail_transaksi dt ON t.id = dt.id_transaksi
    JOIN sepatu s ON dt.id_sepatu = s.id
    WHERE t.user_id = ?
    ORDER BY t.tanggal DESC
");

if (!$stmt) {
    die("Prepare statement gagal: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    die("Eksekusi statement gagal: " . $stmt->error);
}

$result = $stmt->get_result();
if (!$result) {
    die("Mengambil hasil gagal: " . $stmt->error);
}

$riwayat = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembelian</title>
    <style>
        :root {
            --primary-color: #1e3d59;
            --secondary-color: #f5f5f5;
            --highlight-color: #3fa9f5;
            --danger-color: #e74c3c;
            --success-color: #27ae60;
        }
        body {
            font-family: Arial, sans-serif;
            background: var(--secondary-color);
            margin: 0; padding: 0;
        }
        header {
            background: var(--primary-color);
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: var(--highlight-color);
            color: white;
        }
        .status-Selesai {
            color: var(--success-color);
            font-weight: bold;
        }
        .status-Dikirim {
            color: #f39c12;
            font-weight: bold;
        }
        .status-Dibatalkan {
            color: var(--danger-color);
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>Riwayat Pembelian</header>

<div class="container">
    <?php if (empty($riwayat)): ?>
        <p>Tidak ada riwayat pembelian.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <!-- <th>Status</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($riwayat as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item["tanggal"]) ?></td>
                        <td><?= htmlspecialchars($item["produk"]) ?></td>
                        <td><?= (int)$item["jumlah"] ?></td>
                        <td>Rp <?= number_format($item["total"], 0, ',', '.') ?></td>
                        <!-- <td class="status-<?= htmlspecialchars($item["status"]) ?>">
                            <?= htmlspecialchars($item["status"]) ?>
                        </td> -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="profil_user.php" class="back-link">&larr; Kembali ke Profil</a>
 <br>
    <a href="dashboard_user.php" class="back-link">&larr; Kembali ke Menu Utama</a>
</div>

</body>
</html>
