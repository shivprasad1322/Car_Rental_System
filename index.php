<?php
$pageTitle = 'Home';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$featured = getAllVehicles(4);
$brands   = getAllBrands();
?>

<section class="hero-section text-white py-5">
    <div class="container py-5 hero-shell">
        <div class="hero-copy">
            <span class="eyebrow"><i class="fas fa-sparkles"></i> Curated drives, made simple</span>
            <h1>Arrive in<br><span>your element.</span></h1>
            <p class="lead">Premium cars, transparent rates, and the freedom to take the long way home.</p>
        </div>

        <div class="search-panel-wrap">
            <div class="search-panel">
                <div class="search-panel-heading"><span>Build your journey</span></div>
                <form action="<?= SITEURL ?>car-listing.php" method="get" class="hero-search-form">
                    <label class="field field-select">
                        <select name="brand">
                            <option value="">Select Brand</option>
                            <?php foreach ($brands as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['BrandName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label class="field field-select">
                        <select name="fuel_type">
                            <option value="">Fuel Type</option>
                            <option>Petrol</option><option>Diesel</option>
                            <option>CNG</option><option>Electric</option>
                        </select>
                    </label>

                    <label class="field field-input">
                        <input type="number" name="max_price" placeholder="Max ₹ / day">
                    </label>

                    <button class="btn btn-primary hero-search-btn" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="section-heading"><span class="eyebrow">The collection</span><h2>Choose your next chapter</h2><p>Distinctive vehicles, cared for between every journey.</p></div>
        <div class="home-collection-layout">
            <article class="collection-photo-card">
                <img src="<?= SITEURL ?>assets/images/premium-card-photo.png" alt="Black luxury sedan driving on an open road">
                <div class="collection-photo-card__content">
                    <span class="eyebrow">Signature drive</span>
                    <h3>Take the long way home.</h3>
                    <p>Find a car that makes the journey part of the story.</p>
                    <a href="<?= SITEURL ?>car-listing.php" class="btn btn-light btn-sm">Explore the collection <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
            <div class="row row-cols-1 row-cols-sm-2 g-4">
                <?php foreach ($featured as $v): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="<?= getVehicleImage($v['Vimage1']) ?>"
                                 class="card-img-top" style="height:200px;object-fit:cover;"
                                 alt="<?= htmlspecialchars($v['VehicleTitle']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($v['VehicleTitle']) ?></h5>
                                <p class="text-muted mb-2"><?= htmlspecialchars($v['BrandName']) ?> | <?= (int)$v['ModelYear'] ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 text-primary mb-0"><?= formatPrice($v['PricePerDay']) ?>/day</span>
                                    <a href="<?= SITEURL ?>car-details.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="section-heading"><span class="eyebrow">The difference</span><h2>Made for moving well</h2></div>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <i class="fas fa-car fa-3x text-primary mb-3"></i>
                <h5>Wide Range</h5><p>Vehicles for every occasion.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-tags fa-3x text-primary mb-3"></i>
                <h5>Best Prices</h5><p>Competitive pricing, no hidden charges.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                <h5>24/7 Support</h5><p>Round-the-clock customer support.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>