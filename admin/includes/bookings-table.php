<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title><?= $title ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include __DIR__ . '/sidebar.php'; ?>
<div class="admin-content">
  <h1 class="mb-4"><?= $title ?></h1>
  <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
  <div class="card"><div class="card-body table-responsive">
    <table class="table table-striped datatable">
      <thead><tr>
        <th>#</th><th>Booking #</th><th>User</th><th>Vehicle</th>
        <th>From</th><th>To</th><th>Status</th><th>Posted</th><th>Action</th>
      </tr></thead>
      <tbody>
      <?php $i=1; foreach ($rows as $b): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= (int)$b['BookingNumber'] ?></td>
          <td><?= htmlspecialchars($b['userEmail']) ?></td>
          <td><?= htmlspecialchars($b['VehicleTitle']) ?></td>
          <td><?= date('d M Y', strtotime($b['FromDate'])) ?></td>
          <td><?= date('d M Y', strtotime($b['ToDate'])) ?></td>
          <td><?= getStatusBadge($b['Status']) ?></td>
          <td><?= date('d M Y', strtotime($b['PostingDate'])) ?></td>
          <td>
            <?php if ((int)$b['Status'] !== 1): ?>
              <a href="?id=<?= $b['id'] ?>&status=1" class="btn btn-sm btn-success" onclick="return confirm('Confirm this booking?')"><i class="fas fa-check"></i></a>
            <?php endif; ?>
            <?php if ((int)$b['Status'] !== 2): ?>
              <a href="?id=<?= $b['id'] ?>&status=2" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this booking?')"><i class="fas fa-times"></i></a>
            <?php elseif ($title === 'Cancelled Bookings'): ?>
              <form method="post" class="d-inline" onsubmit="return confirm('Delete this cancelled booking permanently?');"><button type="submit" name="delete_cancelled_booking" value="<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete cancelled booking"><i class="fas fa-trash"></i></button></form>
            <?php endif; ?>
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