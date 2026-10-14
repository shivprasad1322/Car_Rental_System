<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'Home';
$currentPage = basename($_SERVER['PHP_SELF']);
$fleetPages = ['car-listing.php', 'car-details.php', 'booking.php'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Car Rental Portal — <?= htmlspecialchars($pageTitle) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITEURL ?>assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= SITEURL ?>"><span class="brand-mark"><i class="fas fa-car"></i></span><span>Car Rental <em>Portal</em></span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>" href="<?= SITEURL ?>about.php">About</a></li>
        <li class="nav-item"><a class="nav-link <?= in_array($currentPage, $fleetPages, true) ? 'active' : '' ?>" href="<?= SITEURL ?>car-listing.php">Our fleet</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>" href="<?= SITEURL ?>contact.php">Contact</a></li>
        <?php if (isLoggedIn()): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
              <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user_name']) ?>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="<?= SITEURL ?>profile.php">My profile</a></li>
              <li><a class="dropdown-item" href="<?= SITEURL ?>my-bookings.php">My Bookings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= SITEURL ?>logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item nav-cta"><a class="nav-link" href="<?= SITEURL ?>login.php"><i class="fas fa-arrow-right-to-bracket"></i> Sign in</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<?php if (!empty($_SESSION['success'])): ?>
  <div class="alert alert-success rounded-0 mb-0"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-danger rounded-0 mb-0"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>