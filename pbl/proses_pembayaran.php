<?php
session_start();
require_once "db_config.php";

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION["keranjang"]) || empty($_SESSION["keranjang"])) {
    echo "<h2>Keranjang kosong.</h2>";
    echo '<a href="index.php">← Kembali</a>';
    exit();
}

$metode = $_POST["metode_pembayaran"] ?? '';
$nomor = '';

// switch ($metode) {
//     case 'dana':
//         $nomor = trim($_POST['nomor_dana'] ?? '');
//         if ($nomor == '') {
//             die("Nomor DANA tidak boleh kosong.");
//         }
//         break;
//     case 'ovo':
//         $nomor = trim($_POST['nomor_ovo'] ?? '');
//         if ($nomor == '') {
//             die("Nomor OVO tidak boleh kosong.");
//         }
//         break;
//     case 'gopay':
//         $nomor = trim($_POST['id_gopay'] ?? '');
//         if ($nomor == '') {
//             die("ID GoPay tidak boleh kosong.");
//         }
//         break;
//     default:
//         die("Metode pembayaran tidak valid.");
// }

// Simpan data transaksi ke database
$user_id = $_SESSION["login"];
$total = 0;
$items = [];

foreach ($_SESSION["keranjang"] as $id => $data) {
    $jumlah = $data["jumlah"];
    $query = $conn->query("SELECT harga FROM sepatu WHERE id = $id");
    $row = $query->fetch_assoc();
    $subtotal = $row['harga'] * $jumlah;
    $total += $subtotal;
    $items[] = [
        "id_sepatu" => $id,
        "jumlah" => $jumlah,
        "subtotal" => $subtotal
    ];
}

// Simpan ke tabel transaksi (buat tabel jika belum ada)
$stmt = $conn->prepare("INSERT INTO transaksi (user_id, metode, nomor, total, tanggal) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("issi", $user_id, $metode, $nomor, $total);
$stmt->execute();
$id_transaksi = $stmt->insert_id;

// Simpan detail transaksi
foreach ($items as $item) {
    $stmt2 = $conn->prepare("INSERT INTO detail_transaksi (id_transaksi, id_sepatu, jumlah, subtotal) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $id_transaksi, $item["id_sepatu"], $item["jumlah"], $item["subtotal"]);
    $stmt2->execute();
}

// Kosongkan keranjang
unset($_SESSION["keranjang"]);

// Redirect ke halaman sukses
header("Location: sukses.php?id=" . $id_transaksi);
exit();
?>
