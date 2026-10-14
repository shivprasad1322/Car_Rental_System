<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM tblvehicles WHERE id=" . intval($_GET['delete']));
    $_SESSION['success']="Vehicle deleted."; redirect(ADMINURL.'manage-vehicles.php');
}
$vehicles = getAllVehicles();
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Manage Vehicles</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <div class="d-flex justify-content-between mb-4">
    <h1>Manage Vehicles</h1>
    <a href="add-vehicle.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Vehicle</a>
  </div>
  <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
  <div class="card"><div class="card-body table-responsive">
    <table class="table table-striped datatable">
      <thead><tr><th>#</th><th>Vehicle No.</th><th>Image</th><th>Title</th><th>Brand</th><th>Price/Day</th><th>Fuel</th><th>Year</th><th>Action</th></tr></thead>
      <tbody>
      <?php $i=1; foreach ($vehicles as $v): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><strong><?= htmlspecialchars($v['VehicleNumber']) ?></strong></td>
          <td><img src="<?= getVehicleImage($v['Vimage1']) ?>" style="width:60px;height:40px;object-fit:cover;"></td>
          <td><?= htmlspecialchars($v['VehicleTitle']) ?></td>
          <td><?= htmlspecialchars($v['BrandName']) ?></td>
          <td><?= formatPrice($v['PricePerDay']) ?></td>
          <td><?= htmlspecialchars($v['FuelType']) ?></td>
          <td><?= (int)$v['ModelYear'] ?></td>
          <td>
            <a href="edit-vehicle.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
            <a href="?delete=<?= $v['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
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