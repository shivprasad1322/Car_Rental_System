<?php
require_once '../includes/config.php';

if (isAdminLoggedIn()) redirect(ADMINURL . 'dashboard.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = sanitize($_POST['username']);
    $pass = $_POST['password'];
    $r = $conn->query("SELECT * FROM admin WHERE UserName='$user'");
    $a = $r->fetch_assoc();
    if ($a && password_verify($pass, $a['Password'])) {
        $_SESSION['admin_id']   = $a['id'];
        $_SESSION['admin_name'] = $a['UserName'];
        redirect(ADMINURL . 'dashboard.php');
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login — Car Rental Portal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
body{background:linear-gradient(135deg,#667eea,#764ba2);min-height:100vh;display:flex;align-items:center;justify-content:center;}
.login-card{width:100%;max-width:400px;}
</style>
</head>
<body>
<div class="login-card">
  <div class="card shadow-lg"><div class="card-body p-5">
    <h3 class="text-center mb-4"><i class="fas fa-car"></i> Admin Panel</h3>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3"><label class="form-label">Username</label>
        <input name="username" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required></div>
      <button class="btn btn-primary w-100">Login</button>
    </form>
    <p class="text-center mt-3"><a href="<?= SITEURL ?>">Back to Home</a></p>
  </div></div>
</div>
</body>
</html>