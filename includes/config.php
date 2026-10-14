<?php
// ---------- Database ----------
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'car_rental_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// ---------- Timezone / Session ----------
date_default_timezone_set('Asia/Kolkata');
if (session_status() === PHP_SESSION_NONE) session_start();

// ---------- Site URLs ----------
define('SITEURL', 'http://localhost/car-rental-system/');
define('ADMINURL', SITEURL . 'admin/');

// ---------- Tiny helpers ----------
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}
function redirect($url) { header("Location: $url"); exit(); }
function isLoggedIn()      { return isset($_SESSION['user_id']); }
function isAdminLoggedIn() { return isset($_SESSION['admin_id']); }
function generateBookingNumber() { return mt_rand(100000000, 999999999); }
function getVehicleImage($img) {
    return empty($img)
        ? SITEURL . 'assets/images/no-image.jpg'
        : SITEURL . 'admin/img/vehicles/' . $img;
}
function formatPrice($p) { return 'रू' . number_format((float)$p, 2); }
function getStatusBadge($s) {
    switch ((int)$s) {
        case 1:  return '<span class="badge bg-success">Confirmed</span>';
        case 2:  return '<span class="badge bg-danger">Cancelled</span>';
        default: return '<span class="badge bg-warning text-dark">Pending</span>';
    }
}