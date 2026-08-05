<?php

/**
 * TES KONEKSI SQL SERVER
 * ======================
 * Cara pakai (isi 4 nilai di bawah sesuai info dari admin/pembimbing):
 *   php test_sqlsrv.php
 *
 * Jika berhasil akan tampil versi SQL Server + daftar tabel di database.
 * File ini AMAN untuk dihapus setelah koneksi berhasil.
 */

$host      = 'IP_ATAU_HOST';      // contoh: 192.168.1.10 atau 26.6.73.87 (IP server SQL di RadminVPN)
$username  = 'USERNAME';          // contoh: sa
$password  = 'PASSWORD';          // password akun SQL Server
$database  = 'NAMA_DATABASE';     // contoh: AltiusMagang
$port      = 1433;

echo "Mencoba koneksi ke SQL Server...\n";
echo "Host: {$host}:{$port}\n";
echo "Database: {$database}\n";
echo "User: {$username}\n\n";

// Cek driver tersedia
if (!in_array('pdo_sqlsrv', PDO::getAvailableDrivers())) {
    echo "[GAGAL] Driver pdo_sqlsrv TIDAK terpasang. Jalankan 'php -m' untuk cek.\n";
    exit(1);
}

$connStr = "sqlsrv:Server={$host},{$port};Database={$database};Encrypt=no;TrustServerCertificate=1";

try {
    $pdo = new PDO($connStr, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT            => 5,
        PDO::SQLSRV_ATTR_DIRECT_QUERY => true,
    ]);

    $version = $pdo->query('SELECT @@VERSION')->fetchColumn();
    echo "[SUKSES] Terhubung ke SQL Server!\n";
    echo "Versi  : " . trim($version) . "\n\n";

    echo "Daftar tabel di database '{$database}':\n";
    $tables = $pdo->query(
        "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME"
    )->fetchAll(PDO::FETCH_COLUMN);

    if (!$tables) {
        echo "  (tidak ada tabel)\n";
    } else {
        foreach ($tables as $t) {
            echo "  - {$t}\n";
        }
    }

    // Cek khusus tabel yang dipakai aplikasi
    echo "\nCek tabel aplikasi:\n";
    foreach (['calon_karyawans', 'data_kerabats'] as $need) {
        echo in_array($need, $tables)
            ? "  [OK]   tabel '{$need}' ADA\n"
            : "  [???]  tabel '{$need}' TIDAK ADA - cek nama tabel asli di server\n";
    }

} catch (PDOException $e) {
    echo "[GAGAL] Tidak bisa terhubung.\n";
    echo "Pesan error: " . $e->getMessage() . "\n\n";
    echo "Kemungkinan penyebab:\n";
    echo " - IP/username/password/nama database salah\n";
    echo " - Server tidak bisa dijangkau (beda jaringan / firewall)\n";
    echo " - Instance SQL Server memakai port/nama berbeda\n";
    exit(1);
}
