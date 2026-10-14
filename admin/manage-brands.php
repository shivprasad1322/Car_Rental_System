<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM tblbrands WHERE id=" . intval($_GET['delete']));
    $_SESSION['success'] = "Brand deleted."; redirect(ADMINURL . 'manage-brands.php');
}
$brands = getAllBrands();
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>Manage Brands</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <div class="d-flex justify-content-between mb-4">
    <h1>Manage Brands</h1>
    <a href="create-brand.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Brand</a>
  </div>
  <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
  <div class="card"><div class="card-body table-responsive">
    <table class="table table-striped datatable">
      <thead><tr><th>#</th><th>Brand Name</th><th>Created</th><th>Updated</th><th>Action</th></tr></thead>
      <tbody>
      <?php $i=1; foreach ($brands as $b): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($b['BrandName']) ?></td>
          <td><?= date('d M Y H:i', strtotime($b['CreationDate'])) ?></td>
          <td><?= $b['UpdationDate'] ? date('d M Y H:i', strtotime($b['UpdationDate'])) : '—' ?></td>
          <td>
            <a href="edit-brand.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
            <a href="?delete=<?= $b['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div></div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>$('.datatable').DataTable();</script>
</body></html>