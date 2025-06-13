<?php
include 'db_config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    $kode_akses = $_POST['kode_akses'];

    if ($kode_akses !== 'RAHASIA123') {
        $message = "Kode akses salah!";
    } else if ($password !== $konfirmasi_password) {
        $message = "Password dan konfirmasi tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $query = "SELECT * FROM login WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username sudah digunakan!";
        } else {
            // Simpan user baru ke tabel login dengan role 'admin'
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO login (username, password, role) VALUES (?, ?, 'admin')";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("ss", $username, $hashed_password);
            $stmt->execute();

            header("Location: index.php?pesan=admin_berhasil_didaftarkan");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .register-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #e67e22;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #d35400;    
            
        }

        .message {
            text-align: center;
            color: red;
            margin-top: 10px;
        }

        .link-login {
            text-align: center;
            margin-top: 20px;
        }

        .link-login a {
            color: #e67e22;
            text-decoration: none;
        }

        .link-login a:hover {
            text-decoration: underline;
        }
        .back-link {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: bold;
            color: #d35400;
            padding: 20px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registrasi Admin</h2>
        <a href="index.php" class="back-link">&larr; Kembali ke login</a>
        <?php if (!empty($message)): ?>
            <p class="message"><?= $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Konfirmasi Password:</label>
            <input type="password" name="konfirmasi_password" required>

            <label>Kode Akses Admin:</label>
            <input type="text" name="kode_akses" required>

            <input type="submit" value="Daftar sebagai Admin">
        </form>

        <div class="link-login">
            Sudah punya akun? <a href="index.php">Login di sini</a>
        </div>
    </div>
</body>
</html>
