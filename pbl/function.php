<?php 

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Fungsi koneksi ke database
 */
function dbconnect($host, $user, $pass, $dbname) {
    $conn = mysqli_connect($host, $user, $pass, $dbname);
    if (!$conn) {
        return [null, mysqli_connect_error()];
    }
    return [$conn, "OK"];
}

/**
 * Fungsi menjalankan query SQL
 */
function run_query($q) { 
    global $conn; 
    $result = mysqli_query($conn, $q); 
    return $result; 
} 

/**
 * Fungsi mengambil hasil query
 */
function fetch($rs) { 
    return mysqli_fetch_assoc($rs); 
} 
?>
