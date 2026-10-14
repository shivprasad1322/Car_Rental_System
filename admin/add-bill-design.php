<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

$designs = getBillDesigns();
if (count($designs) > 0) {
    redirect('edit-bill-design.php?id=' . (int)$designs[0]['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (saveBillDesign(0, $_POST)) {
        $_SESSION['success'] = 'Bill design added.';
        redirect('bill-designs.php');
    }
    $error = 'Unable to save bill design. Check the submitted values.';
}
$design = ['design_name' => '', 'company_name' => '', 'tagline' => '', 'pan_number' => '', 'address' => '', 'email' => '', 'phone' => '', 'website' => '', 'notes' => '', 'primary_color' => '#203b68', 'accent_color' => '#f3ba3f', 'signature_name' => 'Admin', 'signature_post' => 'Authorized Sign', 'is_active' => 1];
include 'bill-design-form.php';