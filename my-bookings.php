<?php
$pageTitle = 'My Bookings';
require_once 'includes/header.php';
require_once 'includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
  if (cancelUserBooking($_POST['cancel_booking'], $_SESSION['user_email'])) {
    $_SESSION['success'] = 'Booking cancelled.';
  } else {
    $_SESSION['error'] = 'That booking could not be cancelled.';
  }
  redirect(SITEURL . 'my-bookings.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cancelled_booking'])) {
  if (deleteCancelledBooking($_POST['delete_cancelled_booking'], $_SESSION['user_email'])) {
    $_SESSION['success'] = 'Cancelled booking deleted.';
  } else {
    $_SESSION['error'] = 'That cancelled booking could not be deleted.';
  }
  redirect(SITEURL . 'my-bookings.php');
}

$bookings = getUserBookings($_SESSION['user_email']);
$activeCount = count(array_filter($bookings, static fn($booking) => (int)$booking['Status'] !== 2));
$confirmedCount = count(array_filter($bookings, static fn($booking) => (int)$booking['Status'] === 1));
?>

<main class="account-page">
  <div class="container py-5">
    <div class="account-layout">
      <?php require 'includes/sidebar.php'; ?>
      <section class="account-content">
        <div class="account-heading">
          <div><span class="eyebrow">Your journeys</span><h1>My bookings</h1><p>Keep every pickup, return, and route in view.</p></div>
          <a class="btn btn-primary" href="<?= SITEURL ?>car-listing.php"><i class="fas fa-plus me-2"></i>Book a car</a>
        </div>

        <div class="account-stats">
          <div><span>Total trips</span><strong><?= count($bookings) ?></strong></div>
          <div><span>Active bookings</span><strong><?= $activeCount ?></strong></div>
          <div><span>Confirmed</span><strong><?= $confirmedCount ?></strong></div>
        </div>

        <?php if (empty($bookings)): ?>
          <div class="account-empty"><i class="fas fa-road"></i><h2>Your next route starts here.</h2><p>You have no bookings yet. Find a car that fits the journey.</p><a href="<?= SITEURL ?>car-listing.php" class="btn btn-primary">Browse our fleet</a></div>
        <?php else: ?>
          <div class="booking-toolbar"><strong><?= count($bookings) ?> <?= count($bookings) === 1 ? 'booking' : 'bookings' ?></strong><div class="booking-filters" data-booking-filters><button type="button" class="is-active" data-booking-filter="all">All</button><button type="button" data-booking-filter="0">Pending</button><button type="button" data-booking-filter="1">Confirmed</button><button type="button" data-booking-filter="2">Cancelled</button></div></div>
          <div class="booking-list" data-booking-list>
            <?php foreach ($bookings as $b): ?>
              <article class="booking-row" data-booking-status="<?= (int)$b['Status'] ?>">
                <img src="<?= getVehicleImage($b['Vimage1']) ?>" alt="<?= htmlspecialchars($b['VehicleTitle']) ?>">
                <div class="booking-row__main"><div class="booking-row__top"><span class="eyebrow">Booking #<?= (int)$b['BookingNumber'] ?></span><?= getStatusBadge($b['Status']) ?></div><h2><?= htmlspecialchars($b['VehicleTitle']) ?></h2><p><i class="fas fa-calendar-days"></i> <?= date('d M Y', strtotime($b['FromDate'])) ?> to <?= date('d M Y', strtotime($b['ToDate'])) ?></p></div>
                <div class="booking-row__meta"><span>Requested <?= date('d M Y', strtotime($b['PostingDate'])) ?></span><?php if ((int)$b['Status'] !== 2): ?><form method="post" onsubmit="return confirm('Cancel this booking?');"><button class="btn btn-sm btn-outline-danger" name="cancel_booking" value="<?= (int)$b['id'] ?>"><i class="fas fa-xmark me-1"></i>Cancel</button></form><?php else: ?><form method="post" onsubmit="return confirm('Delete this cancelled booking permanently?');"><button class="btn btn-sm btn-outline-danger" name="delete_cancelled_booking" value="<?= (int)$b['id'] ?>"><i class="fas fa-trash me-1"></i>Delete</button></form><?php endif; ?></div>
              </article>
            <?php endforeach; ?>
          </div>
          <p class="booking-filter-empty" data-booking-filter-empty hidden>No bookings match this filter.</p>
        <?php endif; ?>
      </section>
    </div>
  </div>
</main>

<?php require_once 'includes/footer.php'; ?>