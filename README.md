# Car Rental Management System

A PHP and MySQL car-rental portal for browsing vehicles, registering customers, submitting rental bookings, and managing the rental catalog through an admin panel.

The project is structured for local development with XAMPP and uses Bootstrap 5, Font Awesome, jQuery, and DataTables from CDNs.

## Features

### Customer portal

- Home page with featured vehicles and vehicle search.
- Vehicle listing with filters for:
  - Brand
  - Fuel type
  - Maximum price per day
- Vehicle detail pages with:
  - Vehicle overview
  - Image carousel
  - Model, fuel type, seating, and daily price
  - Feature list
  - Booking form
- Customer registration and login.
- Session-based authentication.
- Password reset demo that generates a temporary password.
- Logged-in users can view their booking history.
- Date validation for bookings:
  - Pickup date cannot be in the past.
  - Return date must be after the pickup date.
  - Confirmed bookings block overlapping dates for the same vehicle.
- Contact form with messages stored in the database.
- Newsletter subscription form.
- About, terms, and privacy content managed through database-backed pages.

### Admin portal

The admin portal is available under `/admin/` and includes:

- Dashboard with counts for users, vehicles, bookings, brands, subscribers, and testimonials.
- Brand creation, editing, and deletion.
- Vehicle creation, editing, image upload, and deletion.
- Booking status management:
  - New / pending
  - Confirmed
  - Cancelled
- Billing management with invoice totals and payment status tracking.
- User listing and deletion.
- Contact query listing, read status, and deletion.
- Testimonial approval and deletion.
- Newsletter subscriber listing and deletion.
- Page content management.
- Admin password change.
- Admin logout.

## Requirements

- Windows with XAMPP, or an equivalent Apache/PHP/MySQL environment.
- Apache with PHP enabled.
- MySQL or MariaDB.
- PHP 7.4 or newer is recommended.
- PHP extensions:
  - `mysqli`
  - `mbstring`
  - `fileinfo` for image uploads
- A browser with internet access for CDN-hosted Bootstrap, Font Awesome, jQuery, and DataTables assets.

## Installation With XAMPP

1. Copy or clone the project into the XAMPP web root:

   ```text
   C:\xampp\htdocs\car-rental-system
   ```

2. Start **Apache** and **MySQL** from the XAMPP Control Panel.

3. Open phpMyAdmin:

   ```text
   http://localhost/phpmyadmin
   ```

4. Import [`database.sql`](database.sql). The script creates the `car_rental_db` database and inserts sample brands, vehicles, contact information, pages, and an admin account.

  Existing installations should re-import the updated billing table definition or run the `tblbilling` section from [`database.sql`](database.sql) before opening the admin Billing page.

5. Confirm the database settings in [`includes/config.php`](includes/config.php):

   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'car_rental_db');
   ```

   The default configuration assumes the XAMPP MySQL `root` user has no password.

6. Open the customer portal:

   ```text
   http://localhost/car-rental-system/
   ```

7. Open the admin portal:

   ```text
   http://localhost/car-rental-system/admin/
   ```

## Default Admin Login

The database seed contains this demo account:

| Field | Value |
| --- | --- |
| Username | `admin` |
| Password | `admin123` |

Change the password immediately after the first login from **Admin Panel > Change Password**. Do not use the seeded credentials in a public or production deployment.

## Typical User Flow

1. Open the home page or vehicle listing.
2. Filter vehicles by brand, fuel type, or maximum daily price.
3. Open a vehicle's detail page.
4. Register or log in.
5. Select pickup and return dates and submit the booking.
6. The booking is saved with status `0` (pending).
7. View the booking from **My Bookings**.
8. An administrator confirms or cancels the booking from the admin panel.
9. Availability checks consider overlapping bookings only when their status is confirmed.

## Booking Status Values

The `tblbooking.Status` column uses these values:

| Status | Meaning | Display |
| ---: | --- | --- |
| `0` | Pending / new booking | Yellow badge |
| `1` | Confirmed booking | Green badge |
| `2` | Cancelled booking | Red badge |

## Project Structure

```text
car-rental-system/
├── index.php                 # Customer home page
├── about.php                 # About page
├── car-listing.php           # Vehicle listing and filters
├── car-details.php           # Vehicle details and booking submission
├── booking.php               # Currently empty; not used by the booking flow
├── contact.php               # Contact form
├── forgot-password.php       # Demo temporary-password reset
├── login.php                 # Customer login
├── logout.php                # Customer logout
├── my-bookings.php           # Logged-in customer's bookings
├── register.php              # Customer registration
├── subscribe.php             # Newsletter subscription endpoint
├── database.sql              # Database schema and seed data
├── includes/
│   ├── config.php            # Database, session, URL, and helper configuration
│   ├── functions.php         # Authentication, queries, availability, and dashboard helpers
│   ├── header.php            # Shared customer header/navigation
│   ├── footer.php            # Shared customer footer/scripts
│   └── sidebar.php           # Shared customer sidebar component, if used
├── assets/
│   ├── css/style.css         # Customer and shared styles
│   ├── js/main.js            # Customer-side JavaScript
│   └── images/               # Customer-facing images and fallback image
└── admin/
    ├── index.php             # Admin login
    ├── dashboard.php         # Admin dashboard
    ├── add-vehicle.php       # Add vehicle
    ├── edit-vehicle.php      # Edit vehicle and replace images
    ├── manage-vehicles.php   # Vehicle list and deletion
    ├── create-brand.php      # Add brand
    ├── edit-brand.php        # Edit brand
    ├── manage-brands.php     # Brand list and deletion
    ├── new-bookings.php      # Pending bookings
    ├── confirmed-bookings.php # Confirmed bookings
    ├── canceled-bookings.php # Cancelled bookings
    ├── manage-bookings.php   # Booking management entry point
    ├── billing.php           # Invoice and payment management
    ├── manage-users.php      # User list and deletion
    ├── manage-contact.php    # Contact query management
    ├── manage-pages.php      # Page content management
    ├── manage-subscribers.php # Newsletter subscribers
    ├── manage-testimonials.php # Testimonial moderation
    ├── change-password.php   # Admin password change
    ├── logout.php            # Admin logout
    ├── includes/             # Shared admin components
    ├── css/                  # Admin styles
    ├── js/                   # Admin scripts
    └── img/vehicles/         # Uploaded vehicle images
```

## Database Tables

`database.sql` creates the following tables:

- `admin`: administrator credentials.
- `tblbrands`: vehicle brands.
- `tblvehicles`: vehicle details, pricing, images, and features.
- `tblusers`: customer accounts.
- `tblbooking`: customer booking requests and statuses.
- `tblbilling`: booking invoices, rental totals, and payment status.
- `tblcontactusinfo`: public contact details.
- `tblcontactusquery`: submitted contact messages.
- `tblpages`: About, terms, and privacy page content.
- `tblsubscribers`: newsletter subscribers.
- `tbltestimonials`: testimonials and approval status.

Passwords are stored with PHP's `password_hash()` and verified with `password_verify()`.

## Configuration

The main settings are in [`includes/config.php`](includes/config.php):

- Database host, username, password, and database name.
- Timezone: `Asia/Kolkata`.
- Customer base URL: `http://localhost/car-rental-system/`.
- Admin base URL: customer URL plus `admin/`.

If the project is installed under a different folder or host, update `SITEURL` and verify that the Apache virtual host points to the project directory.

## Vehicle Images

Vehicle images are stored in:

```text
admin/img/vehicles/
```

The database stores image filenames in `Vimage1` through `Vimage5`. The application builds the public URL from those filenames. Uploaded filenames are prefixed with a timestamp when vehicles are edited.

If a vehicle has no image, the application uses:

```text
assets/images/no-image.jpg
```

Make sure that fallback image exists before testing vehicles without images.

## Troubleshooting

### Database connection failed

- Confirm MySQL is running in XAMPP.
- Confirm the database `car_rental_db` exists.
- Check the credentials in `includes/config.php`.
- Re-import `database.sql` if tables are missing.

### The site redirects to the wrong URL

Update `SITEURL` in `includes/config.php` to match the folder name and local host URL, including the trailing slash.

### Vehicle images do not appear

- Confirm image files are under `admin/img/vehicles/`.
- Confirm the filenames in `tblvehicles` match the actual files.
- Confirm `assets/images/no-image.jpg` exists for fallback rendering.
- Check that PHP has permission to read uploaded files.

### Uploads fail in the admin panel

- Confirm the `admin/img/vehicles/` directory exists or can be created by PHP.
- Check PHP upload limits such as `upload_max_filesize` and `post_max_size` in `php.ini`.
- Restart Apache after changing `php.ini`.

### A booking is not considered unavailable

Availability checks only exclude overlapping bookings whose status is `1` (confirmed). Pending bookings do not block another booking until an administrator confirms them.

## Current Limitations and Production Notes

This repository is a local/demo application and should be hardened before production use:

- `forgot-password.php` displays a generated temporary password directly in the browser. A production implementation should email a single-use, expiring reset token instead.
- Several database writes use SQL strings assembled in PHP. Use prepared statements throughout the application before exposing it publicly.
- Add CSRF protection to all state-changing forms and admin actions.
- Add server-side authorization and validation for uploaded file type, size, and content.
- Restrict or remove the seeded admin credentials in production.
- Add unique handling for newsletter subscribers if duplicate addresses should be rejected.
- Add a real email or notification workflow for booking confirmations and contact messages.
- `booking.php` is currently empty and is not part of the active booking flow. Bookings are submitted from `car-details.php`.
- No automated test suite or dependency lockfile is currently included.
- CDN assets require internet access; vendor them locally if the deployment must work offline.

## Development Notes

The application uses server-rendered PHP pages and shared includes rather than a framework or build pipeline. There is no Composer or npm installation step in the current project. After changing PHP files, refresh the relevant page in the browser and check the Apache/PHP error log for runtime errors.

## License

No license file is currently included. Add an appropriate license before distributing the project outside its intended environment.
#   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 #   C a r _ R e n t a l _ S y s t e m  
 