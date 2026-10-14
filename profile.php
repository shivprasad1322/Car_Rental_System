<?php
$pageTitle = 'My Profile';
require_once 'includes/header.php';
require_once 'includes/functions.php';
requireLogin();

$user = getUserByEmail($_SESSION['user_email']);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['delete_account'])) {
    if (deleteUserAccount($user['id'], $_SESSION['user_email'])) {
      $_SESSION = [];
      session_destroy();
      header('Location: ' . SITEURL . 'index.php');
      exit;
    }
    $errors[] = 'Account could not be deleted. Please try again.';
  }

  $name = trim($_POST['full_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $contact = trim($_POST['contact_no'] ?? '');
  $dob = trim($_POST['dob'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $city = trim($_POST['city'] ?? '');
  $country = trim($_POST['country'] ?? '');

  if (!$name) $errors[] = 'Full name is required.';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
  $existing = getUserByEmail($email);
  if ($existing && (int)$existing['id'] !== (int)$user['id']) $errors[] = 'That email is already in use.';

  if (!$errors && updateUserProfile($user['id'], $name, $email, $contact, $dob, $address, $city, $country)) {
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['success'] = 'Profile updated successfully.';
    redirect(SITEURL . 'profile.php');
  }
  if (!$errors) $errors[] = 'Profile could not be updated. Please try again.';
  $user['FullName'] = $name;
  $user['EmailId'] = $email;
  $user['ContactNo'] = $contact;
  $user['dob'] = $dob;
  $user['Address'] = $address;
  $user['City'] = $city;
  $user['Country'] = $country;
}
?>

<main class="account-page profile-page">
  <div class="container py-5">
    <div class="account-layout">
      <?php require 'includes/sidebar.php'; ?>
      <section class="account-content">
        <div class="account-heading"><div><span class="eyebrow">Your details</span><h1>My profile</h1><p>Keep your contact details current so every handoff stays easy.</p></div></div>
        <div class="profile-card">
          <div class="profile-card__intro"><span class="profile-avatar"><i class="fas fa-user"></i></span><div><h2><?= htmlspecialchars($user['FullName']) ?></h2><p>Member since <?= date('M Y', strtotime($user['RegDate'])) ?></p></div><button type="button" class="btn btn-sm btn-outline-primary profile-edit-button" data-profile-edit><i class="fas fa-pen me-1"></i>Edit profile</button><button class="btn btn-sm btn-outline-danger profile-delete-button" type="submit" name="delete_account" value="1" form="profile-form" data-delete-account><i class="fas fa-trash me-1"></i>Delete account</button></div>
          <div class="profile-card__status"><span><i class="fas fa-shield-heart"></i> Account secured</span><span><i class="fas fa-location-dot"></i> <?= htmlspecialchars($user['City'] ?: 'Your city') ?></span><span><i class="fas fa-envelope"></i> <?= htmlspecialchars($user['EmailId']) ?></span></div>
          <?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
          <form method="post" id="profile-form" class="profile-form" data-profile-form>
            <div class="row g-3"><div class="col-md-6"><label class="form-label" for="profile-name">Full name</label><input id="profile-name" class="form-control" name="full_name" value="<?= htmlspecialchars($user['FullName']) ?>" readonly required></div><div class="col-md-6"><label class="form-label" for="profile-email">Email address</label><input id="profile-email" class="form-control" type="email" name="email" value="<?= htmlspecialchars($user['EmailId']) ?>" readonly required></div><div class="col-md-6"><label class="form-label" for="profile-contact">Contact number</label><input id="profile-contact" class="form-control" name="contact_no" value="<?= htmlspecialchars($user['ContactNo'] ?? '') ?>" maxlength="11" readonly></div><div class="col-md-6"><label class="form-label" for="profile-dob">Date of birth</label><input id="profile-dob" class="form-control" type="date" name="dob" value="<?= htmlspecialchars($user['dob'] ?? '') ?>" readonly></div><div class="col-12"><label class="form-label" for="profile-address">Address</label><textarea id="profile-address" class="form-control" name="address" rows="2" readonly><?= htmlspecialchars($user['Address'] ?? '') ?></textarea></div><div class="col-md-6"><label class="form-label" for="profile-city">City</label><input id="profile-city" class="form-control" name="city" value="<?= htmlspecialchars($user['City'] ?? '') ?>" readonly></div><div class="col-md-6"><label class="form-label" for="profile-country">Country</label><input id="profile-country" class="form-control" name="country" value="<?= htmlspecialchars($user['Country'] ?? '') ?>" readonly></div></div>
            <div class="profile-form__actions"><div class="profile-form__buttons"><button class="btn btn-primary" type="submit" data-profile-save hidden><i class="fas fa-check me-2"></i>Save changes</button></div></div>
          </form>
        </div>
      </section>
    </div>
  </div>
</main>

<?php require_once 'includes/footer.php'; ?>