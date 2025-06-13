<?php
ini_set('display_errors', 1); error_reporting(E_ALL);
session_start();

require_once 'db_config.php';

// Cek apakah user sudah login
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION["keranjang"])) {
    $_SESSION["keranjang"] = [];
}

// Proses hapus item dari keranjang
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];  // pastikan integer
    if (isset($_SESSION["keranjang"][$id])) {
        unset($_SESSION["keranjang"][$id]);
    }
    header("Location: keranjang.php");
    exit();
}

// Ambil detail sepatu berdasarkan ID dari keranjang
$items = [];
$total = 0;
if (!empty($_SESSION["keranjang"])) {
    $idsArray = array_map('intval', array_keys($_SESSION["keranjang"]));
    $ids = implode(',', $idsArray);

    $query = "SELECT * FROM sepatu WHERE id IN ($ids)";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];

        // Pastikan jumlah adalah integer
       $jumlah = $_SESSION["keranjang"][$id]['jumlah'];
        // Pastikan harga juga integer
        $harga = (int) $row['harga'];

        $row['jumlah'] = $jumlah;
        $row['subtotal'] = $jumlah * $harga;
        $items[] = $row;
        $total += $row['subtotal'];
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #ecf0f1;
            padding: 40px;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .hapus-link {
            color: red;
            text-decoration: none;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 1.1em;
        }

      .button-checkout {
    display: block;
    margin: 10px auto 0 auto; 
    background-color: #2ecc71;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
}


        .button-checkout:hover {
            background-color: #27ae60;
        }
        .back-link {
    display: inline-block;
    margin-top: 20px;
    color: #3498db;
    text-decoration: none;
    font-weight: 600;
}

.back-link:hover {
    color: #2980b9;
}

    </style>
</head>
<body>

<div class="container">
    <h2>Keranjang Belanja Anda</h2>

    <?php if (empty($items)): ?>
        <p>Keranjang Anda kosong.</p>
        <?php else: ?>
        <table>
            <tr>
                <th>Nama Sepatu</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama_sepatu']) ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= $item['jumlah'] ?></td>
                    <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    <td><a class="hapus-link" href="keranjang.php?hapus=<?= $item['id'] ?>">Hapus</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p class="total">Total: Rp <?= number_format($total, 0, ',', '.') ?></p>

        <form action="checkout.php" method="POST">
            <button class="button-checkout" type="submit">Checkout</button>
            <a href="lihat_sepatu.php" class="back-link">⬅ Kembali ke Daftar Sepatu</a>
            <br>
            <a href="dashboard_user.php" class="back-link">⬅ Kembali ke dashboard_user</a>
            
        </form>
    <?php endif; ?>
</div>

</body>
</html>
