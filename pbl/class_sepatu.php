<?php
class cls_sepatu {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function show_data() {
        $result = $this->conn->query("SELECT * FROM sepatu");

        if (!$result) {
            die("Query error: " . $this->conn->error);
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function insert_data($nama, $merk, $ukuran, $harga, $stok, $gambar) {
        $stmt = $this->conn->prepare("INSERT INTO sepatu (nama_sepatu, merk, ukuran, harga, stok, gambar) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Query prepare gagal: " . $this->conn->error);
        }
        $stmt->bind_param("sssiss", $nama, $merk, $ukuran, $harga, $stok, $gambar);
        return $stmt->execute();

        
    }

    public function get_by_id($id) {
        $query = "SELECT * FROM sepatu WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query prepare gagal: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

public function update_data($id, $nama, $merk, $ukuran, $harga, $stok, $gambar)
{
    $stmt = $this->conn->prepare("UPDATE sepatu SET nama_sepatu=?, merk=?, ukuran=?, harga=?, stok=?, gambar=? WHERE id=?");

    // Perbaiki urutan tipe data: s (string), s, i, i, i, s, i
    $stmt->bind_param("ssiiisi", $nama, $merk, $ukuran, $harga, $stok, $gambar, $id);

    if (!$stmt->execute()) {
        echo "Gagal update: " . $stmt->error;
        return false;
    }

    return true;
}

    public function delete_data($id) {
        $stmt = $this->conn->prepare("DELETE FROM sepatu WHERE id = ?");
        if (!$stmt) {
            die("Query prepare gagal: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
