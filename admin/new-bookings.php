<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

// Change booking status
if (isset($_GET['status'], $_GET['id'])) {
    $st = (int)$_GET['status']; $id = (int)$_GET['id'];
    $conn->query("UPDATE tblbooking SET Status=$st WHERE id=$id");
        if ($st === 1) createBillingForBooking($id);
    $_SESSION['success']="Booking status updated."; redirect($_SERVER['HTTP_REFERER']);
}
$rows = getAllBookings(0);
$title = "New Bookings";
include 'includes/bookings-table.php';