<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['brand_name']);
    if ($name) {
        $conn->query("INSERT INTO tblbrands (BrandName) VALUES ('$name')");
        $_SESSION['success'] = "Brand added."; redirect(ADMINURL . 'manage-brands.php');
    }
}
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Add Brand</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <h1 class="mb-4">Add Brand</h1>
  <div class="card" style="max-width:520px"><div class="card-body">
    <form method="post">
      <div class="mb-3"><label class="form-label">Brand Name</label>
        <input name="brand_name" class="form-control" required></div>
      <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
      <a href="manage-brands.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>