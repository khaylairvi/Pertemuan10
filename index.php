<?php
require 'koneksi.php';

$sql = 'SELECT p.id_produk, p.nama_produk, p.harga, p.stok, k.nama_kategori
        FROM produk AS p 
        INNER JOIN kategori AS k ON k.id_kategori = p.id_kategori
        ORDER BY p.id_produk';

$hasil = mysqli_query($koneksi, $sql);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Produk</title>
</head>
<body>
    <h1>Daftar Produk</h1>
    <p><a href="tambah.php">➕ Tambah Produk Baru</a></p>
    
    <table border="1" cellpadding="8">
        <tr>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php while ($baris = mysqli_fetch_assoc($hasil)): ?>
        <tr>
            <td><?= e($baris['nama_produk']) ?></td>
            <td><?= e($baris['nama_kategori']) ?></td>
            <td>Rp <?= e(number_format((float)$baris['harga'], 0, ',', '.')) ?></td>
            <td><?= e($baris['stok']) ?></td>
            <td>
                <a href="edit.php?id=<?= e($baris['id_produk']) ?>">✏️ Edit</a> |
                <form method="post" action="hapus.php" style="display:inline">
                    <input type="hidden" name="id" value="<?= e($baris['id_produk']) ?>">
                    <button type="submit" onclick="return confirm('Yakin hapus?')">🗑️ Hapus</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <?php if (mysqli_num_rows($hasil) === 0): ?>
        <p>Belum ada produk. Silakan tambah produk baru.</p>
    <?php endif; ?>
</body>
</html>