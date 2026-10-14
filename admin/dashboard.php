<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

$s = getDashboardStats();
$recent = array_slice(getAllBookings(), 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard — Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <h1 class="mb-4">Dashboard</h1>
  <div class="row mb-4">
    <?php
    $cards = [
      ['users','bg-primary','fa-users','manage-users.php'],
      ['vehicles','bg-success','fa-car','manage-vehicles.php'],
      ['bookings','bg-warning','fa-calendar-check','manage-bookings.php'],
      ['brands','bg-info','fa-tags','manage-brands.php'],
      ['subscribers','bg-secondary','fa-envelope','manage-subscribers.php'],
      ['testimonials','bg-danger','fa-star','manage-testimonials.php'],
    ];
    foreach ($cards as [$key,$cls,$icon,$url]): ?>
      <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6 mb-3">
        <a href="<?= $url ?>" class="dashboard-stat card text-white <?= $cls ?>"><div class="card-body d-flex justify-content-between">
          <div><h2><?= $s[$key] ?></h2><p class="mb-0 text-capitalize"><?= $key ?></p></div>
          <i class="fas <?= $icon ?> fa-3x opacity-50"></i>
        </div></a>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card"><div class="card-header"><h5 class="mb-0">Recent Bookings</h5></div>
    <div class="card-body table-responsive">
      <table class="table table-striped">
        <thead><tr><th>Booking #</th><th>User</th><th>Vehicle</th><th>From</th><th>To</th><th>Status</th></tr></thead>
        <tbody>
        <?php if (!$recent): ?><tr><td colspan="6" class="text-center text-muted py-4">No bookings yet.</td></tr><?php endif; ?>
        <?php foreach ($recent as $b): ?>
          <tr>
            <td><?= (int)$b['BookingNumber'] ?></td>
            <td><?= htmlspecialchars($b['FullName'] ?: $b['userEmail']) ?></td>
            <td><?= htmlspecialchars($b['VehicleTitle']) ?></td>
            <td><?= date('d M Y', strtotime($b['FromDate'])) ?></td>
            <td><?= date('d M Y', strtotime($b['ToDate'])) ?></td>
            <td><?= getStatusBadge($b['Status']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>