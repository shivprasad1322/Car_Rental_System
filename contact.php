<?php
$pageTitle = 'Contact Us';
require_once 'includes/header.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = sanitize($_POST['name']);
    $e = sanitize($_POST['email']);
    $p = sanitize($_POST['phone']);
  $topic = sanitize($_POST['topic'] ?? 'General enquiry');
  $m = sanitize($_POST['message']);
  $m = '[' . $topic . '] ' . $m;
    $sql = "INSERT INTO tblcontactusquery (name,EmailId,ContactNo,Message)
            VALUES ('$n','$e','$p','$m')";
    if ($conn->query($sql)) $_SESSION['success'] = "Message sent. We'll contact you soon.";
    else                    $_SESSION['error']   = "Could not send message.";
    redirect(SITEURL . 'contact.php');
}
?>

<?php $contactResult = $conn->query("SELECT * FROM tblcontactusinfo LIMIT 1"); ?>
<?php $ci = $contactResult ? $contactResult->fetch_assoc() : []; ?>

<main class="contact-page">
  <section class="contact-hero">
    <div class="container contact-hero__inner">
      <div>
        <span class="eyebrow"><i class="fas fa-signal"></i> We are listening</span>
        <h1>Let’s get<br><em>you moving.</em></h1>
        <p>Questions, a booking idea, or just a route worth talking about? Send us a note and a real person will pick it up.</p>
      </div>
      <div class="contact-hero__mark" aria-hidden="true"><i class="fas fa-paper-plane"></i><span>HELLO<br>THERE</span></div>
    </div>
  </section>

  <section class="contact-section">
    <div class="container">
      <div class="contact-layout">
        <div class="contact-form-wrap" data-contact-form>
          <div class="contact-kicker"><span>01</span><span>Start a conversation</span></div>
          <h2>Tell us what’s<br><em>on your mind.</em></h2>
          <form method="post">
            <div class="contact-topic" data-contact-topics>
              <span class="form-label">I’m reaching out about</span>
              <div class="contact-topic__buttons" role="group" aria-label="Message topic">
                <button type="button" class="contact-topic__button is-active" data-topic="Booking help">Booking help</button>
                <button type="button" class="contact-topic__button" data-topic="Choosing a car">Choosing a car</button>
                <button type="button" class="contact-topic__button" data-topic="Something else">Something else</button>
              </div>
              <input type="hidden" name="topic" value="Booking help" data-contact-topic-value>
            </div>
            <div class="contact-form-grid">
              <div><label class="form-label" for="contact-name">Your name</label><input id="contact-name" name="name" class="form-control" autocomplete="name" required></div>
              <div><label class="form-label" for="contact-email">Email address</label><input id="contact-email" type="email" name="email" class="form-control" autocomplete="email" required></div>
            </div>
            <div class="contact-form-grid">
              <div><label class="form-label" for="contact-phone">Phone <span>(optional)</span></label><input id="contact-phone" name="phone" class="form-control" maxlength="11" autocomplete="tel"></div>
              <div class="contact-response"><i class="fas fa-clock"></i><span data-contact-response>Booking questions usually get a reply within 2 hours.</span></div>
            </div>
            <div class="contact-message-field"><label class="form-label" for="contact-message">Your message</label><textarea id="contact-message" name="message" rows="5" maxlength="600" class="form-control" placeholder="Give us a little context..." required></textarea><span class="contact-counter"><strong data-contact-count>0</strong> / 600</span></div>
            <button class="btn btn-primary contact-submit" type="submit">Send message <i class="fas fa-arrow-right"></i></button>
          </form>
        </div>

        <aside class="contact-aside">
          <div class="contact-kicker"><span>02</span><span>Find us here</span></div>
          <h3>Prefer a<br><em>direct line?</em></h3>
          <div class="contact-details">
            <a href="tel:<?= htmlspecialchars($ci['ContactNo'] ?? '+977-9807627575') ?>"><i class="fas fa-phone"></i><span><small>Call the team</small><?= htmlspecialchars($ci['ContactNo'] ?? '+977-9807627575') ?></span><i class="fas fa-arrow-up-right-from-square"></i></a>
            <a href="mailto:<?= htmlspecialchars($ci['EmailId'] ?? 'info@carrental.com') ?>"><i class="fas fa-envelope"></i><span><small>Send an email</small><?= htmlspecialchars($ci['EmailId'] ?? 'info@carrental.com') ?></span><i class="fas fa-arrow-up-right-from-square"></i></a>
            <div><i class="fas fa-location-dot"></i><span><small>Visit our desk</small><?= htmlspecialchars($ci['Address'] ?? 'Ramanand Chowk, Janakpur') ?></span></div>
          </div>
          <div class="contact-hours"><span class="contact-status-dot"></span><div><strong>We’re available</strong><small>Every day, around the clock</small></div></div>
        </aside>
      </div>
    </div>
  </section>

  <section class="contact-faq-section">
    <div class="container contact-faq-grid">
      <div><span class="eyebrow">Before you send</span><h2>Maybe we’ve<br><em>answered it.</em></h2><p>Quick answers for the things people ask us most.</p></div>
      <div class="contact-faq">
        <details open><summary>How quickly will I hear back?<i class="fas fa-plus"></i></summary><p>Booking questions usually receive a reply within two hours. For general messages, our team will get back to you as soon as possible.</p></details>
        <details><summary>Can you help me choose a vehicle?<i class="fas fa-plus"></i></summary><p>Absolutely. Tell us how many people are travelling, where you are going, and your preferred budget. We can point you toward the best fit.</p></details>
        <details><summary>Where can I see my booking status?<i class="fas fa-plus"></i></summary><p>Sign in and open My Bookings to see your requests, confirmed trips, and cancelled bookings in one place.</p></details>
      </div>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>