-- Car Rental Management System
CREATE DATABASE IF NOT EXISTS car_rental_db DEFAULT CHARACTER SET utf8mb4;
USE car_rental_db;

-- ----------------------------
-- Admin table
-- ----------------------------
CREATE TABLE admin (
  id INT(11) NOT NULL AUTO_INCREMENT,
  UserName VARCHAR(100) NOT NULL,
  Password VARCHAR(255) NOT NULL,
  updationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- password = admin123  (hash generated with password_hash('admin123', PASSWORD_DEFAULT))
INSERT INTO admin (UserName, Password) VALUES
('admin', '$2y$10$R3d7AQmtuY0tr65L3XqHZuXvO6oNmfvCp1OLt0MJmrtc3pEGAVmd2');

-- ----------------------------
-- Brands
-- ----------------------------
CREATE TABLE tblbrands (
  id INT(11) NOT NULL AUTO_INCREMENT,
  BrandName VARCHAR(150) NOT NULL,
  CreationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UpdationDate TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tblbrands (BrandName) VALUES
('Maruti Suzuki'),('BMW'),('Audi'),('Nissan'),
('Toyota'),('Volkswagen'),('Hyundai'),('Honda');

-- ----------------------------
-- Vehicles
-- ----------------------------
CREATE TABLE tblvehicles (
  id INT(11) NOT NULL AUTO_INCREMENT,
  VehicleNumber VARCHAR(50) NOT NULL,
  VehicleTitle VARCHAR(150) DEFAULT NULL,
  VehicleBrand INT(11) DEFAULT NULL,
  VehicleOverview LONGTEXT DEFAULT NULL,
  PricePerDay INT(11) DEFAULT NULL,
  FuelType VARCHAR(100) DEFAULT NULL,
  ModelYear INT(6) DEFAULT NULL,
  SeatingCapacity INT(11) DEFAULT NULL,
  Vimage1 VARCHAR(120) DEFAULT NULL,
  Vimage2 VARCHAR(120) DEFAULT NULL,
  Vimage3 VARCHAR(120) DEFAULT NULL,
  Vimage4 VARCHAR(120) DEFAULT NULL,
  Vimage5 VARCHAR(120) DEFAULT NULL,
  AirConditioner INT(11) DEFAULT 0,
  PowerDoorLocks INT(11) DEFAULT 0,
  AntiLockBrakingSystem INT(11) DEFAULT 0,
  BrakeAssist INT(11) DEFAULT 0,
  PowerSteering INT(11) DEFAULT 0,
  DriverAirbag INT(11) DEFAULT 0,
  PassengerAirbag INT(11) DEFAULT 0,
  PowerWindows INT(11) DEFAULT 0,
  CDPlayer INT(11) DEFAULT 0,
  CentralLocking INT(11) DEFAULT 0,
  CrashSensor INT(11) DEFAULT 0,
  LeatherSeats INT(11) DEFAULT 0,
  RegDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UpdationDate TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_vehicle_number (VehicleNumber),
  KEY fk_brand (VehicleBrand)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tblvehicles
(VehicleNumber,VehicleTitle,VehicleBrand,VehicleOverview,PricePerDay,FuelType,ModelYear,SeatingCapacity,Vimage1,AirConditioner,PowerDoorLocks,AntiLockBrakingSystem,BrakeAssist,PowerSteering,DriverAirbag,PassengerAirbag,PowerWindows,CDPlayer,CentralLocking,CrashSensor,LeatherSeats)
VALUES
('CAR-001','Maruti Suzuki Wagon R',1,'Compact, fuel-efficient hatchback perfect for city driving.',1500,'Petrol',2019,5,'wagonr.jpg',1,1,1,1,1,1,1,1,1,1,1,0),
('CAR-002','BMW 5 Series',2,'Luxury sedan with premium features and exceptional performance.',8000,'Petrol',2018,5,'bmw5.jpg',1,1,1,1,1,1,1,1,1,1,1,1),
('CAR-003','Audi Q8',3,'Premium SUV with advanced technology and comfort.',10000,'Petrol',2017,5,'audiq8.jpg',1,1,1,1,1,1,1,1,1,1,1,1),
('CAR-004','Nissan Kicks',4,'Stylish compact SUV with good mileage.',2500,'Petrol',2020,5,'kicks.jpg',1,1,1,1,1,1,1,1,1,1,1,0),
('CAR-005','Toyota Fortuner',5,'Powerful SUV suitable for all terrains.',5000,'Diesel',2018,7,'fortuner.jpg',1,1,1,1,1,1,1,1,1,1,1,1),
('CAR-006','Hyundai Creta',7,'Popular compact SUV with modern features.',3000,'Petrol',2021,5,'creta.jpg',1,1,1,1,1,1,1,1,1,1,1,0),
('CAR-007','Honda City',8,'Reliable sedan with excellent fuel economy.',2800,'Petrol',2020,5,'city.jpg',1,1,1,1,1,1,1,1,1,1,1,0),
('CAR-008','Volkswagen Polo',6,'German-engineered hatchback with solid build.',2200,'Petrol',2019,5,'polo.jpg',1,1,1,1,1,1,1,1,1,1,1,0);

-- ----------------------------
-- Users
-- ----------------------------
CREATE TABLE tblusers (
  id INT(11) NOT NULL AUTO_INCREMENT,
  FullName VARCHAR(120) DEFAULT NULL,
  EmailId VARCHAR(100) DEFAULT NULL,
  Password VARCHAR(255) DEFAULT NULL,
  ContactNo CHAR(11) DEFAULT NULL,
  dob VARCHAR(100) DEFAULT NULL,
  Address VARCHAR(255) DEFAULT NULL,
  City VARCHAR(100) DEFAULT NULL,
  Country VARCHAR(100) DEFAULT NULL,
  RegDate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  UpdationDate TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_email (EmailId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Bookings
-- ----------------------------
CREATE TABLE tblbooking (
  id INT(11) NOT NULL AUTO_INCREMENT,
  BookingNumber BIGINT(12) DEFAULT NULL,
  userEmail VARCHAR(100) DEFAULT NULL,
  VehicleId INT(11) DEFAULT NULL,
  FromDate VARCHAR(20) DEFAULT NULL,
  ToDate VARCHAR(20) DEFAULT NULL,
  message VARCHAR(255) DEFAULT NULL,
  Status INT(11) DEFAULT 0,
  PostingDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  LastUpdationDate TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Contact Us Info
-- ----------------------------
CREATE TABLE tblcontactusinfo (
  id INT(11) NOT NULL AUTO_INCREMENT,
  Address TEXT DEFAULT NULL,
  EmailId VARCHAR(255) DEFAULT NULL,
  ContactNo VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tblcontactusinfo (Address,EmailId,ContactNo) VALUES
('Ramanand Chowk, Janakpur','info@carrental.com','+977-9807627575');

-- ----------------------------
-- Contact Queries
-- ----------------------------
CREATE TABLE tblcontactusquery (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) DEFAULT NULL,
  EmailId VARCHAR(120) DEFAULT NULL,
  ContactNo CHAR(11) DEFAULT NULL,
  Message TEXT DEFAULT NULL,
  PostingDate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  status INT(11) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Pages
-- ----------------------------
CREATE TABLE tblpages (
  id INT(11) NOT NULL AUTO_INCREMENT,
  PageName VARCHAR(255) DEFAULT NULL,
  type VARCHAR(255) NOT NULL,
  detail TEXT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tblpages (PageName,type,detail) VALUES
('About Us','aboutus','We offer a varied fleet of cars ranging from compact to luxury. All our vehicles have air conditioning, power steering, and electric windows.'),
('Terms and Conditions','terms','By using our car rental services you agree to the following terms and conditions...'),
('Privacy Policy','privacy','We are committed to protecting your privacy...');

-- ----------------------------
-- Subscribers
-- ----------------------------
CREATE TABLE tblsubscribers (
  id INT(11) NOT NULL AUTO_INCREMENT,
  SubscriberEmail VARCHAR(120) DEFAULT NULL,
  PostingDate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Testimonials
-- ----------------------------
CREATE TABLE tbltestimonials (
  id INT(11) NOT NULL AUTO_INCREMENT,
  UserEmail VARCHAR(100) NOT NULL,
  Testimonial MEDIUMTEXT NOT NULL,
  PostingDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status INT(11) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Billing
-- ----------------------------
CREATE TABLE tblbilling (
  id INT(11) NOT NULL AUTO_INCREMENT,
  booking_id INT(11) NOT NULL,
  invoice_number VARCHAR(40) NOT NULL,
  rental_days INT(11) NOT NULL DEFAULT 1,
  daily_rate DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  payment_status ENUM('unpaid','paid','void') NOT NULL DEFAULT 'unpaid',
  payment_method VARCHAR(50) DEFAULT NULL,
  paid_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_billing_booking (booking_id),
  UNIQUE KEY uq_billing_invoice (invoice_number),
  KEY idx_billing_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Bill designs
-- ----------------------------
CREATE TABLE tblbilldesigns (
  id INT(11) NOT NULL AUTO_INCREMENT,
  design_name VARCHAR(100) NOT NULL,
  company_name VARCHAR(150) NOT NULL,
  tagline VARCHAR(150) DEFAULT NULL,
  pan_number VARCHAR(50) DEFAULT NULL,
  address VARCHAR(255) DEFAULT NULL,
  email VARCHAR(150) DEFAULT NULL,
  phone VARCHAR(50) DEFAULT NULL,
  website VARCHAR(150) DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  primary_color VARCHAR(7) NOT NULL DEFAULT '#203b68',
  accent_color VARCHAR(7) NOT NULL DEFAULT '#f3ba3f',
  signature_name VARCHAR(100) NOT NULL DEFAULT 'Admin',
  signature_post VARCHAR(100) NOT NULL DEFAULT 'Authorized Sign',
  is_active TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tblbilldesigns
(design_name, company_name, tagline, pan_number, address, email, phone, website, notes, is_active)
VALUES
('Default design', 'ANTARES', 'Car Rental', '108318630', '1234 Car Avenue Citysville, CA 12345', 'velocitywheels@email.com', '210-788-8829', 'www.velocitywheels.com', 'Thank you for choosing Antares Car Rental for your car rental needs. If you have any questions regarding this invoice, please contact us using the details above.', 1);