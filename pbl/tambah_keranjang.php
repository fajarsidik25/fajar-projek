<?php

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

// Pastikan data POST diterima
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_sepatu']) && isset($_POST['jumlah'])) {
    $id = $_POST['id_sepatu'];
    $jumlah = intval($_POST['jumlah']);

    // Validasi jumlah
    if ($jumlah < 1) {
        $jumlah = 1;
    }

    // Inisialisasi keranjang jika belum ada
    if (!isset($_SESSION["keranjang"])) {
        $_SESSION["keranjang"] = [];
    }

    // Tambahkan atau update item ke keranjang
    if (isset($_SESSION["keranjang"][$id])) {
        $_SESSION["keranjang"][$id] += $jumlah;
    } else {
        $_SESSION["keranjang"][$id] = $jumlah;
    }
}

header("Location: keranjang.php");
exit();
