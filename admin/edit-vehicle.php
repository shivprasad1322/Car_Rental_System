<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdminLogin();

$id = (int)($_GET['id'] ?? 0);
$v  = getVehicleById($id);
if (!$v) { $_SESSION['error']="Not found"; redirect(ADMINURL.'manage-vehicles.php'); }
$brands = getAllBrands();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $vehicleNumber = sanitize($_POST['vehicle_number']);
    $brand = (int)$_POST['brand'];
    $over  = sanitize($_POST['overview']);
    $price = (int)$_POST['price'];
    $fuel  = sanitize($_POST['fuel_type']);
    $year  = (int)$_POST['model_year'];
    $seats = (int)$_POST['seating'];

    $f = [];
    foreach (['AirConditioner'=>'air_conditioner','PowerDoorLocks'=>'power_door_locks',
              'AntiLockBrakingSystem'=>'anti_lock_braking','BrakeAssist'=>'brake_assist',
              'PowerSteering'=>'power_steering','DriverAirbag'=>'driver_airbag',
              'PassengerAirbag'=>'passenger_airbag','PowerWindows'=>'power_windows',
              'CDPlayer'=>'cd_player','CentralLocking'=>'central_locking',
              'CrashSensor'=>'crash_sensor','LeatherSeats'=>'leather_seats'] as $col=>$post) {
        $f[$col] = isset($_POST[$post]) ? 1 : 0;
    }

    // Replace only provided images
    for ($i=1; $i<=5; $i++) {
        if (!empty($_FILES["image$i"]['name'])) {
            $fn = time()."_$i_".basename($_FILES["image$i"]['name']);
            $dir = "img/vehicles/";
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            move_uploaded_file($_FILES["image$i"]['tmp_name'], $dir.$fn);
            $conn->query("UPDATE tblvehicles SET Vimage$i='$fn' WHERE id=$id");
        }
    }

    $sql = "UPDATE tblvehicles SET
      VehicleNumber='$vehicleNumber', VehicleTitle='$title', VehicleBrand=$brand, VehicleOverview='$over',
      PricePerDay=$price, FuelType='$fuel', ModelYear=$year, SeatingCapacity=$seats,
      AirConditioner={$f['AirConditioner']}, PowerDoorLocks={$f['PowerDoorLocks']},
      AntiLockBrakingSystem={$f['AntiLockBrakingSystem']}, BrakeAssist={$f['BrakeAssist']},
      PowerSteering={$f['PowerSteering']}, DriverAirbag={$f['DriverAirbag']},
      PassengerAirbag={$f['PassengerAirbag']}, PowerWindows={$f['PowerWindows']},
      CDPlayer={$f['CDPlayer']}, CentralLocking={$f['CentralLocking']},
      CrashSensor={$f['CrashSensor']}, LeatherSeats={$f['LeatherSeats']}
      WHERE id=$id";
    $conn->query($sql);
    $_SESSION['success']="Vehicle updated."; redirect(ADMINURL.'manage-vehicles.php');
}
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Edit Vehicle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content">
  <h1 class="mb-4">Edit Vehicle</h1>
  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-8">
        <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Details</h5></div>
        <div class="card-body row">
          <div class="col-md-6 mb-3"><label class="form-label">Title</label>
            <input name="title" class="form-control" value="<?= htmlspecialchars($v['VehicleTitle']) ?>" required></div>
          <div class="col-md-6 mb-3"><label class="form-label">Vehicle Number</label>
            <input name="vehicle_number" class="form-control" maxlength="50" value="<?= htmlspecialchars($v['VehicleNumber']) ?>" required></div>
          <div class="col-md-6 mb-3"><label class="form-label">Brand</label>
            <select name="brand" class="form-select" required>
              <?php foreach ($brands as $b): ?>
                <option value="<?= $b['id'] ?>" <?= $v['VehicleBrand']==$b['id']?'selected':'' ?>>
                  <?= htmlspecialchars($b['BrandName']) ?></option>
              <?php endforeach; ?>
            </select></div>
          <div class="col-12 mb-3"><label class="form-label">Overview</label>
            <textarea name="overview" rows="4" class="form-control" required><?= htmlspecialchars($v['VehicleOverview']) ?></textarea></div>
          <div class="col-md-3 mb-3"><label class="form-label">Price/Day</label>
            <input type="number" name="price" class="form-control" value="<?= (int)$v['PricePerDay'] ?>" required></div>
          <div class="col-md-3 mb-3"><label class="form-label">Fuel</label>
            <select name="fuel_type" class="form-select" required>
              <?php foreach (['Petrol','Diesel','CNG','Electric'] as $ft): ?>
                <option <?= $v['FuelType']==$ft?'selected':'' ?>><?= $ft ?></option>
              <?php endforeach; ?>
            </select></div>
          <div class="col-md-3 mb-3"><label class="form-label">Year</label>
            <input type="number" name="model_year" class="form-control" value="<?= (int)$v['ModelYear'] ?>" required></div>
          <div class="col-md-3 mb-3"><label class="form-label">Seats</label>
            <input type="number" name="seating" class="form-control" value="<?= (int)$v['SeatingCapacity'] ?>" required></div>
        </div></div>

        <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Features</h5></div>
        <div class="card-body row">
          <?php
          $fList = ['AirConditioner'=>'air_conditioner','PowerDoorLocks'=>'power_door_locks',
                    'AntiLockBrakingSystem'=>'anti_lock_braking','BrakeAssist'=>'brake_assist',
                    'PowerSteering'=>'power_steering','DriverAirbag'=>'driver_airbag',
                    'PassengerAirbag'=>'passenger_airbag','PowerWindows'=>'power_windows',
                    'CDPlayer'=>'cd_player','CentralLocking'=>'central_locking',
                    'CrashSensor'=>'crash_sensor','LeatherSeats'=>'leather_seats'];
          foreach ($fList as $col=>$post): ?>
            <div class="col-md-4 mb-2"><div class="form-check">
              <input type="checkbox" name="<?= $post ?>" class="form-check-input" id="f_<?= $post ?>"
                     <?= $v[$col]?'checked':'' ?>>
              <label class="form-check-label" for="f_<?= $post ?>"><?= str_replace('_',' ',$post) ?></label>
            </div></div>
          <?php endforeach; ?>
        </div></div>
      </div>

      <div class="col-md-4">
        <div class="card"><div class="card-header"><h5 class="mb-0">Replace Images</h5></div>
        <div class="card-body">
          <?php for ($i=1;$i<=5;$i++):
            $col = "Vimage$i"; ?>
            <div class="mb-3">
              <label class="form-label">Image <?= $i ?></label>
              <?php if (!empty($v[$col])): ?>
                <div class="mb-1"><img src="<?= getVehicleImage($v[$col]) ?>" style="width:80px;"></div>
              <?php endif; ?>
              <input type="file" name="image<?= $i ?>" class="form-control" accept="image/*">
            </div>
          <?php endfor; ?>
        </div></div>
        <div class="mt-3">
          <button class="btn btn-primary w-100"><i class="fas fa-save"></i> Update</button>
          <a href="manage-vehicles.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </div>
      </div>
    </div>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>