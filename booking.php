<?php
$pageTitle = 'Book Your Car';
require_once 'includes/header.php';
require_once 'includes/functions.php';
requireLogin();

$vehicleId = (int)($_GET['id'] ?? $_POST['vehicle_id'] ?? 0);
$vehicle = getVehicleById($vehicleId);

if (!$vehicle) {
		$_SESSION['error'] = 'Vehicle not found.';
		redirect(SITEURL . 'car-listing.php');
}

$from = $_POST['from_date'] ?? '';
$to = $_POST['to_date'] ?? '';
$message = $_POST['message'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
		$from = sanitize($from);
		$to = sanitize($to);
		$message = sanitize($message);
		$today = date('Y-m-d');

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
				$_SESSION['error'] = 'Please choose valid pickup and return dates.';
		} elseif ($from < $today) {
				$_SESSION['error'] = "Pickup date can't be in the past.";
		} elseif ($from >= $to) {
				$_SESSION['error'] = 'Return date must be after pickup date.';
		} elseif (!isVehicleAvailable($vehicleId, $from, $to)) {
				$_SESSION['error'] = 'Vehicle is not available for those dates.';
		} else {
				$bookingNumber = generateBookingNumber();
				$email = sanitize($_SESSION['user_email']);
				$sql = "INSERT INTO tblbooking (BookingNumber,userEmail,VehicleId,FromDate,ToDate,message,Status)
								VALUES ($bookingNumber,'$email',$vehicleId,'$from','$to','$message',0)";

				if ($conn->query($sql)) {
						$_SESSION['success'] = "Booking submitted! Booking no: $bookingNumber";
						redirect(SITEURL . 'my-bookings.php');
				}

				$_SESSION['error'] = 'Booking failed. Please try again.';
		}
}

$fromLabel = $from ? date('d M Y', strtotime($from)) : 'Choose a date';
$toLabel = $to ? date('d M Y', strtotime($to)) : 'Choose a date';
?>

<main class="container py-5 booking-page">
	<div class="row justify-content-center g-4">
		<div class="col-lg-8">
			<div class="booking-intro mb-4">
				<span class="eyebrow">Reserve your drive</span>
				<h1>Make it yours.</h1>
				<p>Tell us when you would like to take the <?= htmlspecialchars($vehicle['VehicleTitle']) ?> out.</p>
			</div>

			<div class="card booking-card">
				<div class="booking-vehicle">
					<img src="<?= getVehicleImage($vehicle['Vimage1']) ?>" alt="<?= htmlspecialchars($vehicle['VehicleTitle']) ?>">
					<div>
						<span class="eyebrow"><?= htmlspecialchars($vehicle['BrandName']) ?></span>
						<h2><?= htmlspecialchars($vehicle['VehicleTitle']) ?></h2>
						<p class="mb-0"><i class="fas fa-gas-pump"></i> <?= htmlspecialchars($vehicle['FuelType']) ?> <span class="mx-2">|</span> <i class="fas fa-users"></i> <?= (int)$vehicle['SeatingCapacity'] ?> seats</p>
					</div>
					<strong><?= formatPrice($vehicle['PricePerDay']) ?><small>/day</small></strong>
				</div>

				<div class="card-body p-4 p-md-5">
					<form method="post" onsubmit="return validateBookingForm(this)">
						<input type="hidden" name="vehicle_id" value="<?= $vehicleId ?>">
						<div class="row g-4">
							<div class="col-md-6">
								<label class="form-label" for="from_date">Pickup date</label>
								<input id="from_date" type="date" name="from_date" class="form-control" value="<?= htmlspecialchars($from) ?>" min="<?= date('Y-m-d') ?>" required>
								<small class="form-text">From: <?= htmlspecialchars($fromLabel) ?></small>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="to_date">Return date</label>
								<input id="to_date" type="date" name="to_date" class="form-control" value="<?= htmlspecialchars($to) ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
								<small class="form-text">To: <?= htmlspecialchars($toLabel) ?></small>
							</div>
							<div class="col-12">
								<label class="form-label" for="message">Notes for our team <span class="text-muted fw-normal">(optional)</span></label>
								<textarea id="message" name="message" rows="4" maxlength="255" class="form-control" placeholder="Airport pickup, child seat, or anything else we should know?"><?= htmlspecialchars($message) ?></textarea>
							</div>
							<div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 pt-2">
								<a class="text-muted" href="<?= SITEURL ?>car-details.php?id=<?= $vehicleId ?>"><i class="fas fa-arrow-left me-2"></i>Back to vehicle</a>
								<button name="book" class="btn btn-primary px-4"><i class="fas fa-calendar-check me-2"></i>Request booking</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</main>

<?php require_once 'includes/footer.php'; ?>
