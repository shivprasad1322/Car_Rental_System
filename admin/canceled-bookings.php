<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cancelled_booking'])) {
    if (deleteCancelledBooking($_POST['delete_cancelled_booking'])) {
        $_SESSION['success'] = 'Cancelled booking deleted.';
    } else {
        $_SESSION['error'] = 'That cancelled booking could not be deleted.';
    }
    redirect('canceled-bookings.php');
}

if (isset($_GET['status'], $_GET['id'])) {
    $st=(int)$_GET['status']; $id=(int)$_GET['id'];
    $conn->query("UPDATE tblbooking SET Status=$st WHERE id=$id");
    $_SESSION['success']="Updated."; redirect($_SERVER['HTTP_REFERER']);
}
$rows = getAllBookings(2);
$title = "Cancelled Bookings";
include 'includes/bookings-table.php';