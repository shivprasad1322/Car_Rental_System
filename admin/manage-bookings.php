<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if (isset($_GET['status'], $_GET['id'])) {
	$status = (int)$_GET['status'];
	$bookingId = (int)$_GET['id'];

	if (in_array($status, [0, 1, 2], true) && $bookingId > 0) {
		$conn->query("UPDATE tblbooking SET Status=$status WHERE id=$bookingId");
		if ($status === 1) createBillingForBooking($bookingId);
		$_SESSION['success'] = 'Booking status updated.';
	}

	redirect(ADMINURL . 'manage-bookings.php');
}

$rows = getAllBookings();
$title = 'Manage Bookings';
include 'includes/bookings-table.php';
