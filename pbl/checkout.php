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

$items = [];
$total = 0;
$ids = implode(',', array_keys($_SESSION["keranjang"]));

$query = "SELECT * FROM sepatu WHERE id IN ($ids)";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $jumlah = $_SESSION["keranjang"][$id]['jumlah'];
    $row['jumlah'] = $jumlah;
    $row['subtotal'] = $jumlah * $row['harga'];
    $items[] = $row;
    $total += $row['subtotal'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #2ecc71; color: white; }
        .total { text-align: right; font-weight: bold; margin-top: 20px; font-size: 1.2em; }
        .metode label { display: inline-block; margin: 10px; font-size: 16px; cursor: pointer; }
        #nomorPembayaran {
            margin-top: 20px;
            display: none;
            text-align: center;
        }
        #nomorPembayaran input {
            font-size: 16px;
            padding: 8px;
            width: 250px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 6px;
            user-select: all;
        }
        #btnCopy {
            margin-left: 10px;
            padding: 8px 15px;
            cursor: pointer;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
        }
        #btnCopy:hover {
            background: #2980b9;
        }
        .popup-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            /* display: flex; */
            align-items: center;
            justify-content: center;
        }
        .popup-box {
            background: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        .popup-box h3 { margin-bottom: 15px; }
        .popup-box button {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            margin: 5px;
        }
        #btnConfirm { background: #27ae60; color: white; }
        #btnCancel { background: #e74c3c; color: white; }

            .back-link {
    display: inline-block;
    margin-top: 20px;
    color: #3498db;
    text-decoration: none;
    font-weight: 600;
}

.back-link:hover {
    color: #2980b9;
}
    </style>
</head>
<body>
<div class="container">
    <h2>Checkout</h2>
    <table>
        <tr>
            <th>Nama Sepatu</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['nama_sepatu']) ?></td>
            <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
            <td><?= $item['jumlah'] ?></td>
            <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p class="total">Total: Rp <?= number_format($total, 0, ',', '.') ?></p>

    <form id="formPembayaran" method="POST" action="proses_pembayaran.php" style="text-align:center; margin-top:30px;">
        <div class="metode">
            <label><input type="radio" name="metode_pembayaran" value="dana"> Dana</label>
            <label><input type="radio" name="metode_pembayaran" value="ovo"> OVO</label>
            <label><input type="radio" name="metode_pembayaran" value="gopay"> GoPay</label>
        </div>

        <div id="nomorPembayaran">
            <p>Nomor Pembayaran:</p>
            <input type="text" id="nomorFixed" readonly value="083873159565" />
            <button type="button" id="btnCopy">Salin Nomor</button>
        </div>

        <br>
        <button type="submit" style="padding:10px 30px; background:#27ae60; color:#fff; border:none; border-radius:8px; cursor:pointer;">
            Bayar Sekarang
        </button>
    </form>
    <a href="keranjang.php" class="back-link">⬅ Kembali ke Daftar Sepatu</a>
</div>

<!-- Popup -->
<div id="popup" class="popup-backdrop">
    <div class="popup-box">
        <h3 id="popupTitle">Konfirmasi Pembayaran</h3>
        <p id="popupMessage">Apakah Anda yakin ingin melanjutkan pembayaran?</p>
        <button id="btnConfirm">Ya</button>
        <button id="btnCancel">Batal</button>
    </div>
</div>

<script>
    const metodeRadios = document.querySelectorAll('input[name="metode_pembayaran"]');
    const nomorPembayaranDiv = document.getElementById('nomorPembayaran');
    const nomorFixedInput = document.getElementById('nomorFixed');
    const btnCopy = document.getElementById('btnCopy');

    metodeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            nomorPembayaranDiv.style.display = 'block';
            // Nomor tetap, bisa disesuaikan jika ingin beda per metode
            nomorFixedInput.value = "083873159565";
        });
    });

    btnCopy.addEventListener('click', () => {
        nomorFixedInput.select();
        nomorFixedInput.setSelectionRange(0, 99999);
        document.execCommand('copy');
        alert('Nomor pembayaran berhasil disalin: ' + nomorFixedInput.value);
    });

    const form = document.getElementById('formPembayaran');
    const popup = document.getElementById('popup');
    const popupTitle = document.getElementById('popupTitle');
    const popupMessage = document.getElementById('popupMessage');
    const btnConfirm = document.getElementById('btnConfirm');
    const btnCancel = document.getElementById('btnCancel');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const selected = document.querySelector('input[name="metode_pembayaran"]:checked');
        if (!selected) {
            alert('Silakan pilih metode pembayaran terlebih dahulu.');
            return;
        }

        // Popup message sesuai metode
        let metode = selected.value;
        let pesan = '';
        switch (metode) {
            case 'dana':
                pesan = 'Anda akan membayar menggunakan Dana. Lanjutkan?';
                break;
            case 'ovo':
                pesan = 'Anda akan membayar menggunakan OVO. Lanjutkan?';
                break;
            case 'gopay':
                pesan = 'Anda akan membayar menggunakan GoPay. Lanjutkan?';
                break;
            default:
                pesan = 'Apakah Anda yakin ingin melanjutkan pembayaran?';
        }

        popupTitle.textContent = 'Konfirmasi ' + metode.toUpperCase();
        popupMessage.textContent = pesan;
        popup.style.display = 'flex';
    });

    btnCancel.addEventListener('click', () => {
        popup.style.display = 'none';
    });

    btnConfirm.addEventListener('click', () => {
        popup.style.display = 'none';
        form.submit();
    });
</script>
</body>
</html>
