<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit();
}
require_once "db_config.php";

// Ambil data user
$sql = "SELECT id, username, role, status FROM login ORDER BY role, username";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manajemen User</title>
    <style>
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f5f8fa; 
        }
        .container { 
            max-width: 800px; 
            margin: 40px auto; 
            background: #fff; 
            padding: 36px 32px 28px 32px; 
            border-radius: 16px; 
            box-shadow: 0 6px 24px rgba(63,169,245,0.10); 
        }
        h2 { 
            color: #1e3d59; 
            margin-bottom: 18px;
            text-align: center;
        }
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0; 
            margin-top: 18px; 
            background: #fafdff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(63,169,245,0.06);
        }
        th, td { 
            padding: 13px 12px; 
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
        .aksi {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .toggle-status-btn {
            border: none;
            color: #fff;
            padding: 7px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(63,169,245,0.07);
        }
        .toggle-status-btn[data-status="aktif"] {
            background: #4CAF50;
        }
        .toggle-status-btn[data-status="blokir"] {
            background: #f39c12;
        }
        .toggle-status-btn:hover {
            filter: brightness(0.95);
            box-shadow: 0 4px 16px rgba(63,169,245,0.13);
        }
        .hapus {
            background: #e74c3c;
            color: #fff;
            padding: 7px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(231,76,60,0.07);
        }
        .hapus:hover {
            background: #c0392b;
        }
        .back-link { 
            display: inline-block; 
            margin-top: 22px; 
            color: #3fa9f5; 
            text-decoration: none; 
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .container { padding: 12px 2vw; }
            th, td { padding: 8px 4px; font-size: 13px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Manajemen User</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['role'] ?></td>
                <td class="aksi">
                    <button 
                        class="edit toggle-status-btn" 
                        data-id="<?= $row['id'] ?>" 
                        data-status="<?= $row['status'] ?>"
                        style="background:<?= $row['status']=='blokir' ? '#f39c12' : '#4CAF50' ?>;">
                        <?= $row['status']=='blokir' ? 'Aktifkan' : 'Blokir' ?>
                    </button>
                    <a href="hapus_user.php?id=<?= $row['id'] ?>" class="hapus" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">Belum ada user.</td></tr>
        <?php endif; ?>
    </table>
    <a href="index.php" class="back-link">&larr; Kembali ke Dashboard</a>
</div>
<script>
document.querySelectorAll('.toggle-status-btn').forEach(function(btn) {
    btn.onclick = function() {
        var id = this.getAttribute('data-id');
        var status = this.getAttribute('data-status');
        var btnEl = this;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'toggle_status_user.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status == 200) {
                // Toggle label dan warna
                if (btnEl.getAttribute('data-status') === 'aktif') {
                    btnEl.textContent = 'Aktifkan';
                    btnEl.setAttribute('data-status', 'blokir');
                    btnEl.style.background = '#f39c12';
                } else {
                    btnEl.textContent = 'Blokir';
                    btnEl.setAttribute('data-status', 'aktif');
                    btnEl.style.background = '#4CAF50';
                }
            }
        };
        xhr.send('id=' + id + '&status=' + status);
    };
});
</script>
</body>
</html>