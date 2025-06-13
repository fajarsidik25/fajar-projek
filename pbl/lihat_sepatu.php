<?php
session_start();
require_once "db_config.php";
require_once "class_sepatu.php";

// Cek apakah user sudah login
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

// Ambil data sepatu
$data = new cls_sepatu;
$sepatu = $data->show_data();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Sepatu</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f6f9fc;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 20px;
            transition: 0.3s;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .card h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .card p {
            margin: 5px 0;
            color: #555;
        }

        .btn-beli {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 20px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-beli:hover {
            background-color: #1e8449;
        }

        .back-button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            margin-top: 30px;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .back-button:hover {
            background-color: #2980b9;
        }

        .no-data {
            text-align: center;
            color: #888;
            margin-top: 40px;
            font-style: italic;
        }

        .fixed-back {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 999;
}

    </style>
</head>
<body>

<div class="container">
    <h1>Daftar Sepatu</h1>

    <?php if (count($sepatu) > 0): ?>
        <div class="card-grid">
            <?php foreach ($sepatu as $s): ?>
                <div class="card">
                    <img src="img/<?= htmlspecialchars($s['gambar'] ?: 'sepatu_default.jpg') ?>" alt="<?= htmlspecialchars($s['nama_sepatu']) ?>">
                    <h3><?= htmlspecialchars($s['nama_sepatu']) ?></h3>
                    <p><strong>Merk:</strong> <?= htmlspecialchars($s['merk']) ?></p>
                    <p><strong>Ukuran:</strong> <?= $s['ukuran'] ?></p>
                    <p><strong>Harga:</strong> Rp <?= number_format($s['harga'], 0, ',', '.') ?></p>
                    <p><strong>Stok:</strong> <?= $s['stok'] ?></p>
                    <a href="beli_sepatu.php?id=<?= $s['id'] ?>" class="btn-beli">🛒 tambah keranjang</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-data">Belum ada sepatu yang tersedia.</p>
    <?php endif; ?>

    <div style="text-align:center;">
      <a href="dashboard_user.php" class="back-button fixed-back">⬅ Kembali ke Menu Utama</a>

    </div>
</div>

</body>
</html>
