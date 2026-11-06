<?php
require 'koneksi.php';

// Ambil daftar kategori
$kategori = mysqli_query($koneksi, 'SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori');

$error = '';
$nama = trim($_POST['nama_produk'] ?? '');
$hargaInput = $_POST['harga'] ?? '';
$stokInput = $_POST['stok'] ?? '';
$kategoriInput = $_POST['id_kategori'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $harga = filter_var($hargaInput, FILTER_VALIDATE_FLOAT);
    $stok = filter_var($stokInput, FILTER_VALIDATE_INT);
    $idKategori = filter_var($kategoriInput, FILTER_VALIDATE_INT);

    // Validasi input
    if ($nama === '' || $harga === false || $harga <= 0 || $stok === false || $stok < 0 || $idKategori === false || $idKategori < 1) {
        $error = '❌ Isi semua data dengan benar! Harga harus positif, stok tidak boleh negatif.';
    } else {
        // Cek apakah kategori ada di database
        $cek = mysqli_prepare($koneksi, 'SELECT COUNT(*) AS jumlah FROM kategori WHERE id_kategori = ?');
        mysqli_stmt_bind_param($cek, 'i', $idKategori);
        mysqli_stmt_execute($cek);
        mysqli_stmt_bind_result($cek, $jumlahKategori);
        mysqli_stmt_fetch($cek);
        mysqli_stmt_close($cek);

        if ($jumlahKategori == 0) {
            $error = '❌ Kategori tidak ditemukan!';
        } else {
            // Simpan ke database pakai Prepared Statement
            $sql = 'INSERT INTO produk (id_kategori, nama_produk, harga, stok) VALUES (?, ?, ?, ?)';
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, 'isdi', $idKategori, $nama, $harga, $stok);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Produk</title>
</head>
<body>
    <h1>Tambah Produk Baru</h1>
    <?php if ($error !== ''): ?><p style="color:red"><?= e($error) ?></p><?php endif; ?>
    
    <form method="post">
        <p>Nama Produk: <input type="text" name="nama_produk" value="<?= e($nama) ?>" required></p>
        <p>Harga: <input type="number" name="harga" min="0.01" step="0.01" value="<?= e($hargaInput) ?>" required></p>
        <p>Stok: <input type="number" name="stok" min="0" step="1" value="<?= e($stokInput) ?>" required></p>
        <p>Kategori: 
            <select name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($item = mysqli_fetch_assoc($kategori)): ?>
                    <option value="<?= e($item['id_kategori']) ?>" 
                        <?= ($kategoriInput == $item['id_kategori']) ? 'selected' : '' ?>>
                        <?= e($item['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </p>
        <p><button type="submit">✅ Simpan Produk</button></p>
    </form>
    <p><a href="index.php">⬅️ Kembali ke Daftar</a></p>
</body>
</html>