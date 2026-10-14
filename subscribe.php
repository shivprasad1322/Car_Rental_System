<?php
require_once 'includes/config.php';
$email = sanitize($_POST['email'] ?? '');
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $conn->query("INSERT INTO tblsubscribers (SubscriberEmail) VALUES ('$email')");
    $_SESSION['success'] = "Subscribed successfully!";
} else {
    $_SESSION['error'] = "Invalid email.";
}
redirect($_SERVER['HTTP_REFERER'] ?? SITEURL . 'index.php');