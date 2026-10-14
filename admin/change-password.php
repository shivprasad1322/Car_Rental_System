<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cur = $_POST['current'];
    $new = $_POST['new'];
    $cnf = $_POST['confirm'];
    $r = $conn->query("SELECT * FROM admin WHERE id=" . (int)$_SESSION['admin_id']);
    $a = $r->fetch_assoc();

    if (!password_verify($cur, $a['Password'])) {
        $_SESSION['error'] = "Current password is wrong.";
    } elseif ($new !== $cnf) {
        $_SESSION['error'] = "New passwords don't match.";
    } elseif (strlen($new) < 6) {
        $_SESSION['error'] = "Password must be ≥ 6 characters.";
    } else {
        $h = password_hash($new, PASSWORD_DEFAULT);
        $conn->query("UPDATE admin SET Password='$h' WHERE id=" . (int)$_SESSION['admin_id']);
        $_SESSION['success'] = "Password updated.";
    }
    redirect(ADMINURL . 'change-password.php');
}
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Change Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content admin-form-page">
  <div class="admin-form-page__inner">
    <h1 class="mb-4">Change Password</h1>
    <div class="card"><div class="card-body">
      <form method="post">
        <div class="mb-3"><label class="form-label">Current Password</label>
          <input type="password" name="current" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">New Password</label>
          <input type="password" name="new" class="form-control" minlength="6" required></div>
        <div class="mb-3"><label class="form-label">Confirm New Password</label>
          <input type="password" name="confirm" class="form-control" required></div>
        <button class="btn btn-primary"><i class="fas fa-key"></i> Update</button>
      </form>
    </div></div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>