<?php
require_once '../config/db.php';
require_once '../class/Penyewaan.php';
require_once '../class/Penyewa.php';
require_once '../class/Lapangan.php';

$database = new Database();
$db = $database->connect();

$penyewaan = new Penyewaan($db);
$penyewa = new Penyewa($db);
$lapangan = new Lapangan($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'create') {
            $penyewaan->create($_POST['id_penyewa'], $_POST['id_lapangan'], $_POST['tgl_sewa'], $_POST['jam_mulai'], $_POST['durasi_jam']);
        } elseif ($_POST['action'] === 'update') {
            $penyewaan->update($_POST['id_sewa'], $_POST['id_penyewa'], $_POST['id_lapangan'], $_POST['tgl_sewa'], $_POST['jam_mulai'], $_POST['durasi_jam']);
        }
    }
    header("Location: penyewaan.php");
    exit;
}

if (isset($_GET['delete'])) {
    $penyewaan->delete($_GET['delete']);
    header("Location: penyewaan.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $editData = $penyewaan->readOne($_GET['edit']);
}

$listPenyewa = $penyewa->readAll()->fetchAll(PDO::FETCH_ASSOC);
$listLapangan = $lapangan->readAll()->fetchAll(PDO::FETCH_ASSOC);
$dataPenyewaan = $penyewaan->readAll()->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h3>Kelola Transaksi Peminjaman</h3>

<form method="POST" action="penyewaan.php" class="form-container">
    <input type="hidden" name="action" value="<?php echo $editData ? 'update' : 'create'; ?>">
    <?php if ($editData): ?>
        <input type="hidden" name="id_sewa" value="<?php echo $editData['id_sewa']; ?>">
    <?php endif; ?>

    <label>Penyewa:</label><br>
    <select name="id_penyewa" required>
        <option value="">-- Pilih Penyewa --</option>
        <?php foreach ($listPenyewa as $p): ?>
            <option value="<?php echo $p['id_penyewa']; ?>" <?php echo ($editData && $editData['id_penyewa'] == $p['id_penyewa']) ? 'selected' : ''; ?>>
                <?php echo $p['nama_penyewa']; ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Lapangan:</label><br>
    <select name="id_lapangan" required>
        <option value="">-- Pilih Lapangan --</option>
        <?php foreach ($listLapangan as $l): ?>
            <option value="<?php echo $l['id_lapangan']; ?>" <?php echo ($editData && $editData['id_lapangan'] == $l['id_lapangan']) ? 'selected' : ''; ?>>
                <?php echo $l['nama_lapangan'] . " (Rp " . number_format($l['harga_per_jam'], 0, ',', '.') . "/jam)"; ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Tanggal Sewa:</label><br>
    <input type="date" name="tgl_sewa" value="<?php echo $editData ? $editData['tgl_sewa'] : ''; ?>" required><br><br>

    <label>Jam Mulai:</label><br>
    <input type="time" name="jam_mulai" value="<?php echo $editData ? $editData['jam_mulai'] : ''; ?>" required><br><br>

    <label>Durasi (Jam):</label><br>
    <input type="number" name="durasi_jam" min="1" value="<?php echo $editData ? $editData['durasi_jam'] : '1'; ?>" required><br><br>

    <button type="submit"><?php echo $editData ? 'Update Transaksi' : 'Simpan Transaksi'; ?></button>
    <?php if ($editData): ?>
        <a href="penyewaan.php">Batal</a>
    <?php endif; ?>
</form>

<br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Penyewa</th>
            <th>Lapangan</th>
            <th>Tgl Sewa</th>
            <th>Jam Mulai</th>
            <th>Durasi</th>
            <th>Total Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dataPenyewaan as $row): ?>
        <tr>
            <td><?php echo $row['id_sewa']; ?></td>
            <td><?php echo $row['nama_penyewa']; ?></td>
            <td><?php echo $row['nama_lapangan']; ?></td>
            <td><?php echo $row['tgl_sewa']; ?></td>
            <td><?php echo $row['jam_mulai']; ?></td>
            <td><?php echo $row['durasi_jam']; ?> Jam</td>
            <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
            <td>
                <a href="penyewaan.php?edit=<?php echo $row['id_sewa']; ?>">Edit</a> | 
                <a href="penyewaan.php?delete=<?php echo $row['id_sewa']; ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>