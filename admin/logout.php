<?php
session_start();
unset($_SESSION['admin_id'], $_SESSION['admin_name']);
session_destroy();
header("Location: index.php");
exit;