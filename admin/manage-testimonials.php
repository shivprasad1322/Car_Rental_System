<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if (isset($_GET['approve'])) {
    $conn->query("UPDATE tbltestimonials SET status=1 WHERE id=" . intval($_GET['approve']));
    redirect(ADMINURL.'manage-testimonials.php');
}
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM tbltestimonials WHERE id=" . intval($_GET['delete']));
    $_SESSION['success']="Testimonial deleted."; redirect(ADMINURL.'manage-testimonials.php');
}
$rows = $conn->query("SELECT * FROM tbltestimonials ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Testimonials</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <h1 class="mb-4">Testimonials</h1>
  <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
  <div class="card"><div class="card-body table-responsive">
    <table class="table table-striped datatable">
      <thead><tr><th>#</th><th>User</th><th>Testimonial</th><th>Status</th><th>Posted</th><th>Action</th></tr></thead>
      <tbody>
      <?php $i=1; foreach ($rows as $t): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($t['UserEmail']) ?></td>
          <td><?= htmlspecialchars($t['Testimonial']) ?></td>
          <td><?= $t['status'] ? '<span class="badge bg-success">Approved</span>' : '<span class="badge bg-warning text-dark">Pending</span>' ?></td>
          <td><?= date('d M Y', strtotime($t['PostingDate'])) ?></td>
          <td>
            <?php if (!$t['status']): ?>
              <a href="?approve=<?= $t['id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
            <?php endif; ?>
            <a href="?delete=<?= $t['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
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