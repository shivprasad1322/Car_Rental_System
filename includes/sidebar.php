<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$sidebarUser = $_SESSION['user_name'] ?? 'Guest driver';
?>

<aside class="account-sidebar" aria-label="Account navigation">
	<div class="account-sidebar__identity">
		<span class="account-sidebar__avatar"><i class="fas fa-user"></i></span>
		<div>
			<span class="eyebrow">Your account</span>
			<strong><?= htmlspecialchars($sidebarUser) ?></strong>
		</div>
	</div>

	<nav class="account-sidebar__nav">
		<?php if (isLoggedIn()): ?>
			<span class="account-sidebar__label">Manage</span>
			<a class="<?= $currentPage === 'profile.php' ? 'active' : '' ?>" href="<?= SITEURL ?>profile.php">
				<i class="fas fa-user-pen"></i> My profile
			</a>
			<a class="<?= $currentPage === 'my-bookings.php' ? 'active' : '' ?>" href="<?= SITEURL ?>my-bookings.php">
				<i class="fas fa-calendar-check"></i> My bookings
			</a>
			<a class="account-sidebar__logout" href="<?= SITEURL ?>logout.php">
				<i class="fas fa-arrow-right-from-bracket"></i> Sign out
			</a>
		<?php else: ?>
			<span class="account-sidebar__label">Already a member?</span>
			<a class="account-sidebar__login" href="<?= SITEURL ?>login.php">
				<i class="fas fa-arrow-right-to-bracket"></i> Sign in
			</a>
		<?php endif; ?>
	</nav>
</aside>
