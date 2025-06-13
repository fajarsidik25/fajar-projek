<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php 
require_once "db_config.php"; 
require_once "class_sepatu.php"; 

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_sepatu'];
    $merk = $_POST['merk'];
    $ukuran = $_POST['ukuran'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Proses upload gambar
    $gambar = 'default_sepatu.png'; // nama file default jika tidak ada upload

    if (!empty($_FILES['gambar']['name'])) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($ext), $allowed_ext)) {
            $nama_file = uniqid('sepatu_') . '.' . $ext;
            $target = __DIR__ . '/img/' . $nama_file;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                $gambar = $nama_file;
            } else {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $error = "Ekstensi gambar tidak valid. Hanya jpg, jpeg, png, gif yang diperbolehkan.";
        }
    }

    if (!$error) {
        $sepatu = new cls_sepatu;
        $insert = $sepatu->insert_data($nama, $merk, $ukuran, $harga, $stok, $gambar); // pastikan fungsi insert_data sudah ada parameter gambar
        if ($insert) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal menambahkan data.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Sepatu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 50px;
        }

        .form-box {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .btn {
            display: block;
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #4CAF50;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            margin-top: 15px;
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Tambah Sepatu Baru</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Nama Sepatu</label>
            <input type="text" name="nama_sepatu" required>

            <label>Merk</label>
            <input type="text" name="merk" required>

            <label>Ukuran</label>
            <input type="number" name="ukuran" required min="20" max="50">

            <label>Harga</label>
            <input type="number" name="harga" required min="0">

            <label>Stok</label>
            <input type="number" name="stok" required min="0">

            <label>Gambar Sepatu</label>
            <input type="file" name="gambar" accept="image/*">

            <button class="btn" type="submit">Simpan</button>
        </form>
        <a class="back-link" href="index.php">← Kembali</a>
    </div>
</body>
</html>
