<?php
$pageTitle = 'Register';
require_once 'includes/header.php';
require_once 'includes/functions.php';

if (isLoggedIn()) redirect(SITEURL . 'index.php');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['confirm_password'] ?? '';
    $ph    = sanitize($_POST['contact_no'] ?? '');
    $dob   = sanitize($_POST['dob'] ?? '');
    $addr  = sanitize($_POST['address'] ?? '');
    $city  = sanitize($_POST['city'] ?? '');
    $ctry  = sanitize($_POST['country'] ?? '');

    if (!$name)  $errors[] = "Full name required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required";
    if (strlen($pass) < 6) $errors[] = "Password must be ≥ 6 chars";
    if ($pass !== $pass2) $errors[] = "Passwords don't match";
    if (getUserByEmail($email)) $errors[] = "Email already registered";

    if (!$errors) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tblusers (FullName,EmailId,Password,ContactNo,dob,Address,City,Country)
                VALUES ('$name','$email','$hash','$ph','$dob','$addr','$city','$ctry')";
        if ($conn->query($sql)) {
            $_SESSION['success'] = "Registration successful. Please login.";
            redirect(SITEURL . 'login.php');
        } else {
            $errors[] = "Registration failed: " . $conn->error;
        }
    }
}
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow"><div class="card-body p-4">
        <h3 class="text-center mb-4">Create Account</h3>
        <?php if ($errors): ?>
          <div class="alert alert-danger"><ul class="mb-0">
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
          </ul></div>
        <?php endif; ?>
        <form method="post">
          <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Full Name *</label>
              <input name="full_name" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Email *</label>
              <input type="email" name="email" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Password *</label>
              <input type="password" name="password" class="form-control" minlength="6" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Confirm *</label>
              <input type="password" name="confirm_password" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Contact No *</label>
              <input name="contact_no" class="form-control" maxlength="11" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">DOB</label>
              <input type="date" name="dob" class="form-control"></div>
            <div class="col-12 mb-3"><label class="form-label">Address</label>
              <textarea name="address" rows="2" class="form-control"></textarea></div>
            <div class="col-md-6 mb-3"><label class="form-label">City</label>
              <input name="city" class="form-control"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Country</label>
              <input name="country" class="form-control"></div>
          </div>
          <button class="btn btn-primary w-100">Register</button>
        </form>
        <p class="text-center mt-3">Already registered? <a href="<?= SITEURL ?>login.php">Login</a></p>
      </div></div>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>