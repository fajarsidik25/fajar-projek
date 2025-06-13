<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "db_config.php";
require_once "class_sepatu.php";



// Membuat objek dari class_sepatu
$data = new cls_sepatu;

// Mengecek apakah parameter `id` ada di URL
if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id = $_GET['id'];

// Ambil data sepatu berdasarkan ID
$sepatu = $data->get_by_id($id);

// Jika data tidak ditemukan
if (!$sepatu) {
    echo "Data tidak ditemukan!";
    exit;
}

// Jika tombol konfirmasi hapus ditekan
if (isset($_POST['hapus'])) {
    if ($data->delete_data($id)) {
        echo "<script>alert('Data berhasil dihapus'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}
?>

<html>
<head>
    <title>Hapus Data Sepatu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fefefe;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h3 {
            color: #333;
        }

        .warning {
            margin: 20px 0;
            color: red;
            font-weight: bold;
        }

        button {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .hapus {
            background-color: #d9534f;
            color: white;
        }

        .batal {
            background-color: #6c757d;
            color: white;
        }

        a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Konfirmasi Hapus</h3>
        <p class="warning">Apakah Anda yakin ingin menghapus data sepatu:<br><strong><?= $sepatu['nama_sepatu'] ?></strong>?</p>
        <form method="post">
            <button type="submit" name="hapus" class="hapus">Ya, Hapus</button>
            <button class="batal"><a href="index.php">Batal</a></button>
        </form>
    </div>
</body>
</html>
