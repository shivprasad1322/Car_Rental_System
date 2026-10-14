<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$design = getBillDesignById($id);
if (!$design) { http_response_code(404); exit('Bill design not found.'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (saveBillDesign($id, $_POST)) {
        $_SESSION['success'] = 'Bill design updated.';
        redirect('bill-designs.php');
    }
    $error = 'Unable to update bill design. Check the submitted values.';
}
include 'bill-design-form.php';