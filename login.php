<?php
$pageTitle = 'Login';
require_once 'includes/header.php';
require_once 'includes/functions.php';

if (isLoggedIn()) redirect(SITEURL . 'index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $pass  = $_POST['password'];
    $u = getUserByEmail($email);
    if ($u && password_verify($pass, $u['Password'])) {
        $_SESSION['user_id']    = $u['id'];
        $_SESSION['user_name']  = $u['FullName'];
        $_SESSION['user_email'] = $u['EmailId'];
        $_SESSION['success']    = "Welcome back, " . $u['FullName'] . "!";
        redirect(SITEURL . 'index.php');
    } else {
        $_SESSION['error'] = "Invalid email or password";
    }
}
$emailValue = htmlspecialchars($_POST['email'] ?? '');
?>

<main class="auth-page" data-login-page>
  <div class="auth-layout">
    <section class="auth-story">
      <div class="auth-story__content"><span class="eyebrow"><i class="fas fa-key"></i> Welcome back</span><h1>Your next<br><em>mile starts here.</em></h1><p>Keep your favourite routes close, manage bookings in one place, and get back on the road faster.</p><div class="auth-story__route"><span class="auth-story__dot"></span><span></span><i class="fas fa-location-arrow"></i><small>YOUR JOURNEY / CONTINUES</small></div></div>
      <div class="auth-story__stamp"><i class="fas fa-car"></i><span>DRIVE<br>ON</span></div>
    </section>
    <section class="auth-panel">
      <div class="auth-panel__content">
        <h2>Sign in to<br><em>your account.</em></h2>
        <p class="auth-panel__intro">Pick up where you left off.</p>
        <form method="post" data-login-form>
          <div class="auth-field"><label for="login-email">Email address</label><div class="auth-input-wrap"><i class="fas fa-envelope"></i><input id="login-email" type="email" name="email" value="<?= $emailValue ?>" class="form-control" placeholder="you@example.com" autocomplete="email" required><span class="auth-input-state" data-email-state></span></div></div>
          <div class="auth-field"><div class="auth-label-row"><label for="login-password">Password</label><a href="<?= SITEURL ?>forgot-password.php">Forgot password?</a></div><div class="auth-input-wrap"><i class="fas fa-lock"></i><input id="login-password" type="password" name="password" class="form-control" placeholder="Enter your password" autocomplete="current-password" required><button type="button" class="auth-password-toggle" data-password-toggle aria-label="Show password"><i class="fas fa-eye"></i></button></div></div>
          <button class="btn btn-primary auth-submit" type="submit">Continue to portal <i class="fas fa-arrow-right"></i></button>
        </form>
        <div class="auth-divider"><span>New to the portal?</span></div>
        <a class="auth-register" href="<?= SITEURL ?>register.php">Create your account <i class="fas fa-arrow-up-right-from-square"></i></a>
      </div>
      <div class="auth-panel__footer"><span><i class="fas fa-headset"></i> Need a hand?</span><a href="<?= SITEURL ?>contact.php">Contact support</a></div>
    </section>
  </div>
</main>

<?php require_once 'includes/footer.php'; ?>