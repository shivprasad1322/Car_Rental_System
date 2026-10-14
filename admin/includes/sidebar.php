<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<div class="admin-mobile-bar"><button type="button" data-admin-menu-toggle aria-expanded="false"><i class="fas fa-bars"></i><span>Admin workspace</span></button></div>
<div class="admin-sidebar-backdrop" data-admin-menu-close></div>
<aside class="sidebar p-3" data-admin-sidebar>
  <div class="admin-sidebar__head"><h5 class="mb-3"><i class="fas fa-car"></i> Admin Panel</h5><button type="button" data-admin-menu-close aria-label="Close admin menu"><i class="fas fa-xmark"></i></button></div>
  <ul class="nav flex-column">
    <li><a class="nav-link <?= $cur=='dashboard.php'?'active':'' ?>" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
    <li><a class="nav-link <?= $cur=='manage-brands.php'?'active':'' ?>" href="manage-brands.php"><i class="fas fa-tags me-2"></i> Brands</a></li>
    <li><a class="nav-link <?= in_array($cur,['manage-vehicles.php','add-vehicle.php','edit-vehicle.php'])?'active':'' ?>" href="manage-vehicles.php"><i class="fas fa-car me-2"></i> Vehicles</a></li>
    <li class="mt-2 text-white-50 small ps-2">Bookings</li>
    <li><a class="nav-link <?= $cur=='new-bookings.php'?'active':'' ?>" href="new-bookings.php"><i class="fas fa-hourglass-half me-2"></i> New</a></li>
    <li><a class="nav-link <?= $cur=='confirmed-bookings.php'?'active':'' ?>" href="confirmed-bookings.php"><i class="fas fa-check me-2"></i> Confirmed</a></li>
    <li><a class="nav-link <?= $cur=='canceled-bookings.php'?'active':'' ?>" href="canceled-bookings.php"><i class="fas fa-times me-2"></i> Cancelled</a></li>
    <li><a class="nav-link <?= in_array($cur,['billing.php','add-bill.php','edit-bill.php'])?'active':'' ?>" href="billing.php"><i class="fas fa-file-invoice-dollar me-2"></i> Billing</a></li>
    <li><a class="nav-link <?= in_array($cur,['bill-designs.php','add-bill-design.php','edit-bill-design.php'])?'active':'' ?>" href="bill-designs.php"><i class="fas fa-palette me-2"></i> Bill Designs</a></li>
    <li class="mt-2"></li>
    <li><a class="nav-link <?= $cur=='manage-testimonials.php'?'active':'' ?>" href="manage-testimonials.php"><i class="fas fa-star me-2"></i> Testimonials</a></li>
    <li><a class="nav-link <?= $cur=='manage-users.php'?'active':'' ?>" href="manage-users.php"><i class="fas fa-users me-2"></i> Users</a></li>
    <li><a class="nav-link <?= $cur=='manage-contact.php'?'active':'' ?>" href="manage-contact.php"><i class="fas fa-envelope me-2"></i> Contact Queries</a></li>
    <li><a class="nav-link <?= $cur=='manage-pages.php'?'active':'' ?>" href="manage-pages.php"><i class="fas fa-file me-2"></i> Pages</a></li>
    <li><a class="nav-link <?= $cur=='manage-subscribers.php'?'active':'' ?>" href="manage-subscribers.php"><i class="fas fa-bell me-2"></i> Subscribers</a></li>
    <li><a class="nav-link <?= $cur=='change-password.php'?'active':'' ?>" href="change-password.php"><i class="fas fa-key me-2"></i> Change Password</a></li>
    <li><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
  </ul>
</aside>
<script src="../assets/js/main.js"></script>