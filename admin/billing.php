<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_due_return_bill'])) {
  if (createBillingForDueReturn($_POST['create_due_return_bill'])) {
    $_SESSION['success'] = 'Bill created for the due return.';
  } else {
    $_SESSION['error'] = 'This booking is not due for return or already has a bill.';
  }
  redirect('billing.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['billing_id'], $_POST['payment_status'])) {
    $method = $_POST['payment_method'] ?? null;
    if (updateBillingStatus($_POST['billing_id'], $_POST['payment_status'], $method)) {
        $_SESSION['success'] = 'Invoice payment status updated.';
    } else {
        $_SESSION['error'] = 'Unable to update invoice.';
    }
    redirect('billing.php');
}
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_billing_id'])) {
    if (deleteBilling($_POST['delete_billing_id'])) $_SESSION['success'] = 'Invoice deleted.';
    else $_SESSION['error'] = 'Unable to delete invoice.';
    redirect('billing.php');
  }

$rows = getBillingRows();
$totals = getBillingTotals();
$dueReturnBookings = getDueReturnBookings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Billing - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content billing-page">
  <h1 class="mb-4">Billing</h1>
  <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
  <div class="d-flex justify-content-end gap-2 mb-3"><a href="bill-designs.php" class="btn btn-outline-secondary"><i class="fas fa-palette"></i> Bill Designs</a></div>
  <?php if ($dueReturnBookings): ?>
  <div class="card border-warning mb-4"><div class="card-body">
    <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>Due Returns</h5>
    <p class="text-muted">Create bills for bookings whose return date is today or overdue.</p>
    <div class="table-responsive"><table class="table table-sm align-middle mb-0">
      <thead><tr><th>Booking</th><th>Customer</th><th>Vehicle</th><th>Return Date</th><th>Action</th></tr></thead>
      <tbody><?php foreach ($dueReturnBookings as $booking): ?><tr>
        <td>#<?= (int)$booking['BookingNumber'] ?></td>
        <td><?= htmlspecialchars($booking['FullName'] ?: $booking['userEmail']) ?></td>
        <td><?= htmlspecialchars($booking['VehicleTitle'] ?? 'Unknown vehicle') ?></td>
        <td><?= date('d M Y', strtotime($booking['ToDate'])) ?></td>
        <td><form method="post"><button type="submit" name="create_due_return_bill" value="<?= (int)$booking['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-file-invoice me-1"></i>Create Bill</button></form></td>
      </tr><?php endforeach; ?></tbody>
    </table></div>
  </div></div>
  <?php endif; ?>
  <div class="row mb-4">
    <div class="col-md-4 mb-3"><div class="card border-primary"><div class="card-body"><small class="text-muted">Total invoiced</small><h3><?= formatPrice($totals['invoiced']) ?></h3></div></div></div>
    <div class="col-md-4 mb-3"><div class="card border-success"><div class="card-body"><small class="text-muted">Paid</small><h3 class="text-success"><?= formatPrice($totals['paid']) ?></h3></div></div></div>
    <div class="col-md-4 mb-3"><div class="card border-warning"><div class="card-body"><small class="text-muted">Outstanding</small><h3 class="text-warning"><?= formatPrice($totals['unpaid']) ?></h3></div></div></div>
  </div>
  <div class="card"><div class="card-body table-responsive">
    <table class="table table-striped align-middle datatable billing-table">
      <thead><tr><th>Invoice</th><th>Booking</th><th>Customer</th><th>Vehicle No.</th><th>Vehicle</th><th>Rental</th><th>Total</th><th>Payment</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td><strong><?= htmlspecialchars($row['invoice_number']) ?></strong><br><small class="text-muted"><?= date('d M Y', strtotime($row['created_at'])) ?></small></td>
          <td><?= (int)$row['BookingNumber'] ?></td>
          <td><?= htmlspecialchars($row['FullName'] ?: $row['userEmail']) ?></td>
          <td><strong><?= htmlspecialchars($row['VehicleNumber']) ?></strong></td>
          <td><?= htmlspecialchars($row['VehicleTitle'] ?? 'Unknown vehicle') ?></td>
          <td><?= (int)$row['rental_days'] ?> day(s)<br><small><?= date('d M Y', strtotime($row['FromDate'])) ?> - <?= date('d M Y', strtotime($row['ToDate'])) ?></small></td>
          <td><?= formatPrice($row['total_amount']) ?></td>
          <td><span class="badge bg-<?= $row['payment_status'] === 'paid' ? 'success' : ($row['payment_status'] === 'void' ? 'secondary' : 'warning text-dark') ?>"><?= ucfirst($row['payment_status']) ?></span><?php if ($row['paid_at']): ?><br><small><?= date('d M Y', strtotime($row['paid_at'])) ?></small><?php endif; ?></td>
          <td>
            <form method="post" class="d-flex gap-1">
              <input type="hidden" name="billing_id" value="<?= (int)$row['id'] ?>">
              <select name="payment_status" class="form-select form-select-sm" aria-label="Payment status">
                <?php foreach (['unpaid','paid','void'] as $status): ?><option value="<?= $status ?>" <?= $row['payment_status'] === $status ? 'selected' : '' ?>><?= ucfirst($status) ?></option><?php endforeach; ?>
              </select>
              <select name="payment_method" class="form-select form-select-sm" aria-label="Payment method">
                <?php foreach (['Cash','Bank transfer','Card','Esewa','Khalti'] as $method): ?><option value="<?= $method ?>" <?= $row['payment_method'] === $method ? 'selected' : '' ?>><?= $method ?></option><?php endforeach; ?>
              </select>
              <button class="btn btn-sm btn-primary" title="Save payment status"><i class="fas fa-save"></i></button>
              <a href="print-bill.php?id=<?= (int)$row['id'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary billing-print" title="Print bill"><i class="fas fa-print"></i></a>
              <button type="submit" name="delete_billing_id" value="<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-danger billing-delete" title="Delete bill" onclick="return confirm('Delete this invoice? The booking will remain unchanged.')"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="9" class="text-center text-muted py-4">No invoices yet.</td></tr><?php endif; ?>
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