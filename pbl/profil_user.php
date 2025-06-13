<?php
session_start();

// Cek login
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

// Inisialisasi data user dan password di session (default)
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "nama" => "Andi Saputra",
        "email" => "andi@example.com",
        "telepon" => "08123456789",
        "alamat" => "Jl. Merdeka No. 45, Jakarta"
    ];
}
if (!isset($_SESSION["password"])) {
    // password default '123456' (hashed)
    $_SESSION["password"] = password_hash("123456", PASSWORD_DEFAULT);
}

// Ambil data user dari session
$user = $_SESSION["user"];
$edit_mode = false;

$success = "";
$error = "";

// Tombol Edit Profil ditekan
if (isset($_POST['btn_edit'])) {
    $edit_mode = true;
}

// Proses update profil
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_simpan'])) {
    $nama = trim($_POST["nama"]);
    $email = trim($_POST["email"]);
    $telepon = trim($_POST["telepon"]);
    $alamat = trim($_POST["alamat"]);

    $_SESSION["user"]["nama"] = htmlspecialchars($nama);
    $_SESSION["user"]["email"] = htmlspecialchars($email);
    $_SESSION["user"]["telepon"] = htmlspecialchars($telepon);
    $_SESSION["user"]["alamat"] = htmlspecialchars($alamat);

    $user = $_SESSION["user"];
    $success = "Profil berhasil diperbarui.";
    $edit_mode = false;
}

// Proses ubah password
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_ubah_password'])) {
    $pass_lama = $_POST['pass_lama'] ?? '';
    $pass_baru = $_POST['pass_baru'] ?? '';
    $pass_konf = $_POST['pass_konf'] ?? '';

    if (!password_verify($pass_lama, $_SESSION["password"])) {
        $error = "Password lama salah.";
    } elseif ($pass_baru !== $pass_konf) {
        $error = "Password baru dan konfirmasi tidak cocok.";
    } elseif (strlen($pass_baru) < 6) {
        $error = "Password baru minimal 6 karakter.";
    } else {
        $_SESSION["password"] = password_hash($pass_baru, PASSWORD_DEFAULT);
        $success = "Password berhasil diubah.";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Profil Saya - Toko Sepatu</title>
<style>
:root {
    --primary-color: #1e3d59;
    --secondary-color: #f5f5f5;
    --highlight-color: #3fa9f5;
    --success-color: #27ae60;
    --danger-color: #e74c3c;
}
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--secondary-color);
    color: #333;
    margin: 0; padding: 0;
}
header {
    background-color: var(--primary-color);
    color: white;
    padding: 28px 0 22px 0;
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    letter-spacing: 1px;
    box-shadow: 0 2px 12px rgba(63,169,245,0.08);
    border-radius: 0 0 18px 18px;
}
.container {
    max-width: 520px;
    background: #fff;
    margin: 48px auto 0 auto;
    padding: 36px 32px 28px 32px;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(63,169,245,0.10);
}
form label {
    display: block;
    margin-top: 18px;
    font-weight: 600;
    color: var(--primary-color);
    letter-spacing: 0.5px;
}
form input, form textarea {
    width: 100%;
    padding: 11px 13px;
    margin-top: 7px;
    border: 1.5px solid #b5c6d6;
    border-radius: 8px;
    font-size: 16px;
    background-color: #fafdff;
    transition: border 0.2s, background 0.2s;
}
form input:focus, form textarea:focus {
    border: 1.5px solid var(--highlight-color);
    background: #f5f8fa;
    outline: none;
}
form input[readonly], form textarea[readonly] {
    background-color: #e9ecef;
    border-color: #e3e3e3;
    color: #888;
}
form textarea {
    min-height: 80px;
    resize: vertical;
}
button {
    margin-top: 28px;
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 13px 0;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    width: 100%;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
button:hover {
    background-color: var(--highlight-color);
}
.success-msg {
    margin-top: 20px;
    color: var(--success-color);
    font-weight: 600;
}
.error-msg {
    margin-top: 20px;
    color: var(--danger-color);
    font-weight: 600;
}
a.back {
    display: inline-block;
    margin-top: 30px;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}
a.back:hover {
    text-decoration: underline;
}
.section-title {
    margin-top: 40px;
    font-size: 20px;
    font-weight: 700;
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 6px;
}
</style>
</head>
<body>

<header>Profil Saya</header>

<div class="container">

    <?php if ($success): ?>
        <p class="success-msg"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Form Profil -->
    <form method="post" action="">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" 
            value="<?= htmlspecialchars($user["nama"]) ?>" 
            <?= $edit_mode ? '' : 'readonly' ?> />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" 
            value="<?= htmlspecialchars($user["email"]) ?>" 
            <?= $edit_mode ? '' : 'readonly' ?> />

        <label for="telepon">No. Telepon</label>
        <input type="tel" id="telepon" name="telepon" 
            value="<?= htmlspecialchars($user["telepon"]) ?>" 
            <?= $edit_mode ? '' : 'readonly' ?> />

        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" <?= $edit_mode ? '' : 'readonly' ?>><?= htmlspecialchars($user["alamat"]) ?></textarea>

        <?php if ($edit_mode): ?>
            <button type="submit" name="btn_simpan">Simpan Perubahan</button>
        <?php endif; ?>
    </form>

    <?php if (!$edit_mode): ?>
        <form method="post" action="" style="margin-top: 20px;">
            <button type="submit" name="btn_edit">Edit Profil</button>
        </form>
    <?php endif; ?>

    <!-- Form Ubah Password -->
    <h2 class="section-title">Ubah Password</h2>
    <form method="post" action="">
        <label for="pass_lama">Password Lama</label>
        <input type="password" id="pass_lama" name="pass_lama" required>

        <label for="pass_baru">Password Baru</label>
        <input type="password" id="pass_baru" name="pass_baru" required>

        <label for="pass_konf">Konfirmasi Password Baru</label>
        <input type="password" id="pass_konf" name="pass_konf" required>

        <button type="submit" name="btn_ubah_password">Simpan Password Baru</button>
    </form>

    <!-- Link tambahan -->
    <a href="dashboard_user.php" class="back">&larr; Kembali ke Dashboard</a><br>
    <a href="riwayat_pembelian.php" class="back">📜 Riwayat Pembelian</a><br>
    <a href="logout.php" class="back" style="color: var(--danger-color);">🚪 Logout</a>

</div>

</body>
</html>
