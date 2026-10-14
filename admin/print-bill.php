<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

$bill = getBillingById($_GET['id'] ?? 0);
$design = getActiveBillDesign();
if (!$bill) {
    http_response_code(404);
    exit('Bill not found.');
}
$taxRate = 13;
$taxAmount = round((float)$bill['subtotal'] * $taxRate / 100, 2);
$totalWithTax = (float)$bill['subtotal'] + $taxAmount;
$signatureName = $design['signature_name'] ?? 'Admin';
$signaturePost = $design['signature_post'] ?? 'Authorized Sign';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($bill['invoice_number']) ?> - Bill</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
@page { size: A4; margin: 0; }
:root { --bill-navy: #203b68; --bill-gold: #f3ba3f; --bill-ink: #28313d; --bill-muted: #657080; }
* { box-sizing: border-box; }
body { margin: 0; background: #e8ebef; color: var(--bill-ink); font-family: Arial, Helvetica, sans-serif; }
.bill { position: relative; max-width: 794px; min-height: 1123px; margin: 1.5rem auto; overflow: hidden; background: #fff; box-shadow: 0 8px 24px rgba(15,23,42,.12); }
.bill-header { display: flex; justify-content: space-between; align-items: center; min-height: 148px; padding: 24px 38px 20px; color: var(--bill-navy); background: var(--bill-gold); }
.brand { display: flex; align-items: center; gap: 14px; }
.brand-mark { display: grid; place-items: center; width: 76px; height: 58px; color: var(--bill-navy); font-size: 3.4rem; }
.brand-name { margin: 0; font-size: 1.75rem; font-weight: 800; letter-spacing: .02em; }
.brand-subtitle { margin: 1px 0 0; font-size: 1.05rem; font-weight: 700; }
.brand-pan { margin: 7px 0 0; font-size: .72rem; letter-spacing: .08em; }
.company-contact { text-align: right; font-size: .76rem; line-height: 1.8; }
.company-contact i { width: 17px; margin-left: 5px; text-align: center; }
.navy-rule { height: 17px; background: var(--bill-navy); border-bottom: 3px solid #d9a62f; }
.bill-content { padding: 28px 38px 0; }
.bill-top { display: flex; justify-content: space-between; gap: 28px; margin-bottom: 38px; }
.section-label { margin-bottom: 8px; color: var(--bill-navy); font-size: .76rem; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
.bill-to { flex: 1; }
.customer-line { margin: 4px 0; font-size: .86rem; }
.customer-line strong { display: inline-block; width: 84px; font-weight: 400; }
.invoice-heading { min-width: 245px; text-align: right; }
.invoice-heading h1 { margin: -5px 0 14px; color: var(--bill-navy); font-size: 2rem; letter-spacing: .03em; }
.invoice-meta { margin: 4px 0; font-size: .78rem; }
.invoice-meta strong { display: inline-block; width: 110px; font-weight: 400; }
.vehicle-block { margin-bottom: 22px; }
.vehicle-grid { display: grid; grid-template-columns: 100px 1fr 100px 1fr; gap: 6px 10px; font-size: .82rem; }
.vehicle-grid strong { font-weight: 400; }
.bill-table { width: 100%; margin: 0; border-collapse: collapse; font-size: .8rem; }
.bill-table th { padding: 9px 10px; color: #fff; background: var(--bill-navy); font-size: .76rem; }
.bill-table th:not(:first-child) { color: #fff; background: var(--bill-gold); text-align: center; }
.bill-table td { padding: 9px 10px; border-bottom: 1px solid #e5e7eb; }
.bill-table .numeric { text-align: center; white-space: nowrap; }
.bill-table .total-label { color: #fff; background: var(--bill-navy); text-align: right; font-weight: 700; }
.bill-table .total-value { color: #fff; background: var(--bill-gold); font-size: 1rem; font-weight: 800; text-align: center; }
.payment-section { margin-top: 20px; }
.payment-options { display: flex; flex-wrap: wrap; gap: 22px; font-size: .88rem; }
.payment-option::before { content: '\2610'; margin-right: 7px; font-size: 1.1rem; vertical-align: -1px; }
.payment-option.selected { font-weight: 700; }
.payment-option.selected::before { content: '\2611'; }
.bottom-area { display: flex; justify-content: space-between; gap: 35px; margin-top: 66px; }
.notes { max-width: 390px; font-size: .76rem; line-height: 1.55; }
.signature { min-width: 190px; align-self: flex-end; text-align: center; font-size: .74rem; }
.signature-line { margin-bottom: 7px; border-bottom: 1px solid #667085; }
.signature strong { display: block; font-size: .82rem; }
.print-actions { padding: 24px 38px; text-align: right; }
.bill-footer { position: absolute; right: 0; bottom: 0; left: 0; height: 12px; background: linear-gradient(to right, var(--bill-navy) 0 70%, var(--bill-gold) 70% 100%); }
@media print {
  body { background: #fff; }
  .bill { max-width: none; min-height: 297mm; margin: 0; box-shadow: none; }
  .no-print { display: none !important; }
}
@media (max-width: 700px) {
  .bill { margin: 0; min-height: 100vh; }
  .bill-header, .bill-content { padding-right: 20px; padding-left: 20px; }
  .bill-header, .bill-top, .bottom-area { flex-direction: column; }
  .company-contact, .invoice-heading { text-align: left; }
  .vehicle-grid { grid-template-columns: 90px 1fr; }
  .bottom-area { margin-top: 35px; }
}
</style>
</head>
<?php $primaryColor = htmlspecialchars($design['primary_color'] ?? '#203b68'); $accentColor = htmlspecialchars($design['accent_color'] ?? '#f3ba3f'); ?>
<style>:root { --bill-navy: <?= $primaryColor ?>; --bill-gold: <?= $accentColor ?>; }</style>
<body>
<main class="bill">
  <div class="bill-header">
    <div class="brand">
      <div class="brand-mark"><i class="fas fa-car-side"></i></div>
      <div><h2 class="brand-name"><?= htmlspecialchars($design['company_name'] ?? 'ANTARES') ?></h2><div class="brand-subtitle"><?= htmlspecialchars($design['tagline'] ?? 'Car Rental') ?></div><div class="brand-pan">PAN / VAT: <?= htmlspecialchars($design['pan_number'] ?? '') ?></div></div>
    </div>
    <div class="company-contact"><?= htmlspecialchars($design['address'] ?? '') ?> <i class="fas fa-location-dot"></i><br><?= htmlspecialchars($design['email'] ?? '') ?> <i class="fas fa-envelope"></i><br><?= htmlspecialchars($design['phone'] ?? '') ?> <i class="fas fa-phone"></i><br><?= htmlspecialchars($design['website'] ?? '') ?> <i class="fas fa-globe"></i></div>
  </div>
  <div class="navy-rule"></div>
  <div class="bill-content">
    <div class="bill-top">
      <div class="bill-to"><div class="section-label">Bill To</div>
        <div class="customer-line"><strong>Name</strong>: <?= htmlspecialchars($bill['FullName'] ?: $bill['userEmail']) ?></div>
        <div class="customer-line"><strong>Address</strong>: <?= htmlspecialchars($bill['Address'] ?: 'Not provided') ?></div>
        <div class="customer-line"><strong>Email</strong>: <?= htmlspecialchars($bill['userEmail']) ?></div>
        <div class="customer-line"><strong>Phone</strong>: <?= htmlspecialchars($bill['ContactNo'] ?: 'Not provided') ?></div>
      </div>
      <div class="invoice-heading"><h1>INVOICE</h1>
        <div class="invoice-meta"><strong>Invoice Number</strong>: <?= htmlspecialchars($bill['invoice_number']) ?></div>
        <div class="invoice-meta"><strong>Booking Number</strong>: #<?= (int)$bill['BookingNumber'] ?></div>
        <div class="invoice-meta"><strong>Invoice Date</strong>: <?= date('F d, Y', strtotime($bill['created_at'])) ?></div>
      </div>
    </div>
    <div class="vehicle-block"><div class="section-label">Vehicle Information</div>
      <div class="vehicle-grid"><strong>Car Model</strong><span>: <?= htmlspecialchars($bill['VehicleTitle'] ?: 'Vehicle rental') ?></span><strong>Vehicle No.</strong><span>: <?= htmlspecialchars($bill['VehicleNumber']) ?></span>
        <strong>Rental Period</strong><span>: <?= date('m/d/Y', strtotime($bill['FromDate'])) ?> - <?= date('m/d/Y', strtotime($bill['ToDate'])) ?></span><strong>Booking</strong><span>: #<?= (int)$bill['BookingNumber'] ?></span>
      </div>
    </div>
    <table class="bill-table">
      <thead><tr><th>Description</th><th>Rate</th><th>Quantity</th><th>Subtotal</th></tr></thead>
      <tbody>
        <tr><td>Car Rental Charges</td><td class="numeric"><?= formatPrice($bill['daily_rate']) ?></td><td class="numeric"><?= (int)$bill['rental_days'] ?> days</td><td class="numeric"><?= formatPrice($bill['subtotal']) ?></td></tr>
        <tr><td colspan="3" class="total-label">Tax (<?= $taxRate ?>% VAT)</td><td class="total-value"><?= formatPrice($taxAmount) ?></td></tr>
        <tr><td colspan="3" class="total-label">Total Charges</td><td class="total-value"><?= formatPrice($totalWithTax) ?></td></tr>
      </tbody>
    </table>
    <div class="payment-section"><div class="section-label">Payment Information</div><div class="payment-options">
      <?php foreach (['Cash','Card','Bank transfer','Esewa','Khalti'] as $method): ?><span class="payment-option <?= strtolower($bill['payment_method'] ?? '') === strtolower($method) ? 'selected' : '' ?>"><?= $method ?></span><?php endforeach; ?>
    </div></div>
    <div class="bottom-area"><div class="notes"><div class="section-label">Terms and Conditions</div><div>1. The vehicle must be returned on or before the agreed return date.</div><div>2. The customer is responsible for the vehicle during the rental period.</div><div>3. Additional charges may apply for late returns, damage, or missing fuel.</div><div class="section-label" style="margin-top: 14px;">Notes</div><?= nl2br(htmlspecialchars($design['notes'] ?? '')) ?></div>
      <div class="signature"><div><?= date('F d, Y', strtotime($bill['created_at'])) ?></div><div class="signature-line" style="margin-top: 56px;"></div><strong><?= htmlspecialchars($signatureName) ?></strong><div><?= htmlspecialchars($signaturePost) ?></div></div>
    </div>
  </div>
  <div class="print-actions no-print"><button type="button" class="btn btn-dark" onclick="window.print()"><i class="fas fa-print"></i> Print Bill</button></div>
  <div class="bill-footer"></div>
</main>
</body>
</html>