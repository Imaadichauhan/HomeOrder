HomeOrder - Delivery Management System

HomeOrder is a web-based Delivery Management System developed using **PHP, MySQL, HTML, CSS, JavaScript, and Bootstrap**. It helps administrators manage customer orders, assign riders, track deliveries, manage COD collections, and monitor delivery performance through an easy-to-use dashboard.

---

Features

Admin Panel
- Secure Admin Login
- Dashboard with Analytics
- Manage Orders
- Add, Edit, Delete Orders
- Import Orders via CSV/Excel
- Assign Orders to Riders
- Search and Filter Orders
- Order Status Management
- Rider Management
- COD Management
- Settlement Reports
- Delivery Analytics
- Download Reports

---

Rider Panel
- Secure Rider Login
- View Assigned Orders
- Accept Orders
- Update Order Status
- Out for Delivery
- Delivered
- Cancel Delivery with Reason
- Upload Delivery Proof Image
- OTP Verification
- View Daily COD Collection
- Settlement History

---
Order Management
- Create Orders
- Edit Orders
- Delete Orders
- Search Orders
- Filter by Status
- Pending Orders
- Assigned Orders
- Delivered Orders
- Cancelled Orders

---

COD Management
- Rider-wise COD Collection
- Daily Collection
- Settlement Tracking
- Total COD Analytics
- Pending Settlement Report

---

Dashboard
- Total Orders
- Pending Orders
- Delivered Orders
- Cancelled Orders
- Total Revenue
- Rider Performance
- Delivery Statistics
- Interactive Charts

---

File Upload
- CSV Import
- Excel Import
- Delivery Proof Upload
- Image Validation

---

Technologies Used

### Frontend
- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- Chart.js

### Backend
- PHP 8+

### Database
- MySQL

### Server
- Apache (XAMPP)

---

Project Structure

```
HomeOrder/
│
├── admin/
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   ├── orders.php
│   ├── riders.php
│   ├── allorders.php
│   ├── import.php
│   └── reports.php
│
├── rider/
│   ├── login.php
│   ├── dashboard.php
│   ├── assigned_orders.php
│   ├── upload_proof.php
│   ├── settlement.php
│   └── logout.php
│
├── uploads/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── database/
│   └── homeorder.sql
│
├── config/
│   └── db.php
│
├── index.php
└── README.md
```

---

# Database Tables

## orders

| Column | Type |
|----------|---------|
| order_id | VARCHAR(50) |
| customer_name | VARCHAR(100) |
| phone | VARCHAR(20) |
| address | TEXT |
| city | VARCHAR(100) |
| state | VARCHAR(100) |
| order_item | VARCHAR(255) |
| quantity | INT |
| price | DECIMAL(10,2) |
| payment_method | VARCHAR(50) |
| rider_name | VARCHAR(100) |
| status | VARCHAR(30) |
| order_date | DATE |

---

## riders

- rider_id
- rider_name
- phone
- password
- status

---

## rider_cash

- id
- rider_name
- amount
- order_id
- collection_date

---

## rider_settlement

- settlement_id
- rider_name
- total_amount
- settled_amount
- pending_amount
- settlement_date

---

## admin

- id
- username
- password

---

# Installation

## Clone Repository

```bash
git clone https://github.com/yourusername/HomeOrder.git
```

---

## Move Project

Copy project into

```
xampp/htdocs/
```

---

## Create Database

Open phpMyAdmin

Create database

```
homeorder
```

Import

```
database/homeorder.sql
```

---

## Configure Database

Edit

```
config/db.php
```

```php
$host="localhost";
$user="root";
$password="";
$database="homeorder";
```

---

## Run Project

Start

- Apache
- MySQL

Visit

```
http://localhost/HomeOrder
```

---

# Future Improvements

- Live Rider Location Tracking
- Google Maps Integration
- SMS Notification
- WhatsApp Notification
- Email Notification
- QR Code Verification
- Customer Portal
- Mobile App
- Payment Gateway Integration
- REST API
- AI Delivery Prediction

---

# Screenshots

- Login Page
- Admin Dashboard
- Orders Management
- Rider Dashboard
- Reports
- COD Analytics

(Add screenshots inside `/screenshots` folder.)

---

# Security

- Password Hashing
- Session Authentication
- SQL Injection Prevention
- XSS Protection
- File Upload Validation
- Prepared Statements

---

# Author

**Aditya**

Software Developer

---

# License

This project is licensed under the MIT License.

---

## Project Workflow

Customer Order
        │
        ▼
Admin Creates Order
        │
        ▼
Assign Rider
        │
        ▼
Rider Accepts Order
        │
        ▼
Out for Delivery
        │
        ▼
OTP Verification
        │
        ▼
Delivery Completed
        │
        ▼
COD Collection
        │
        ▼
Settlement Report
