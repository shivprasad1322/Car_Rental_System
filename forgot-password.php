<?php
$pageTitle = 'Forgot Password';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$msg = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $u = getUserByEmail($email);
    if (!$u) {
        $err = "No account with that email.";
    } else {
        // Demo reset — real project should email a token link
        $new = substr(bin2hex(random_bytes(4)), 0, 8);
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $conn->query("UPDATE tblusers SET Password='$hash' WHERE EmailId='$email'");
        $msg = "A new temporary password has been set: <b>$new</b> (change after login).";
    }
}
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow"><div class="card-body p-4">
        <h3 class="text-center mb-4">Forgot Password</h3>

        <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
        <?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

        <form method="post">
          <div class="mb-3">
            <label class="form-label">Registered Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100">Send Reset</button>
        </form>
        <p class="text-center mt-3"><a href="<?= SITEURL ?>login.php">Back to login</a></p>
      </div></div>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>