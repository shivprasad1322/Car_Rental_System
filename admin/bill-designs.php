<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_design_id'])) {
    $designs = getBillDesigns();
    if (count($designs) < 2) {
        $_SESSION['error'] = 'Keep at least one bill design.';
    } elseif (deleteBillDesign($_POST['delete_design_id'])) {
        $_SESSION['success'] = 'Bill design deleted.';
    } else {
        $_SESSION['error'] = 'Unable to delete bill design.';
    }
    redirect('bill-designs.php');
}

$designs = getBillDesigns();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bill Designs - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Bill Designs</h1>
  </div>
  <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
  <div class="card"><div class="card-body table-responsive">
    <p class="text-muted">The active design is used for every printed bill. Common company details can be changed without editing individual invoices.</p>
    <table class="table align-middle">
      <thead><tr><th>Design</th><th>Company</th><th>Contact</th><th>Colors</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
      <tbody>
      <?php foreach ($designs as $design): ?>
        <tr>
          <td><strong><?= htmlspecialchars($design['design_name']) ?></strong></td>
          <td><?= htmlspecialchars($design['company_name']) ?><br><small class="text-muted"><?= htmlspecialchars($design['tagline'] ?? '') ?></small></td>
          <td><?= htmlspecialchars($design['email'] ?? '') ?><br><?= htmlspecialchars($design['phone'] ?? '') ?></td>
          <td><span class="d-inline-block border rounded" style="width:22px;height:22px;background:<?= htmlspecialchars($design['primary_color']) ?>" title="Primary color"></span> <span class="d-inline-block border rounded" style="width:22px;height:22px;background:<?= htmlspecialchars($design['accent_color']) ?>" title="Accent color"></span></td>
          <td><?= $design['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
          <td class="text-end">
            <a href="edit-bill-design.php?id=<?= (int)$design['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit design"><i class="fas fa-edit"></i></a>
            <form method="post" class="d-inline" onsubmit="return confirm('Delete this bill design?')"><button name="delete_design_id" value="<?= (int)$design['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete design"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>