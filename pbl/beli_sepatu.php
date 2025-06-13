<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "db_config.php";
require_once "class_sepatu.php";

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: lihat_sepatu.php");
    exit();
}

$id_sepatu = intval($_GET['id']);
$data = new cls_sepatu;
$sepatu = $data->get_by_id($id_sepatu);

if (!$sepatu) {
    echo "Sepatu tidak ditemukan.";
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah = intval($_POST['jumlah']);
    if ($jumlah > 0 && $jumlah <= $sepatu['stok']) {
      $_SESSION['keranjang'][$sepatu['id']] = [
    'jumlah' => $jumlah
];

        // Tambahkan redirect setelah tambah ke keranjang
        header("Location: keranjang.php");
        exit();
    } else {
        $message = "Jumlah tidak valid atau melebihi stok tersedia.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Beli Sepatu - <?= htmlspecialchars($sepatu['nama_sepatu']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9fafb;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }

        img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        h1 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        p {
            margin: 8px 0;
            color: #555;
        }

        form {
            margin-top: 20px;
        }

        input[type="number"] {
            width: 100px;
            padding: 8px;
            font-size: 16px;
            margin-right: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            text-align: center;
        }

        button {
            padding: 10px 20px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1e8449;
        }

        .message {
            margin-top: 20px;
            font-weight: 600;
            color: #27ae60;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="img/<?= htmlspecialchars($sepatu['gambar'] ?: 'sepatu_default.jpg') ?>" alt="Sepatu">
    <h1><?= htmlspecialchars($sepatu['nama_sepatu']) ?></h1>
    <p><strong>Merk:</strong> <?= htmlspecialchars($sepatu['merk']) ?></p>
    <p><strong>Ukuran:</strong> <?= $sepatu['ukuran'] ?></p>
    <p><strong>Harga:</strong> Rp <?= number_format($sepatu['harga'], 0, ',', '.') ?></p>
    <p><strong>Stok:</strong> <?= $sepatu['stok'] ?></p>

    <form method="post" action="">
        <input type="number" name="jumlah" min="1" max="<?= $sepatu['stok'] ?>" value="1" required>
        <button type="submit">tambahkan keranjang</button>
           <form method="post" action="checkout.php" style="margin-top: 10px;">
    <input type="hidden" name="id" value="<?= $s['id'] ?>">
    <input type="hidden" name="nama_sepatu" value="<?= htmlspecialchars($s['nama_sepatu']) ?>">
    <input type="hidden" name="harga" value="<?= $s['harga'] ?>">
    <input type="hidden" name="gambar" value="<?= htmlspecialchars($s['gambar']) ?>">
    <button type="submit" class="btn-beli" style="background-color:#e67e22;">💳 Beli Sekarang</button>
</form>
    </form>
        
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <a href="lihat_sepatu.php" class="back-link">⬅ Kembali ke Daftar Sepatu</a>
</div>

</body>
</html>
