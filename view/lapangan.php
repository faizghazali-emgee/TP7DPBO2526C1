<?php
require_once '../config/db.php';
require_once '../class/Lapangan.php';

$database = new Database();
$db = $database->connect();
$lapangan = new Lapangan($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'create') {
            $lapangan->create($_POST['nama_lapangan'], $_POST['jenis_olahraga'], $_POST['harga_per_jam']);
        } elseif ($_POST['action'] === 'update') {
            $lapangan->update($_POST['id_lapangan'], $_POST['nama_lapangan'], $_POST['jenis_olahraga'], $_POST['harga_per_jam']);
        }
    }
    header("Location: lapangan.php");
    exit;
}

if (isset($_GET['delete'])) {
    $lapangan->delete($_GET['delete']);
    header("Location: lapangan.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $editData = $lapangan->readOne($_GET['edit']);
}

$data = $lapangan->readAll()->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h3>Kelola Data Lapangan</h3>

<form method="POST" action="lapangan.php" class="form-container">
    <input type="hidden" name="action" value="<?php echo $editData ? 'update' : 'create'; ?>">
    <?php if ($editData): ?>
        <input type="hidden" name="id_lapangan" value="<?php echo $editData['id_lapangan']; ?>">
    <?php endif; ?>

    <label>Nama Lapangan:</label><br>
    <input type="text" name="nama_lapangan" value="<?php echo $editData ? $editData['nama_lapangan'] : ''; ?>" required><br><br>

    <label>Jenis Olahraga:</label><br>
    <input type="text" name="jenis_olahraga" value="<?php echo $editData ? $editData['jenis_olahraga'] : ''; ?>" required><br><br>

    <label>Harga Per Jam (Rp):</label><br>
    <input type="number" step="0.01" name="harga_per_jam" value="<?php echo $editData ? $editData['harga_per_jam'] : ''; ?>" required><br><br>

    <button type="submit"><?php echo $editData ? 'Update' : 'Simpan'; ?></button>
    <?php if ($editData): ?>
        <a href="lapangan.php">Batal</a>
    <?php endif; ?>
</form>

<br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Lapangan</th>
            <th>Jenis Olahraga</th>
            <th>Harga/Jam</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?php echo $row['id_lapangan']; ?></td>
            <td><?php echo $row['nama_lapangan']; ?></td>
            <td><?php echo $row['jenis_olahraga']; ?></td>
            <td>Rp <?php echo number_format($row['harga_per_jam'], 0, ',', '.'); ?></td>
            <td>
                <a href="lapangan.php?edit=<?php echo $row['id_lapangan']; ?>">Edit</a> | 
                <a href="lapangan.php?delete=<?php echo $row['id_lapangan']; ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>