<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $name = sanitize($_POST['page_name']);
    $detail = sanitize($_POST['detail']);
    $conn->query("UPDATE tblpages SET PageName='$name', detail='$detail' WHERE id=$id");
    $_SESSION['success']="Page updated."; redirect(ADMINURL.'manage-pages.php');
}
$pages = $conn->query("SELECT * FROM tblpages ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Manage Pages</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <h1 class="mb-4">Manage Pages</h1>
  <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
  <?php foreach ($pages as $p): ?>
    <div class="card mb-3">
      <div class="card-header bg-light">
        <strong><?= htmlspecialchars($p['PageName']) ?></strong>
        <span class="text-muted small ms-2">(<?= htmlspecialchars($p['type']) ?>)</span>
      </div>
      <div class="card-body">
        <form method="post">
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <div class="mb-3">
            <label class="form-label">Page Name</label>
            <input name="page_name" class="form-control" value="<?= htmlspecialchars($p['PageName']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="detail" rows="6" class="form-control"><?= htmlspecialchars($p['detail']) ?></textarea>
          </div>
          <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>