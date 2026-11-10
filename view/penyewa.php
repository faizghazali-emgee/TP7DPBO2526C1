<?php
require_once '../config/db.php';
require_once '../class/Penyewa.php';

$database = new Database();
$db = $database->connect();
$penyewa = new Penyewa($db);

// Handle POST (Create / Update / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'create') {
            $penyewa->create($_POST['nama_penyewa'], $_POST['no_hp'], $_POST['email']);
        } elseif ($_POST['action'] === 'update') {
            $penyewa->update($_POST['id_penyewa'], $_POST['nama_penyewa'], $_POST['no_hp'], $_POST['email']);
        }
    }
    header("Location: penyewa.php");
    exit;
}

if (isset($_GET['delete'])) {
    $penyewa->delete($_GET['delete']);
    header("Location: penyewa.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $editData = $penyewa->readOne($_GET['edit']);
}

$data = $penyewa->readAll()->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h3>Kelola Data Penyewa</h3>

<form method="POST" action="penyewa.php" class="form-container">
    <input type="hidden" name="action" value="<?php echo $editData ? 'update' : 'create'; ?>">
    <?php if ($editData): ?>
        <input type="hidden" name="id_penyewa" value="<?php echo $editData['id_penyewa']; ?>">
    <?php endif; ?>

    <label>Nama Penyewa:</label><br>
    <input type="text" name="nama_penyewa" value="<?php echo $editData ? $editData['nama_penyewa'] : ''; ?>" required><br><br>

    <label>No HP:</label><br>
    <input type="text" name="no_hp" value="<?php echo $editData ? $editData['no_hp'] : ''; ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo $editData ? $editData['email'] : ''; ?>" required><br><br>

    <button type="submit"><?php echo $editData ? 'Update' : 'Simpan'; ?></button>
    <?php if ($editData): ?>
        <a href="penyewa.php">Batal</a>
    <?php endif; ?>
</form>

<br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?php echo $row['id_penyewa']; ?></td>
            <td><?php echo $row['nama_penyewa']; ?></td>
            <td><?php echo $row['no_hp']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <a href="penyewa.php?edit=<?php echo $row['id_penyewa']; ?>">Edit</a> | 
                <a href="penyewa.php?delete=<?php echo $row['id_penyewa']; ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>