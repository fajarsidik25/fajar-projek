<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}
require_once "db_config.php";

$username = $_SESSION["username"];
$role = $_SESSION["role"];

// Kirim pesan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pesan"])) {
    $pesan = trim($_POST["pesan"]);
    if ($pesan !== "") {
        // Admin kirim ke user, user kirim ke admin
        $penerima = ($role === "admin") ? $_POST["penerima"] : "admin";
        $stmt = $conn->prepare("INSERT INTO pesan (pengirim, penerima, pesan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $penerima, $pesan);
        $stmt->execute();
    }
}

// Ambil daftar user (untuk admin memilih penerima)
$daftar_user = [];
if ($role === "admin") {
    $res = $conn->query("SELECT username FROM login WHERE role != 'admin'");
    while ($row = $res->fetch_assoc()) {
        $daftar_user[] = $row["username"];
    }
}

// Ambil pesan terkait user/admin
if ($role === "admin") {
    // Admin: tampilkan semua pesan
    $sql = "SELECT * FROM pesan ORDER BY waktu DESC";
} else {
    // User: tampilkan pesan yang terkait user/admin
    $sql = "SELECT * FROM pesan WHERE (pengirim='$username' AND penerima='admin') OR (pengirim='admin' AND penerima='$username') ORDER BY waktu DESC";
}
$pesan_list = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pesan</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f8fa; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; padding: 32px 24px 24px 24px; border-radius: 16px; box-shadow: 0 6px 24px rgba(63,169,245,0.10);}
        h2 { color: #1e3d59; text-align: center; }
        .pesan-list { max-height: 340px; overflow-y: auto; margin-bottom: 18px; border-radius: 8px; border: 1px solid #e3f1fb; background: #fafdff; padding: 12px;}
        .pesan-item { margin-bottom: 14px; }
        .pesan-item.me { text-align: right; }
        .pesan-item .isi { display: inline-block; padding: 10px 16px; border-radius: 12px; background: #3fa9f5; color: #fff; margin-bottom: 2px;}
        .pesan-item.me .isi { background: #1e3d59; }
        .pesan-item .meta { font-size: 12px; color: #888; margin-top: 2px;}
        form { display: flex; gap: 10px; margin-top: 12px;}
        textarea { flex: 1; border-radius: 8px; border: 1.5px solid #b5c6d6; padding: 10px; font-size: 15px; resize: none;}
        button { background: #3fa9f5; color: #fff; border: none; border-radius: 8px; padding: 10px 18px; font-weight: 600; font-size: 15px; cursor: pointer; transition: background 0.2s;}
        button:hover { background: #1e3d59; }
        select { border-radius: 8px; padding: 7px 10px; font-size: 15px; border: 1.5px solid #b5c6d6;}
        .back-link { display: inline-block; margin-top: 18px; color: #3fa9f5; text-decoration: none; font-weight: 600;}
        .back-link:hover { text-decoration: underline;}
    </style>
</head>
<body>
<div class="container">
    <h2>Pesan <?= $role === "admin" ? "Admin & User" : "" ?></h2>
    <div class="pesan-list">
        <?php if ($pesan_list && $pesan_list->num_rows > 0): ?>
            <?php while($p = $pesan_list->fetch_assoc()): ?>
                <div class="pesan-item<?= $p['pengirim'] === $username ? ' me' : '' ?>">
                    <div class="isi"><?= htmlspecialchars($p['pesan']) ?></div>
                    <div class="meta">
                        <?= $p['pengirim'] ?> &bull; <?= date('d/m/Y H:i', strtotime($p['waktu'])) ?>
                        <?php if ($role === "admin"): ?>
                            &rarr; <?= $p['penerima'] ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="color:#888;text-align:center;">Belum ada pesan.</div>
        <?php endif; ?>
    </div>
    <form method="post">
        <?php if ($role === "admin"): ?>
            <select name="penerima" required>
                <option value="">-- Pilih User --</option>
                <?php foreach ($daftar_user as $u): ?>
                    <option value="<?= htmlspecialchars($u) ?>"><?= htmlspecialchars($u) ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <textarea name="pesan" placeholder="Tulis pesan..." required rows="2"></textarea>
        <button type="submit">Kirim</button>
    </form>
    <a href="index.php" class="back-link">&larr; Kembali</a>
</div>
</body>
</html>