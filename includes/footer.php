<footer class="site-footer">
  <div class="container">
    <div class="site-footer__top">
      <div class="site-footer__brand">
        <a class="site-footer__logo" href="<?= SITEURL ?>"><span class="brand-mark"><i class="fas fa-car"></i></span><span>Car Rental <em>Portal</em></span></a>
        <p>A little more freedom in every mile. Thoughtful cars, clear rates, and a human team behind every booking.</p>
        <div class="site-footer__socials" aria-label="Social links"><a href="https://www.facebook.com" target="_blank" rel="noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a><a href="https://www.instagram.com" target="_blank" rel="noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a><a href="https://www.linkedin.com" target="_blank" rel="noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></div>
      </div>
      <div class="site-footer__links">
        <details open><summary>Explore <i class="fas fa-chevron-down"></i></summary><a href="<?= SITEURL ?>about.php">About us</a><a href="<?= SITEURL ?>car-listing.php">Available cars</a><a href="<?= SITEURL ?>contact.php">Contact team</a></details>
      </div>
      <div class="site-footer__newsletter">
        <span class="eyebrow">Stay in motion</span><p>Occasional notes on new cars, better routes, and useful rental tips.</p>
        <form action="<?= SITEURL ?>subscribe.php" method="post" data-footer-newsletter>
          <label class="visually-hidden" for="footer-email">Email address</label><div class="site-footer__input"><input id="footer-email" type="email" name="email" placeholder="Your email address" autocomplete="email" required><button aria-label="Subscribe to newsletter"><i class="fas fa-arrow-right"></i></button></div>
          <small data-footer-newsletter-note>One useful note. Never noise.</small>
        </form>
      </div>
    </div>
    <div class="site-footer__bottom"><span>&copy; <?= date('Y') ?> Car Rental Portal</span><span>Made for the road ahead <i class="fas fa-arrow-right"></i></span><button type="button" class="back-to-top" data-back-to-top aria-label="Back to top"><i class="fas fa-arrow-up"></i></button></div>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITEURL ?>assets/js/main.js"></script>
</body>
</html>