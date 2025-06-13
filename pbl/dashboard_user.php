<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Pengguna - Toko Sepatu</title>
    <style>
        :root {
            --primary-color: #1e3d59;
            --secondary-color: #f5f5f5;
            --highlight-color: #3fa9f5;
            --danger-color: #e74c3c;
            --card-gradient: linear-gradient(135deg, #3fa9f5 0%, #1e3d59 100%);
        }
        * {
            box-sizing: border-box;
            margin: 0; padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--secondary-color);
            color: #333;
            min-height: 100vh;
        }
        header {
            background: var(--primary-color);
            color: #fff;
            padding: 28px 40px 22px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.10);
            border-radius: 0 0 18px 18px;
            position: relative;
        }
        header h1 {
            font-size: 30px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px rgba(63,169,245,0.08);
        }
        .user-actions {
            display: flex;
            gap: 18px;
        }
        .user-actions a {
            background: #fff;
            color: var(--primary-color);
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.09);
            transition: background 0.3s, color 0.3s, transform 0.2s;
            white-space: nowrap;
            border: 2px solid transparent;
        }
        .user-actions a:hover {
            background: var(--highlight-color);
            color: #fff;
            transform: translateY(-2px) scale(1.04);
            border-color: #fff;
        }
        .user-actions a.logout {
            background: var(--danger-color);
            color: #fff;
            border-color: var(--danger-color);
        }
        .user-actions a.logout:hover {
            background: #c0392b;
            border-color: #c0392b;
        }
        .user-actions .icon {
            font-size: 20px;
        }
        .container {
            max-width: 1100px;
            margin: 48px auto 0 auto;
            padding: 0 20px 40px 20px;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 32px;
            margin-top: 38px;
        }
        .card {
            background: var(--card-gradient);
            color: #fff;
            padding: 38px 20px 32px 20px;
            border-radius: 18px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            transition: transform 0.22s, box-shadow 0.22s, background 0.3s;
            box-shadow: 0 8px 24px rgba(63,169,245,0.13);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border: 2px solid #fff;
        }
        .card:hover {
            background: linear-gradient(135deg, #1e3d59 0%, #3fa9f5 100%);
            transform: translateY(-7px) scale(1.04);
            box-shadow: 0 12px 32px rgba(63,169,245,0.18);
            border-color: #3fa9f5;
        }
        .icon {
            font-size: 40px;
            margin-bottom: 14px;
            filter: drop-shadow(0 2px 8px rgba(63,169,245,0.10));
        }
        @media (max-width: 700px) {
            .container { padding: 0 4vw 24px 4vw; }
            .menu-grid { gap: 18px; }
            .card { font-size: 15px; padding: 22px 8px 18px 8px; }
            header { flex-direction: column; gap: 12px; padding: 18px 10px 14px 10px; }
            header h1 { font-size: 22px; }
            .user-actions { gap: 10px; }
        }
    </style>
</head>
<body>

<header>
    <h1>selamat datang di Toko Sepatu</h1>
    <div class="user-actions">
        <a href="profil_user.php" title="Profil Saya">
            <span class="icon">👤</span> Profil
        </a>
        <a href="logout.php" class="logout" title="Logout">
            <span class="icon">🚪</span> Logout
        </a>
    </div>
</header>

<div class="container">
    <div class="menu-grid">
         <a href="lihat_sepatu.php" class="card">
        <span class="icon">👟</span>Lihat Semua Sepatu
    </a>
    <a href="keranjang.php" class="card">
        <span class="icon">🛒</span>Keranjang Belanja
    </a>
    <a href="riwayat_pembelian.php" class="card">
        <span class="icon">📜</span>Riwayat Pembelian
    </a>
    <!-- <a href="hubungi_admin.php" class="card">
        <span class="icon">✉️</span>Hubungi Admin
    </a> -->
    <a href="pesan.php" class="card">
        <span class="icon">💬</span>Pesan
    </a>
    </div>
</div>


</body>
</html>
