
<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}
$pesan_terkirim = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pesan = htmlspecialchars($_POST['pesan']);
    file_put_contents('pesan_admin.txt', date('Y-m-d H:i:s')." - ".$_SESSION['username'].": $pesan\n", FILE_APPEND);
    $pesan_terkirim = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hubungi Admin</title>
    <style>
        :root {
            --primary-color: #1e3d59;
            --secondary-color: #f5f5f5;
            --highlight-color: #3fa9f5;
            --danger-color: #e74c3c;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--secondary-color);
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 60px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
        }
        h2 {
            color: var(--primary-color);
            margin-bottom: 18px;
            text-align: center;
        }
        a {
            display: inline-block;
            margin-bottom: 18px;
            color: var(--highlight-color);
            text-decoration: none;
            font-weight: 600;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
            font-size: 15px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-family: inherit;
        }
        button {
            background: var(--primary-color);
            color: #fff;
            border: none;
            padding: 10px 0;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover {
            background: var(--highlight-color);
        }
        .success {
            background: #e0f7e9;
            color: #218c5b;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
        @media (max-width: 600px) {
            .container {
                margin: 24px 8px;
                padding: 18px 8px 14px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hubungi Admin</h2>
        <a href="dashboard_user.php">&larr; Kembali ke Dashboard</a>
        <?php if($pesan_terkirim): ?>
            <div class="success">Pesan berhasil dikirim!</div>
        <?php endif; ?>
        <form method="post">
            <textarea name="pesan" required placeholder="Tulis pesan Anda di sini..."></textarea>
            <button type="submit">Kirim</button>
        </form>
    </div>
</body>
</html>