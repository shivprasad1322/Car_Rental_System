<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM tblcontactusquery WHERE id=" . intval($_GET['delete']));
    $_SESSION['success']="Query deleted."; redirect(ADMINURL.'manage-contact.php');
}
if (isset($_GET['read'])) {
    $conn->query("UPDATE tblcontactusquery SET status=1 WHERE id=" . intval($_GET['read']));
    redirect(ADMINURL.'manage-contact.php');
}
$rows = $conn->query("SELECT * FROM tblcontactusquery ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Contact Queries</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <h1 class="mb-4">Contact Queries</h1>
  <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
  <div class="card"><div class="card-body table-responsive">
    <table class="table table-striped datatable">
      <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Message</th><th>Status</th><th>Action</th></tr></thead>
      <tbody>
      <?php $i=1; foreach ($rows as $r): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= htmlspecialchars($r['EmailId']) ?></td>
          <td><?= htmlspecialchars($r['ContactNo']) ?></td>
          <td><?= htmlspecialchars($r['Message']) ?></td>
          <td><?= $r['status'] ? '<span class="badge bg-success">Read</span>' : '<span class="badge bg-warning text-dark">Unread</span>' ?></td>
          <td>
            <?php if (!$r['status']): ?>
              <a href="?read=<?= $r['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
            <?php endif; ?>
            <a href="?delete=<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
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