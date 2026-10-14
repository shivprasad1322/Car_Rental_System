<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM tblusers WHERE id=" . intval($_GET['delete']));
    $_SESSION['success']="User deleted."; redirect(ADMINURL.'manage-users.php');
}
$users = $conn->query("SELECT * FROM tblusers ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Registered Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <h1 class="mb-4">Registered Users</h1>
  <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
  <div class="card"><div class="card-body table-responsive">
    <table class="table table-striped datatable">
      <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Contact</th><th>City</th><th>Registered</th><th>Action</th></tr></thead>
      <tbody>
      <?php $i=1; foreach ($users as $u): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($u['FullName']) ?></td>
          <td><?= htmlspecialchars($u['EmailId']) ?></td>
          <td><?= htmlspecialchars($u['ContactNo']) ?></td>
          <td><?= htmlspecialchars($u['City']) ?></td>
          <td><?= date('d M Y', strtotime($u['RegDate'])) ?></td>
          <td>
            <a href="?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Delete user?')"><i class="fas fa-trash"></i></a>
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