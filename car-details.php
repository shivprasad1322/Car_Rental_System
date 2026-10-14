<?php
$pageTitle = 'Car Details';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$v  = getVehicleById($id);
if (!$v) { $_SESSION['error'] = "Vehicle not found"; redirect(SITEURL.'car-listing.php'); }

// --- Handle Booking ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    if (!isLoggedIn()) {
        $_SESSION['error'] = "Please login to book a vehicle.";
        redirect(SITEURL . 'login.php');
    }
    $from = sanitize($_POST['from_date']);
    $to   = sanitize($_POST['to_date']);
    $msg  = sanitize($_POST['message'] ?? '');

    if (strtotime($from) >= strtotime($to)) {
        $_SESSION['error'] = "Return date must be after pickup date.";
    } elseif (strtotime($from) < strtotime(date('Y-m-d'))) {
        $_SESSION['error'] = "Pickup date can't be in the past.";
    } elseif (!isVehicleAvailable($id, $from, $to)) {
        $_SESSION['error'] = "Vehicle is not available for those dates.";
    } else {
        $bn   = generateBookingNumber();
        $em   = $_SESSION['user_email'];
        $sql  = "INSERT INTO tblbooking (BookingNumber,userEmail,VehicleId,FromDate,ToDate,message,Status)
                 VALUES ($bn,'$em',$id,'$from','$to','$msg',0)";
        if ($conn->query($sql)) {
            $_SESSION['success'] = "Booking submitted! Booking no: $bn";
            redirect(SITEURL . 'my-bookings.php');
        } else {
            $_SESSION['error'] = "Booking failed. Try again.";
        }
    }
    redirect(SITEURL . "car-details.php?id=$id");
}
?>

<div class="container py-5">
  <div class="row">
    <div class="col-md-8">
      <div id="gal" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php
          $imgs = array_filter([$v['Vimage1'],$v['Vimage2'],$v['Vimage3'],$v['Vimage4'],$v['Vimage5']]);
          if (empty($imgs)) $imgs = ['no-image.jpg'];
          $first = true;
          foreach ($imgs as $img): ?>
            <div class="carousel-item <?= $first?'active':'' ?>">
              <img src="<?= getVehicleImage($img) ?>" class="d-block w-100"
                   style="height:400px;object-fit:cover;" alt="">
            </div>
          <?php $first=false; endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#gal" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#gal" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span></button>
      </div>

      <div class="card mb-4"><div class="card-body">
        <h2><?= htmlspecialchars($v['VehicleTitle']) ?></h2>
        <p class="text-muted">
          <i class="fas fa-car"></i> <?= htmlspecialchars($v['BrandName']) ?> |
          <i class="fas fa-gas-pump"></i> <?= htmlspecialchars($v['FuelType']) ?> |
          <i class="fas fa-calendar"></i> <?= (int)$v['ModelYear'] ?> |
          <i class="fas fa-users"></i> <?= (int)$v['SeatingCapacity'] ?> Seats
        </p>
        <h3 class="text-primary"><?= formatPrice($v['PricePerDay']) ?>/day</h3>
        <hr>
        <h5>Overview</h5>
        <p><?= nl2br(htmlspecialchars($v['VehicleOverview'])) ?></p>

        <h5>Features</h5>
        <div class="row">
          <?php
          $feats = [
            'AirConditioner'=>'Air Conditioner',
            'PowerDoorLocks'=>'Power Door Locks',
            'AntiLockBrakingSystem'=>'AntiLock Braking',
            'BrakeAssist'=>'Brake Assist',
            'PowerSteering'=>'Power Steering',
            'DriverAirbag'=>'Driver Airbag',
            'PassengerAirbag'=>'Passenger Airbag',
            'PowerWindows'=>'Power Windows',
            'CDPlayer'=>'CD Player',
            'CentralLocking'=>'Central Locking',
            'CrashSensor'=>'Crash Sensor',
            'LeatherSeats'=>'Leather Seats',
          ];
          $chunks = array_chunk($feats, 4, true);
          foreach ($chunks as $col): ?>
            <div class="col-md-4"><ul class="list-unstyled">
              <?php foreach ($col as $k=>$label): ?>
                <li><?= $v[$k]
                    ? '<i class="fas fa-check text-success"></i>'
                    : '<i class="fas fa-times text-danger"></i>' ?>
                    <?= $label ?></li>
              <?php endforeach; ?>
            </ul></div>
          <?php endforeach; ?>
        </div>
      </div></div>
    </div>

    <div class="col-md-4">
      <div class="card sticky-top" style="top:20px;">
        <div class="card-header bg-primary text-white"><h5 class="mb-0">Book This Car</h5></div>
        <div class="card-body">
          <form method="post" onsubmit="return validateBookingForm(this)">
            <div class="mb-3">
              <label class="form-label">Pickup Date</label>
              <input type="date" name="from_date" class="form-control"
                     min="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Return Date</label>
              <input type="date" name="to_date" class="form-control"
                     min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Message (optional)</label>
              <textarea name="message" rows="3" class="form-control"></textarea>
            </div>
            <p class="mb-2">Price/day: <strong><?= formatPrice($v['PricePerDay']) ?></strong></p>
            <button name="book" class="btn btn-primary w-100">
              <i class="fas fa-calendar-check"></i> Book Now
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>