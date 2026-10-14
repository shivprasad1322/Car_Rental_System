<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

if (isset($_GET['status'], $_GET['id'])) {
    $st=(int)$_GET['status']; $id=(int)$_GET['id'];
    $conn->query("UPDATE tblbooking SET Status=$st WHERE id=$id");
        if ($st === 1) createBillingForBooking($id);
    $_SESSION['success']="Updated."; redirect($_SERVER['HTTP_REFERER']);
}
$rows = getAllBookings(1);
$title = "Confirmed Bookings";
include 'includes/bookings-table.php';