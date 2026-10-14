<?php
$pageTitle = 'About Us';
require_once 'includes/header.php';
require_once 'includes/config.php';

$result = $conn->query("SELECT * FROM tblpages WHERE type='aboutus' LIMIT 1");
$row = $result ? $result->fetch_assoc() : null;
$aboutTitle = trim($row['PageName'] ?? 'About Us');
$aboutDetail = trim($row['detail'] ?? 'A better way to move through your city, on your own terms.');
?>

<main class="about-page">
    <section class="about-hero">
        <div class="container">
            <div class="about-hero__grid">
                <div class="about-hero__copy">
                    <span class="eyebrow"><i class="fas fa-route"></i> The road is yours</span>
                    <h1><?= htmlspecialchars($aboutTitle) ?>, with more room to roam.</h1>
                    <p class="about-hero__lead"><?= nl2br(htmlspecialchars($aboutDetail)) ?></p>
                    <div class="about-hero__actions">
                        <a href="#trip-planner" class="btn btn-primary">Plan a drive <i class="fas fa-arrow-down"></i></a>
                        <a href="<?= SITEURL ?>car-listing.php" class="btn btn-link">Explore the fleet <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="about-hero__signal" aria-label="Car Rental Portal highlights">
                    <div class="signal-orbit signal-orbit--outer"></div>
                    <div class="signal-orbit signal-orbit--inner"></div>
                    <div class="signal-core"><i class="fas fa-compass"></i><span>MOVE<br>WELL</span></div>
                    <span class="signal-note signal-note--top">01 / Curated</span>
                    <span class="signal-note signal-note--bottom">Since day one</span>
                </div>
            </div>
            <div class="about-stats" aria-label="Our service promises">
                <div><strong>24/7</strong><span>Human support</span></div>
                <div><strong>08+</strong><span>Trusted brands</span></div>
                <div><strong>01</strong><span>Simple promise: go</span></div>
            </div>
        </div>
    </section>

    <section class="about-section about-section--light about-section--reasons">
        <div class="container">
            <div class="section-heading about-heading"><span class="eyebrow">A rental for real life</span><h2>Different reasons.<br><em>Same feeling of freedom.</em></h2></div>
            <div class="about-tabs" data-about-tabs>
                <div class="about-tabs__nav" role="tablist" aria-label="Choose your kind of drive">
                    <button class="about-tab is-active" type="button" role="tab" aria-selected="true" data-about-tab="weekend"><span>01</span> Weekend escape</button>
                    <button class="about-tab" type="button" role="tab" aria-selected="false" data-about-tab="business"><span>02</span> Business, polished</button>
                    <button class="about-tab" type="button" role="tab" aria-selected="false" data-about-tab="family"><span>03</span> Room for everyone</button>
                </div>
                <div class="about-tabs__content">
                    <article class="about-tab-panel is-active" data-about-panel="weekend">
                        <span class="about-tab-panel__number">01</span><h3>Take the scenic route.</h3><p>Pick a car that makes the journey part of the story. Flexible dates, clear pricing, and a little more space for the unexpected.</p><a href="<?= SITEURL ?>car-listing.php" class="text-link">Find an escape-ready car <i class="fas fa-arrow-right"></i></a>
                    </article>
                    <article class="about-tab-panel" data-about-panel="business" hidden>
                        <span class="about-tab-panel__number">02</span><h3>Arrive with intention.</h3><p>From airport runs to back-to-back meetings, choose a dependable ride that keeps the day moving and leaves a lasting first impression.</p><a href="<?= SITEURL ?>car-listing.php" class="text-link">Browse refined rides <i class="fas fa-arrow-right"></i></a>
                    </article>
                    <article class="about-tab-panel" data-about-panel="family" hidden>
                        <span class="about-tab-panel__number">03</span><h3>Bring the whole plan.</h3><p>More seats, more bags, more room for the playlist. Our range is built for the people and detours that make a trip memorable.</p><a href="<?= SITEURL ?>car-listing.php" class="text-link">Find your family fit <i class="fas fa-arrow-right"></i></a>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section about-section--dark about-section--planner" id="trip-planner">
        <div class="container">
            <div class="planner-grid">
                <div>
                    <span class="eyebrow">Make it tangible</span>
                    <h2>What will your next<br><em>three days cost?</em></h2>
                    <p>Build a quick estimate, then find the car that fits the feeling.</p>
                    <div class="planner-marker"><span></span> Transparent by design</div>
                </div>
                <div class="planner-tool" data-trip-planner>
                    <div class="planner-tool__top"><span>Trip estimate</span><i class="fas fa-calculator"></i></div>
                    <div class="planner-tag"><span class="planner-tag__dot"></span>Live estimate</div>
                    <label for="planner-car">Choose a pace</label>
                    <select id="planner-car" class="form-select" data-planner-rate>
                        <option value="1500">City run · रू1,500 / day</option>
                        <option value="3000">Open road · रू3,000 / day</option>
                        <option value="8000">Make an entrance · रू8,000 / day</option>
                    </select>
                    <div class="planner-days"><label for="planner-days">Days away</label><output data-planner-days-output>3 days</output></div>
                    <input id="planner-days" type="range" min="1" max="14" value="3" data-planner-days>
                    <label class="planner-check"><input type="checkbox" data-planner-support> <span>Include roadside support</span><strong>+ रू500</strong></label>
                    <div class="planner-total"><span>Estimated total</span><strong data-planner-total>रू4,500</strong></div>
                    <a href="<?= SITEURL ?>car-listing.php" class="btn btn-primary w-100">See available cars <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section about-section--light about-section--faq about-faq">
        <div class="container about-faq__grid">
            <div class="section-heading about-heading"><span class="eyebrow">Good to know</span><h2>Questions before<br><em>the first mile?</em></h2></div>
            <div class="about-accordion">
                <details open><summary>What makes your pricing simple?<i class="fas fa-plus"></i></summary><p>You see the daily rate up front, with no hidden surprises added at the last step. Your final booking details show exactly what you are paying for.</p></details>
                <details><summary>Can I choose from different vehicle types?<i class="fas fa-plus"></i></summary><p>Yes. Explore the fleet by brand, fuel type, and maximum daily price to find something that suits your route and your budget.</p></details>
                <details><summary>What happens after I request a booking?<i class="fas fa-plus"></i></summary><p>Your request is saved as pending while our team reviews it. Once confirmed, the dates are held for you and appear in My Bookings.</p></details>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>