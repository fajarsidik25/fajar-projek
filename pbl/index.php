<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


require_once "db_config.php";
require_once "class_sepatu.php";



// Cek dan proses login dari database
if (isset($_POST["username"]) && isset($_POST["sandi"])) {
    $username = $_POST["username"];
    $sandi = $_POST["sandi"];


    
    // Query ke tabel login
 $stmt = $conn->prepare("SELECT * FROM login WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Periksa password menggunakan password_verify
    if (password_verify($sandi, $user["password"])) {
        $_SESSION["login"] = true;
        $_SESSION["role"] = $user["role"];
        $_SESSION["username"] = $user["username"];

        if ($user["role"] === "admin") {
            header("Location: index.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit;
    } else {
        $error = "Password salah!";
    }
} else {
    $error = "Username tidak ditemukan!";
}
}

// Proses logout
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Proteksi halaman, hanya tampil data jika sudah login
if (!isset($_SESSION["login"])) {
    // tidak login, tidak tampil data
    $data_sepatu = [];
} else {
    $data = new cls_sepatu;
    $data_sepatu = $data->show_data();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Toko Sepatu</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-image: url('img/toko_sepatu_modern.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            width: 95%;
            max-width: 1100px;
            background-color: rgba(255,255,255,0.97);
            padding: 36px 28px 32px 28px;
            border-radius: 16px;
            box-shadow: 0 10px 32px rgba(63,169,245,0.10);
            margin: 48px auto 32px auto;
        }
        .header {
            text-align: center;
            padding: 22px 0 18px 0;
            background: linear-gradient(90deg, #3fa9f5 0%, #1e3d59 100%);
            color: white;
            border-radius: 12px;
            margin-bottom: 32px;
            box-shadow: 0 4px 18px rgba(63,169,245,0.08);
        }
        .header h1 {
            margin-bottom: 8px;
            font-size: 2em;
            letter-spacing: 1px;
        }
        .header p {
            font-size: 1.1em;
            color: #eaf6ff;
        }
        .main-menu-cards {
            display: flex;
            gap: 22px;
            justify-content: center;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }
        .menu-card {
            background: #fff;
            color: #1e3d59;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(63,169,245,0.10);
            padding: 28px 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-weight: 600;
            font-size: 17px;
            text-decoration: none;
            cursor: pointer;
            transition: box-shadow 0.2s, background 0.2s, color 0.2s, transform 0.2s;
            min-width: 140px;
            min-height: 100px;
            border: 2px solid #f5f5f5;
            margin-bottom: 10px;
        }
        .menu-card:hover {
            background: #3fa9f5;
            color: #fff;
            box-shadow: 0 8px 24px rgba(63,169,245,0.13);
            border-color: #3fa9f5;
            transform: translateY(-5px) scale(1.04);
        }
        .menu-icon {
            font-size: 2.2em;
            margin-bottom: 10px;
        }
        .menu-card.logout-link {
            background: #e74c3c;
            color: #fff;
            border-color: #e74c3c;
        }
        .menu-card.logout-link:hover {
            background: #c0392b;
            border-color: #c0392b;
        }
        .description {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 15px;
            color: #444;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fafdff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(63,169,245,0.06);
            margin-top: 18px;
        }
        th, td {
            padding: 14px 13px;
            text-align: center;
        }
        th {
            background: #3fa9f5;
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #e3f1fb;
        }
        tr:nth-child(even) {
            background: #f1f7fa;
        }
        tr:hover {
            background: #e6f4fb;
        }
        .aksi a {
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            margin-right: 8px;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
            color: #fff;
            display: inline-block;
        }
        .aksi a:first-child {
            background-color: #2196F3;
        }
        .aksi a:first-child:hover {
            background-color: #1769aa;
        }
        .aksi .hapus {
            background-color: #e74c3c;
        }
        .aksi .hapus:hover {
            background-color: #c0392b;
        }
        .add-button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 22px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin: 5px;
            display: inline-block;
            font-size: 16px;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(76,175,80,0.07);
        }
        .add-button:hover {
            background-color: #388E3C;
            box-shadow: 0 4px 16px rgba(76,175,80,0.13);
        }
        .error {
            color: #e74c3c;
            background: #fff0f0;
            border-radius: 6px;
            padding: 8px 0;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .login-form {
            text-align: center;
            margin-top: 30px;
        }
        .login-form input {
            display: block;
            width: 300px;
            margin: 12px auto;
            padding: 12px;
            font-size: 1em;
            border-radius: 8px;
            border: 1px solid #b5c6d6;
            background: #f5f8fa;
            transition: border 0.2s;
        }
        .login-form input:focus {
            border: 1.5px solid #3fa9f5;
            outline: none;
            background: #fafdff;
        }
        .login-form button {
            background-color: #3fa9f5;
            color: white;
            padding: 12px 0;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.1em;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(63,169,245,0.07);
        }
        .login-form button:hover {
            background-color: #1e3d59;
        }
        @media (max-width: 700px) {
            .container { padding: 10px 2vw; }
            .main-menu-cards { flex-direction: column; gap: 12px; }
            .menu-card { min-width: 90vw; }
            th, td { padding: 8px 4px; font-size: 13px; }
            .header { font-size: 1.1em; }
            .login-form input { width: 90vw; }
        }
    </style>
</head>
<body>
<div class="container">
    <?php if (!isset($_SESSION["login"])): ?>
        <div class="header">
            <h1>Login</h1>
            <p>Hallo, selamat datang di Toko Sepatu!</p>
        </div>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" class="login-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="sandi" placeholder="Sandi" required>
            <button type="submit">Login</button>
        </form>
        <p style="text-align:center; margin-top: 10px;">
            Belum punya akun? <a href="register.php" style="color:#3fa9f5;font-weight:600;text-decoration:none;">Daftar di sini</a>
        </p>
    <?php else: ?>
        <div class="header">
            <h1>Selamat Datang di Sistem Manajemen Toko Sepatu</h1>
            <p>Di sini Anda dapat melihat, menambah, memperbarui, dan menghapus data sepatu dengan mudah.</p>
        </div>
        <?php if ($_SESSION["role"] === "admin"): ?>
            <div class="main-menu-cards">
                <div class="menu-card" id="show-table-btn">
                    <span class="menu-icon">📦</span>
                    <span>Data Sepatu</span>
                </div>
                <a href="laporan_penjualan.php" class="menu-card">
                    <span class="menu-icon">📊</span>
                    <span>Laporan Penjualan</span>
                </a>
                <a href="pesan.php" class="menu-card">
                    <span class="menu-icon">💬</span>
                    <span>Pesan</span>
                </a>
                <a href="manajemen_user.php" class="menu-card">
                    <span class="menu-icon">👤</span>
                    <span>Manajemen User</span>
                </a>
                <a href="?logout=true" class="menu-card logout-link">
                    <span class="menu-icon">🚪</span>
                    <span>Logout</span>
                </a>
            </div>
            <?php
            // Notifikasi stok menipis
            $stok_min = 5;
            $sepatu_menipis = array_filter($data_sepatu, function($s) use ($stok_min) {
                return $s['stok'] <= $stok_min;
            });
            if (count($sepatu_menipis) > 0): ?>
                <div style="background:#fff3cd;color:#856404;padding:12px 18px;border-radius:8px;margin-bottom:18px;border:1px solid #ffeeba;">
                    <strong>Perhatian!</strong> Stok sepatu berikut menipis:
                    <ul style="margin:8px 0 0 18px;">
                        <?php foreach ($sepatu_menipis as $s): ?>
                            <li><?= htmlspecialchars($s['nama_sepatu']) ?> (Stok: <?= $s['stok'] ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <table id="tabel-sepatu" style="display:none;">
            <caption class="description">Tabel Data Sepatu</caption>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Sepatu</th>
                    <th>Merk</th>
                    <th>Ukuran</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_sepatu as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama_sepatu']) ?></td>
                        <td><?= htmlspecialchars($row['merk']) ?></td>
                        <td><?= htmlspecialchars($row['ukuran']) ?></td>
                        <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td class="aksi">
                            <a href="child_class_edit.php?id=<?= $row['id'] ?>" style="background-color: #2196F3;">Edit</a>
                            <a href="proses_hapus.php?id=<?= $row['id'] ?>" class="hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div id="tabel-sepatu-btns" style="display:none; text-align:center; margin-top:10px;">
            <a href="child_class_tambah.php" class="add-button">➕ Tambah Data Sepatu</a>
        </div>

        <script>
        document.getElementById('show-table-btn').onclick = function(e) {
            e.preventDefault();
            var tabel = document.getElementById('tabel-sepatu');
            var btns = document.getElementById('tabel-sepatu-btns');
            if (tabel.style.display === 'none' || tabel.style.display === '') {
                tabel.style.display = '';
                btns.style.display = '';
                this.style.background = '#3fa9f5';
                this.style.color = '#fff';
            } else {
                tabel.style.display = 'none';
                btns.style.display = 'none';
                this.style.background = '#fff';
                this.style.color = '#1e3d59';
            }
        };
        </script>
    <?php endif; ?>
</div>
</body>
</html>