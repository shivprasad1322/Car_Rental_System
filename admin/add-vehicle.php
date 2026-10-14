<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

$brands = getAllBrands();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = sanitize($_POST['title']);
    $vehicleNumber = sanitize($_POST['vehicle_number']);
    $brand   = (int)$_POST['brand'];
    $over    = sanitize($_POST['overview']);
    $price   = (int)$_POST['price'];
    $fuel    = sanitize($_POST['fuel_type']);
    $year    = (int)$_POST['model_year'];
    $seats   = (int)$_POST['seating'];

    $feat = [];
    foreach (['AirConditioner'=>'air_conditioner','PowerDoorLocks'=>'power_door_locks',
              'AntiLockBrakingSystem'=>'anti_lock_braking','BrakeAssist'=>'brake_assist',
              'PowerSteering'=>'power_steering','DriverAirbag'=>'driver_airbag',
              'PassengerAirbag'=>'passenger_airbag','PowerWindows'=>'power_windows',
              'CDPlayer'=>'cd_player','CentralLocking'=>'central_locking',
              'CrashSensor'=>'crash_sensor','LeatherSeats'=>'leather_seats'] as $col=>$post) {
        $feat[$col] = isset($_POST[$post]) ? 1 : 0;
    }

    $imgs = ['','','','',''];
    for ($i=1; $i<=5; $i++) {
        if (!empty($_FILES["image$i"]['name'])) {
            $fn = time()."_$i_".basename($_FILES["image$i"]['name']);
            $dir = "img/vehicles/";
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            move_uploaded_file($_FILES["image$i"]['tmp_name'], $dir.$fn);
            $imgs[$i-1] = $fn;
        }
    }

    $sql = "INSERT INTO tblvehicles
      (VehicleNumber,VehicleTitle,VehicleBrand,VehicleOverview,PricePerDay,FuelType,ModelYear,SeatingCapacity,
       Vimage1,Vimage2,Vimage3,Vimage4,Vimage5,
       AirConditioner,PowerDoorLocks,AntiLockBrakingSystem,BrakeAssist,PowerSteering,
       DriverAirbag,PassengerAirbag,PowerWindows,CDPlayer,CentralLocking,CrashSensor,LeatherSeats)
      VALUES
      ('$vehicleNumber','$title',$brand,'$over',$price,'$fuel',$year,$seats,
       '{$imgs[0]}','{$imgs[1]}','{$imgs[2]}','{$imgs[3]}','{$imgs[4]}',
       {$feat['AirConditioner']},{$feat['PowerDoorLocks']},{$feat['AntiLockBrakingSystem']},{$feat['BrakeAssist']},{$feat['PowerSteering']},
       {$feat['DriverAirbag']},{$feat['PassengerAirbag']},{$feat['PowerWindows']},{$feat['CDPlayer']},{$feat['CentralLocking']},{$feat['CrashSensor']},{$feat['LeatherSeats']})";

    if ($conn->query($sql)) { $_SESSION['success']="Vehicle added."; redirect(ADMINURL.'manage-vehicles.php'); }
    else $error = $conn->error;
}
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Add Vehicle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <h1 class="mb-4">Add New Vehicle</h1>
  <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-8">
        <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Details</h5></div>
        <div class="card-body row">
          <div class="col-md-6 mb-3"><label class="form-label">Title *</label>
            <input name="title" class="form-control" required></div>
          <div class="col-md-6 mb-3"><label class="form-label">Vehicle Number *</label>
            <input name="vehicle_number" class="form-control" maxlength="50" required></div>
          <div class="col-md-6 mb-3"><label class="form-label">Brand *</label>
            <select name="brand" class="form-select" required>
              <option value="">Select</option>
              <?php foreach ($brands as $b): ?><option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['BrandName']) ?></option><?php endforeach; ?>
            </select></div>
          <div class="col-12 mb-3"><label class="form-label">Overview *</label>
            <textarea name="overview" rows="4" class="form-control" required></textarea></div>
          <div class="col-md-3 mb-3"><label class="form-label">Price/Day *</label>
            <input type="number" name="price" class="form-control" required></div>
          <div class="col-md-3 mb-3"><label class="form-label">Fuel *</label>
            <select name="fuel_type" class="form-select" required>
              <option>Petrol</option><option>Diesel</option><option>CNG</option><option>Electric</option>
            </select></div>
          <div class="col-md-3 mb-3"><label class="form-label">Model Year *</label>
            <input type="number" name="model_year" min="2000" max="<?= date('Y') ?>" class="form-control" required></div>
          <div class="col-md-3 mb-3"><label class="form-label">Seats *</label>
            <input type="number" name="seating" min="2" max="15" class="form-control" required></div>
        </div></div>

        <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Features</h5></div>
        <div class="card-body row">
          <?php
          $fList = ['air_conditioner'=>'Air Conditioner','power_door_locks'=>'Power Door Locks',
                    'anti_lock_braking'=>'ABS','brake_assist'=>'Brake Assist',
                    'power_steering'=>'Power Steering','driver_airbag'=>'Driver Airbag',
                    'passenger_airbag'=>'Passenger Airbag','power_windows'=>'Power Windows',
                    'cd_player'=>'CD Player','central_locking'=>'Central Locking',
                    'crash_sensor'=>'Crash Sensor','leather_seats'=>'Leather Seats'];
          foreach ($fList as $k=>$lbl): ?>
            <div class="col-md-4 mb-2">
              <div class="form-check">
                <input type="checkbox" name="<?= $k ?>" class="form-check-input" id="f_<?= $k ?>">
                <label class="form-check-label" for="f_<?= $k ?>"><?= $lbl ?></label>
              </div>
            </div>
          <?php endforeach; ?>
        </div></div>
      </div>

      <div class="col-md-4">
        <div class="card"><div class="card-header"><h5 class="mb-0">Images</h5></div>
        <div class="card-body">
          <?php for ($i=1;$i<=5;$i++): ?>
            <div class="mb-3"><label class="form-label">Image <?= $i ?><?= $i==1?' *':'' ?></label>
              <input type="file" name="image<?= $i ?>" class="form-control" accept="image/*" <?= $i==1?'required':'' ?>>
            </div>
          <?php endfor; ?>
        </div></div>
        <div class="mt-3">
          <button class="btn btn-primary w-100"><i class="fas fa-save"></i> Save</button>
          <a href="manage-vehicles.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </div>
      </div>
    </div>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>