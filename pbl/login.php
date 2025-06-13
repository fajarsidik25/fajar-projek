

<!DOCTYPE html>
<html>
<head>
    <title>Login - Toko Sepatu</title>
    <style> 
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4CAF50, #81C784);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            width: 100%;
            font-weight: bold;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login Toko Sepatu</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Sandi</label>
        <input type="password" name="sandi" required>

        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>