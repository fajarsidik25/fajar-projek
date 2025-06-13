<?php
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

// Jika form disubmit
if (isset($_POST['submit'])) {
    $nama = $_POST['nama_sepatu'];
    $merk = $_POST['merk'];
    $ukuran = $_POST['ukuran'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $gambar = $sepatu['gambar']; // Default: gambar lama

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['gambar']['name'];
        $tmpName = $_FILES['gambar']['tmp_name'];
        $ekstensi = pathinfo($namaFile, PATHINFO_EXTENSION);
        $namaBaru = uniqid() . '.' . strtolower($ekstensi);
        $tujuan = 'img/' . $namaBaru;

        $tipeValid = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($ekstensi), $tipeValid)) {
            if (move_uploaded_file($tmpName, $tujuan)) {
                $gambar = $namaBaru;
            }
        }
    }

    if ($data->update_data($id, $nama, $merk, $ukuran, $harga , $stok, $gambar)) {
        echo "<script>alert('Data berhasil diupdate'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update data');</script>";
    }
}
?>

<html>
<head>
    <title>Edit Data Sepatu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
        }

        h3 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        td {
            padding: 10px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        img {
            max-width: 100px;
            margin-top: 5px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Form Edit Data Sepatu</h3>
        <form method="post" action="" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Nama Sepatu</td>
                    <td><input type="text" name="nama_sepatu" value="<?= htmlspecialchars($sepatu['nama_sepatu']) ?>" required></td>
                </tr>
                <tr>
                    <td>Merk</td>
                    <td><input type="text" name="merk" value="<?= htmlspecialchars($sepatu['merk']) ?>" required></td>
                </tr>
                <tr>
                    <td>Ukuran</td>
                    <td><input type="number" name="ukuran" value="<?= $sepatu['ukuran'] ?>" required></td>
                </tr>
                <tr>
                    <td>Harga</td>
                    <td><input type="number" name="harga" value="<?= $sepatu['harga'] ?>" required></td>
                </tr>
                <tr>
                    <td>Stok</td>
                    <td><input type="number" name="stok" value="<?= $sepatu['stok'] ?>" required></td>
                </tr>
                <tr>
                    <td>Foto</td>
                    <td>
                        <input type="file" name="gambar">
                        <br>
                        <img src="img/<?= htmlspecialchars($sepatu['gambar'] ?: 'sepatu_default.jpg') ?>" alt="Preview Gambar">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" name="submit">Simpan</button>
                    </td>
                </tr>
            </table>
        </form>
        <div class="form-footer">
            <a href="index.php">Kembali ke daftar sepatu</a>
        </div>
    </div>
</body>
</html>
