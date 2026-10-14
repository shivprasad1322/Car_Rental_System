<?php
require_once __DIR__ . '/config.php';

// ---------- Access guards ----------
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = "Please login to access this page";
        redirect(SITEURL . 'login.php');
    }
}
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        $_SESSION['error'] = "Please login as admin";
        redirect(ADMINURL . 'index.php');
    }
}

// ---------- Brands ----------
function getAllBrands() {
    global $conn;
    $r = $conn->query("SELECT * FROM tblbrands ORDER BY BrandName ASC");
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}

// ---------- Vehicles ----------
function getAllVehicles($limit = null) {
    global $conn;
    $sql = "SELECT v.*, b.BrandName
            FROM tblvehicles v
            LEFT JOIN tblbrands b ON v.VehicleBrand = b.id
            ORDER BY v.id DESC";
    if ($limit) $sql .= " LIMIT " . intval($limit);
    $r = $conn->query($sql);
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}
function getVehicleById($id) {
    global $conn;
    $id = intval($id);
    $r = $conn->query("SELECT v.*, b.BrandName
                       FROM tblvehicles v
                       LEFT JOIN tblbrands b ON v.VehicleBrand = b.id
                       WHERE v.id = $id");
    return $r ? $r->fetch_assoc() : null;
}

// ---------- Users ----------
function getUserByEmail($email) {
    global $conn;
    $email = sanitize($email);
    $r = $conn->query("SELECT * FROM tblusers WHERE EmailId = '$email'");
    return $r ? $r->fetch_assoc() : null;
}
function updateUserProfile($userId, $name, $email, $contact, $dob, $address, $city, $country) {
    global $conn;
    $userId  = (int)$userId;
    $name    = sanitize($name);
    $email   = sanitize($email);
    $contact = sanitize($contact);
    $dob     = sanitize($dob);
    $address = sanitize($address);
    $city    = sanitize($city);
    $country = sanitize($country);
    $sql = "UPDATE tblusers SET FullName='$name', EmailId='$email', ContactNo='$contact',
            dob='$dob', Address='$address', City='$city', Country='$country' WHERE id=$userId";
    return $conn->query($sql);
}
function deleteUserAccount($userId, $email) {
    global $conn;
    $userId = (int)$userId;
    $email = sanitize($email);
    $conn->query("DELETE FROM tblbooking WHERE userEmail='$email'");
    return $conn->query("DELETE FROM tblusers WHERE id=$userId AND EmailId='$email'");
}

// ---------- Bookings ----------
function getUserBookings($email) {
    global $conn;
    $email = sanitize($email);
    $r = $conn->query("SELECT b.*, v.VehicleTitle, v.PricePerDay, v.Vimage1, br.BrandName
                       FROM tblbooking b
                       LEFT JOIN tblvehicles v ON b.VehicleId = v.id
                       LEFT JOIN tblbrands  br ON v.VehicleBrand = br.id
                       WHERE b.userEmail = '$email'
                       ORDER BY b.PostingDate DESC");
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}
function cancelUserBooking($bookingId, $email) {
    global $conn;
    $bookingId = (int)$bookingId;
    $email = sanitize($email);
    return $conn->query("UPDATE tblbooking SET Status=2
                         WHERE id=$bookingId AND userEmail='$email' AND Status IN (0, 1)");
}
function deleteCancelledBooking($bookingId, $email = null) {
    global $conn;
    $bookingId = (int)$bookingId;
    $ownerCondition = $email === null ? '' : " AND userEmail='" . sanitize($email) . "'";
    $booking = $conn->query("SELECT id FROM tblbooking WHERE id=$bookingId AND Status=2$ownerCondition");
    if (!$booking || !$booking->fetch_assoc()) return false;
    $conn->query("DELETE FROM tblbilling WHERE booking_id=$bookingId");
    return $conn->query("DELETE FROM tblbooking WHERE id=$bookingId AND Status=2$ownerCondition");
}
function getAllBookings($status = null) {
    global $conn;
    $sql = "SELECT b.*, u.FullName, v.VehicleTitle, v.PricePerDay, v.Vimage1
            FROM tblbooking b
            LEFT JOIN tblusers u ON u.EmailId = b.userEmail
            LEFT JOIN tblvehicles v ON b.VehicleId = v.id";
    if ($status !== null) $sql .= " WHERE b.Status = " . intval($status);
    $sql .= " ORDER BY b.PostingDate DESC";
    $r = $conn->query($sql);
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}

// ---------- Billing ----------
function ensureBillDesignTable() {
    global $conn;
    static $ready = false;
    if ($ready) return;
    $conn->query("CREATE TABLE IF NOT EXISTS tblbilldesigns (
        id INT(11) NOT NULL AUTO_INCREMENT,
        design_name VARCHAR(100) NOT NULL,
        company_name VARCHAR(150) NOT NULL,
        tagline VARCHAR(150) DEFAULT NULL,
        pan_number VARCHAR(50) DEFAULT NULL,
        address VARCHAR(255) DEFAULT NULL,
        email VARCHAR(150) DEFAULT NULL,
        phone VARCHAR(50) DEFAULT NULL,
        website VARCHAR(150) DEFAULT NULL,
        notes TEXT DEFAULT NULL,
        primary_color VARCHAR(7) NOT NULL DEFAULT '#203b68',
        accent_color VARCHAR(7) NOT NULL DEFAULT '#f3ba3f',
        signature_name VARCHAR(100) NOT NULL DEFAULT 'Admin',
        signature_post VARCHAR(100) NOT NULL DEFAULT 'Authorized Sign',
        is_active TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    foreach ([
        'signature_name' => "VARCHAR(100) NOT NULL DEFAULT 'Admin'",
        'signature_post' => "VARCHAR(100) NOT NULL DEFAULT 'Authorized Sign'"
    ] as $column => $definition) {
        $columnCheck = $conn->query("SHOW COLUMNS FROM tblbilldesigns LIKE '$column'");
        if ($columnCheck && $columnCheck->num_rows === 0) {
            $conn->query("ALTER TABLE tblbilldesigns ADD COLUMN $column $definition");
        }
    }
    $count = $conn->query("SELECT COUNT(*) c FROM tblbilldesigns");
    if ($count && (int)$count->fetch_assoc()['c'] === 0) {
        $conn->query("INSERT INTO tblbilldesigns
            (design_name, company_name, tagline, pan_number, address, email, phone, website, notes, is_active)
            VALUES ('Default design', 'ANTARES', 'Car Rental', '108318630',
                    '1234 Car Avenue Citysville, CA 12345', 'velocitywheels@email.com',
                    '210-788-8829', 'www.velocitywheels.com',
                    'Thank you for choosing Antares Car Rental for your car rental needs. If you have any questions regarding this invoice, please contact us using the details above.', 1)");
    }
    $ready = true;
}
function getBillDesigns() {
    global $conn;
    ensureBillDesignTable();
    $r = $conn->query("SELECT * FROM tblbilldesigns ORDER BY is_active DESC, id DESC");
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}
function getBillDesignById($id) {
    global $conn;
    ensureBillDesignTable();
    $r = $conn->query("SELECT * FROM tblbilldesigns WHERE id=" . (int)$id);
    return $r ? $r->fetch_assoc() : null;
}
function getActiveBillDesign() {
    global $conn;
    ensureBillDesignTable();
    $r = $conn->query("SELECT * FROM tblbilldesigns ORDER BY is_active DESC, id ASC LIMIT 1");
    return $r ? $r->fetch_assoc() : null;
}
function saveBillDesign($id, $data) {
    global $conn;
    ensureBillDesignTable();
    $id = (int)$id;
    if (!$id) {
        $existing = $conn->query("SELECT id FROM tblbilldesigns LIMIT 1");
        if ($existing && $existing->num_rows > 0) return false;
    }
    $values = [];
    foreach (['design_name','company_name','tagline','pan_number','address','email','phone','website','notes','primary_color','accent_color','signature_name','signature_post'] as $field) {
        $values[$field] = sanitize($data[$field] ?? '');
    }
    if (!preg_match('/^#[0-9a-fA-F]{6}$/', $values['primary_color'])) $values['primary_color'] = '#203b68';
    if (!preg_match('/^#[0-9a-fA-F]{6}$/', $values['accent_color'])) $values['accent_color'] = '#f3ba3f';
    $active = !empty($data['is_active']) ? 1 : 0;
    if ($active) $conn->query("UPDATE tblbilldesigns SET is_active=0");
    if ($id) {
        return $conn->query("UPDATE tblbilldesigns SET design_name='{$values['design_name']}', company_name='{$values['company_name']}', tagline='{$values['tagline']}', pan_number='{$values['pan_number']}', address='{$values['address']}', email='{$values['email']}', phone='{$values['phone']}', website='{$values['website']}', notes='{$values['notes']}', primary_color='{$values['primary_color']}', accent_color='{$values['accent_color']}', signature_name='{$values['signature_name']}', signature_post='{$values['signature_post']}', is_active=$active WHERE id=$id");
    }
    return $conn->query("INSERT INTO tblbilldesigns (design_name, company_name, tagline, pan_number, address, email, phone, website, notes, primary_color, accent_color, signature_name, signature_post, is_active) VALUES ('{$values['design_name']}', '{$values['company_name']}', '{$values['tagline']}', '{$values['pan_number']}', '{$values['address']}', '{$values['email']}', '{$values['phone']}', '{$values['website']}', '{$values['notes']}', '{$values['primary_color']}', '{$values['accent_color']}', '{$values['signature_name']}', '{$values['signature_post']}', $active)");
}
function deleteBillDesign($id) {
    global $conn;
    ensureBillDesignTable();
    $id = (int)$id;
    $design = getBillDesignById($id);
    if (!$design) return false;
    $result = $conn->query("DELETE FROM tblbilldesigns WHERE id=$id");
    if ($result && $design['is_active']) $conn->query("UPDATE tblbilldesigns SET is_active=1 WHERE id=(SELECT id FROM (SELECT id FROM tblbilldesigns ORDER BY id DESC LIMIT 1) selected)");
    return $result;
}
function rentalDays($from, $to) {
    $days = (int)((strtotime($to) - strtotime($from)) / 86400);
    return max(1, $days);
}
function createBillingForBooking($bookingId) {
    global $conn;
    $bookingId = (int)$bookingId;
    $r = $conn->query("SELECT b.BookingNumber, b.FromDate, b.ToDate, v.PricePerDay
                       FROM tblbooking b
                       LEFT JOIN tblvehicles v ON b.VehicleId = v.id
                       WHERE b.id = $bookingId");
    $booking = $r ? $r->fetch_assoc() : null;
    if (!$booking) return false;

    $days = rentalDays($booking['FromDate'], $booking['ToDate']);
    $rate = (float)$booking['PricePerDay'];
    $amount = $days * $rate;
    $invoice = 'INV-' . (int)$booking['BookingNumber'];
    return $conn->query("INSERT IGNORE INTO tblbilling
        (booking_id, invoice_number, rental_days, daily_rate, subtotal, total_amount)
        VALUES ($bookingId, '$invoice', $days, $rate, $amount, $amount)");
}
function createBillingForDueReturn($bookingId) {
    global $conn;
    $bookingId = (int)$bookingId;
    $r = $conn->query("SELECT id FROM tblbooking
                       WHERE id = $bookingId AND Status <> 2
                       AND STR_TO_DATE(ToDate, '%Y-%m-%d') <= CURDATE()");
    return $r && $r->num_rows > 0 && createBillingForBooking($bookingId);
}
function syncBillingRecords() {
    global $conn;
        $r = $conn->query("SELECT b.id FROM tblbooking b
                           LEFT JOIN tblbilling bl ON bl.booking_id = b.id
                           WHERE bl.id IS NULL AND b.Status = 1");
    if (!$r) return;
    while ($row = $r->fetch_assoc()) createBillingForBooking($row['id']);
}
function getBillingRows() {
    global $conn;
        $sql = "SELECT bl.*, b.BookingNumber, b.userEmail, u.FullName, b.FromDate, b.ToDate,
                       b.Status AS booking_status, v.VehicleNumber, v.VehicleTitle
            FROM tblbilling bl
            INNER JOIN tblbooking b ON b.id = bl.booking_id
            LEFT JOIN tblusers u ON u.EmailId = b.userEmail
            LEFT JOIN tblvehicles v ON v.id = b.VehicleId
            WHERE b.Status <> 2
            ORDER BY bl.created_at DESC";
    $r = $conn->query($sql);
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}
function getBillingById($billingId) {
    global $conn;
    $billingId = (int)$billingId;
    $sql = "SELECT bl.*, b.BookingNumber, b.userEmail, u.FullName, u.ContactNo, u.Address,
                   b.FromDate, b.ToDate, v.VehicleNumber, v.VehicleTitle, v.PricePerDay
            FROM tblbilling bl
            INNER JOIN tblbooking b ON b.id = bl.booking_id
            LEFT JOIN tblusers u ON u.EmailId = b.userEmail
            LEFT JOIN tblvehicles v ON v.id = b.VehicleId
            WHERE bl.id = $billingId";
    $r = $conn->query($sql);
    return $r ? $r->fetch_assoc() : null;
}
function getBookingsWithoutBilling() {
    global $conn;
    $sql = "SELECT b.id, b.BookingNumber, b.FromDate, b.ToDate, b.userEmail,
                   u.FullName, v.VehicleTitle, v.PricePerDay
            FROM tblbooking b
            LEFT JOIN tblbilling bl ON bl.booking_id = b.id
            LEFT JOIN tblusers u ON u.EmailId = b.userEmail
            LEFT JOIN tblvehicles v ON v.id = b.VehicleId
            WHERE bl.id IS NULL ORDER BY b.PostingDate DESC";
    $r = $conn->query($sql);
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}
function createBilling($bookingId, $invoiceNumber, $days, $rate, $status, $method) {
    global $conn;
    $bookingId = (int)$bookingId;
    $invoiceNumber = sanitize($invoiceNumber);
    $days = max(1, (int)$days);
    $rate = max(0, (float)$rate);
    $amount = $days * $rate;
    $status = sanitize($status);
    $method = sanitize($method);
    if (!in_array($status, ['unpaid', 'paid', 'void'], true)) return false;
    $paidAt = $status === 'paid' ? 'NOW()' : 'NULL';
    return $conn->query("INSERT INTO tblbilling
        (booking_id, invoice_number, rental_days, daily_rate, subtotal, total_amount, payment_status, payment_method, paid_at)
        VALUES ($bookingId, '$invoiceNumber', $days, $rate, $amount, $amount, '$status', NULLIF('$method', ''), $paidAt)");
}
function updateBilling($billingId, $invoiceNumber, $days, $rate, $status, $method) {
    global $conn;
    $billingId = (int)$billingId;
    $invoiceNumber = sanitize($invoiceNumber);
    $days = max(1, (int)$days);
    $rate = max(0, (float)$rate);
    $amount = $days * $rate;
    $status = sanitize($status);
    $method = sanitize($method);
    if (!in_array($status, ['unpaid', 'paid', 'void'], true)) return false;
    $paidAt = $status === 'paid' ? 'NOW()' : 'NULL';
    return $conn->query("UPDATE tblbilling SET invoice_number='$invoiceNumber', rental_days=$days,
                         daily_rate=$rate, subtotal=$amount, total_amount=$amount,
                         payment_status='$status', payment_method=NULLIF('$method', ''), paid_at=$paidAt
                         WHERE id=$billingId");
}
function deleteBilling($billingId) {
    global $conn;
    return $conn->query('DELETE FROM tblbilling WHERE id=' . (int)$billingId);
}
function getBillingTotals() {
    global $conn;
    $totals = ['invoiced' => 0, 'paid' => 0, 'unpaid' => 0];
    $r = $conn->query("SELECT bl.payment_status, COALESCE(SUM(bl.total_amount), 0) amount
                       FROM tblbilling bl
                       INNER JOIN tblbooking b ON b.id = bl.booking_id
                       WHERE b.Status <> 2
                       GROUP BY bl.payment_status");
    if ($r) while ($row = $r->fetch_assoc()) {
        if ($row['payment_status'] === 'paid') $totals['paid'] = (float)$row['amount'];
        if ($row['payment_status'] === 'unpaid') $totals['unpaid'] = (float)$row['amount'];
    }
    $totals['invoiced'] = $totals['paid'] + $totals['unpaid'];
    return $totals;
}
function updateBillingStatus($billingId, $status, $method = null) {
    global $conn;
    $billingId = (int)$billingId;
    $allowed = ['unpaid', 'paid', 'void'];
    if (!in_array($status, $allowed, true)) return false;
    $status = sanitize($status);
    $method = $method === null ? '' : sanitize($method);
    $paidAt = $status === 'paid' ? 'NOW()' : 'NULL';
    return $conn->query("UPDATE tblbilling SET payment_status='$status',
                         payment_method=NULLIF('$method', ''), paid_at=$paidAt
                         WHERE id=$billingId");
}

// ---------- Availability ----------
function isVehicleAvailable($vehicleId, $from, $to, $excludeId = null) {
    global $conn;
    $vehicleId = intval($vehicleId);
    $from = sanitize($from);
    $to   = sanitize($to);
    $sql = "SELECT COUNT(*) c FROM tblbooking
            WHERE VehicleId = $vehicleId
              AND Status = 1
              AND ( (FromDate <= '$from' AND ToDate >= '$from')
                 OR (FromDate <= '$to'   AND ToDate >= '$to')
                 OR (FromDate >= '$from' AND ToDate <= '$to') )";
    if ($excludeId) $sql .= " AND id != " . intval($excludeId);
    $r = $conn->query($sql);
    return $r && $r->fetch_assoc()['c'] == 0;
}

// ---------- Dashboard stats ----------
function getDashboardStats() {
    global $conn;
    $map = [
        'users'        => 'tblusers',
        'vehicles'     => 'tblvehicles',
        'bookings'     => 'tblbooking',
        'billing'      => 'tblbilling',
        'brands'       => 'tblbrands',
        'subscribers'  => 'tblsubscribers',
        'testimonials' => 'tbltestimonials'
    ];
    $out = [];
    foreach ($map as $k => $t) {
        $r = $conn->query("SELECT COUNT(*) c FROM $t");
        $out[$k] = $r ? (int)$r->fetch_assoc()['c'] : 0;
    }
    return $out;
}
function getDueReturnBookings() {
        global $conn;
        $sql = "SELECT b.id, b.BookingNumber, b.FromDate, b.ToDate, b.userEmail,
                                     u.FullName, v.VehicleTitle, v.PricePerDay
                        FROM tblbooking b
                        LEFT JOIN tblbilling bl ON bl.booking_id = b.id
                        LEFT JOIN tblusers u ON u.EmailId = b.userEmail
                        LEFT JOIN tblvehicles v ON v.id = b.VehicleId
                        WHERE bl.id IS NULL AND b.Status <> 2
                            AND STR_TO_DATE(b.ToDate, '%Y-%m-%d') <= CURDATE()
                        ORDER BY STR_TO_DATE(b.ToDate, '%Y-%m-%d') ASC";
        $r = $conn->query($sql);
        return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}
