<?php
$pageTitle = 'Car Listing';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$brand     = $_GET['brand']     ?? null;
$fuelType  = $_GET['fuel_type'] ?? null;
$maxPrice  = $_GET['max_price'] ?? null;
$sort      = $_GET['sort']      ?? 'latest';

$vehicles = searchVehicles($brand, $fuelType, null, $maxPrice, $sort);
$brands   = getAllBrands();

/**
 * Small helper local to this file.
 */
function searchVehicles($brand=null,$fuel=null,$min=null,$max=null,$sort='latest') {
    global $conn;
    $sql = "SELECT v.*, b.BrandName FROM tblvehicles v
            LEFT JOIN tblbrands b ON v.VehicleBrand = b.id WHERE 1=1";
    if ($brand)    $sql .= " AND v.VehicleBrand = " . intval($brand);
    if ($fuel)     { $fuel = sanitize($fuel); $sql .= " AND v.FuelType = '$fuel'"; }
    if ($min)      $sql .= " AND v.PricePerDay >= " . intval($min);
    if ($max)      $sql .= " AND v.PricePerDay <= " . intval($max);
    $sortMap = [
        'latest' => 'v.id DESC',
        'price-low' => 'v.PricePerDay ASC',
        'price-high' => 'v.PricePerDay DESC',
        'name' => 'v.VehicleTitle ASC'
    ];
    $sql .= " ORDER BY " . ($sortMap[$sort] ?? $sortMap['latest']);
    $r = $conn->query($sql);
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}
?>

<main class="listing-page">
  <section class="listing-hero">
    <div class="container listing-hero__inner">
      <div><span class="eyebrow"><i class="fas fa-layer-group"></i> The collection</span><h1>Available cars,<br><em>your way.</em></h1><p>From quick city runs to wide-open weekends, find the right shape for the road ahead.</p></div>
      <div class="listing-hero__count"><strong><?= count($vehicles) ?></strong><span>cars ready<br>to explore</span></div>
    </div>
  </section>

  <section class="listing-section">
    <div class="container">
      <div class="listing-layout">
        <aside class="listing-filters">
          <div class="listing-kicker"><span>01</span><span>Refine the drive</span></div>
          <h2>Find your<br><em>fit.</em></h2>
          <form method="get">
            <div class="mb-3"><label class="form-label" for="listing-brand">Brand</label><select id="listing-brand" name="brand" class="form-select"><option value="">All Brands</option><?php foreach ($brands as $b): ?><option value="<?= $b['id'] ?>" <?= $brand == $b['id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['BrandName']) ?></option><?php endforeach; ?></select></div>
            <div class="mb-3"><label class="form-label" for="listing-fuel">Fuel type</label><select id="listing-fuel" name="fuel_type" class="form-select"><option value="">All types</option><?php foreach (['Petrol','Diesel','CNG','Electric'] as $f): ?><option <?= $fuelType == $f ? 'selected' : '' ?>><?= $f ?></option><?php endforeach; ?></select></div>
            <div class="mb-3"><label class="form-label" for="listing-price">Maximum per day</label><div class="input-group"><span class="input-group-text">रू</span><input id="listing-price" type="number" name="max_price" class="form-control" value="<?= htmlspecialchars($maxPrice ?? '') ?>" placeholder="No limit"></div></div>
            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
            <button class="btn btn-primary w-100"><i class="fas fa-sliders"></i> Apply filters</button>
          </form>
          <?php if ($brand || $fuelType || $maxPrice): ?><a class="listing-clear" href="<?= SITEURL ?>car-listing.php"><i class="fas fa-rotate-left"></i> Clear all filters</a><?php endif; ?>
        </aside>

        <div class="listing-results" data-listing-results>
          <div class="listing-toolbar">
            <div><strong data-result-count><?= count($vehicles) ?></strong> vehicles <span class="listing-toolbar__muted">matching your search</span></div>
            <div class="listing-toolbar__controls"><div class="listing-quick-filters" data-quick-filters><button type="button" class="is-active" data-quick-filter="all">All</button><button type="button" data-quick-filter="Petrol">Petrol</button><button type="button" data-quick-filter="Diesel">Diesel</button></div><select class="form-select form-select-sm" data-listing-sort aria-label="Sort vehicles"><option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>>Latest</option><option value="price-low" <?= $sort === 'price-low' ? 'selected' : '' ?>>Price: low to high</option><option value="price-high" <?= $sort === 'price-high' ? 'selected' : '' ?>>Price: high to low</option><option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name</option></select><button type="button" class="listing-view-button is-active" data-listing-view="grid" aria-label="Grid view"><i class="fas fa-grip"></i></button><button type="button" class="listing-view-button" data-listing-view="list" aria-label="List view"><i class="fas fa-list"></i></button></div>
          </div>
          <div class="listing-grid" data-listing-grid>
                <?php if (empty($vehicles)): ?>
                    <div class="listing-empty"><i class="fas fa-road"></i><h3>No cars on this route yet.</h3><p>Try widening your filters and we’ll show you what’s available.</p><a href="<?= SITEURL ?>car-listing.php" class="btn btn-outline-primary">Reset search</a></div>
                <?php else: foreach ($vehicles as $v): ?>
                    <article class="listing-card" data-listing-card data-fuel="<?= htmlspecialchars($v['FuelType']) ?>" data-price="<?= (int)$v['PricePerDay'] ?>" data-name="<?= htmlspecialchars($v['VehicleTitle']) ?>">
                        <div class="listing-card__image"><img src="<?= getVehicleImage($v['Vimage1']) ?>" alt="<?= htmlspecialchars($v['VehicleTitle']) ?>"><span><?= htmlspecialchars($v['FuelType']) ?></span></div>
                        <div class="listing-card__body"><div class="listing-card__meta"><span><?= htmlspecialchars($v['BrandName']) ?></span><span><?= (int)$v['ModelYear'] ?></span></div><h3><?= htmlspecialchars($v['VehicleTitle']) ?></h3><div class="listing-card__bottom"><strong><?= formatPrice($v['PricePerDay']) ?><small>/day</small></strong><a href="<?= SITEURL ?>car-details.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary">View <i class="fas fa-arrow-right"></i></a></div></div>
                    </article>
                <?php endforeach; endif; ?>
          </div>
          <div class="listing-no-match" data-listing-no-match hidden><i class="fas fa-compass"></i><strong>Nothing matches that quick filter.</strong><button type="button" data-reset-quick-filter>Show every car</button></div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>