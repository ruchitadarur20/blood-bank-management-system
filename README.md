Blood Bank Management System - Documentation
📌 Project Overview
The Blood Bank Management System is a web-based application designed to facilitate blood donation and management. The system allows donors to register, users to book appointments, and admins to manage blood inventory efficiently.

This project is built using:

Frontend: HTML, CSS, Bootstrap
Backend: PHP (secured with prepared statements)
Database: MySQL (using phpMyAdmin)
Version Control: Git & GitHub

🚀 Key Features
✅ User Features
Donor Registration: Users can sign up and register as donors.
Appointment Booking: Users can book appointments for blood donation.
Blood Inventory Viewing: Users can check available blood stock.
Secure Login & Authentication: Passwords are encrypted for security.
✅ Admin Features
Blood Inventory Management: Admins can add, update, and delete blood stock.
Manage Donors: Admins can approve or reject donor registrations.
Appointment Management: Admins can confirm or cancel appointment bookings.
Generate Reports: Track blood donation trends and statistics.

🛠 Installation & Setup
1️⃣ Clone the Repository
sh
Copy
Edit
git clone https://github.com/ruchitadarur20/blood_bank_Manager.git
cd blood_bank_Manager
2️⃣ Move Files to XAMPP htdocs (if using XAMPP)
sh
Copy
Edit
mv blood_bank_Manager /Applications/XAMPP/xamppfiles/htdocs/
3️⃣ Start Apache & MySQL
Open XAMPP Control Panel.
Start Apache and MySQL services.
4️⃣ Import the Database
Open phpMyAdmin at:
👉 http://localhost/phpmyadmin/
Create a new database:
👉 blood_bank_db
Import the provided SQL file:
Go to Import > Upload blood_bank_db.sql file.
5️⃣ Run the Application
Open your web browser.
Visit: http://localhost/blood_bank_Manager/
🎉 Now, you can register, log in, and manage the blood bank!
🏗 Project Structure


📂 blood_bank_Manager
 ├── 📄 index.php             # Homepage
 ├── 📄 login.php             # Login page
 ├── 📄 register.php          # Donor registration
 ├── 📄 inventory.php         # Blood inventory view
 ├── 📄 appointment.php       # Book appointments
 ├── 📄 admin.php             # Admin login
 ├── 📄 admin_dashboard.php   # Admin panel
 ├── 📄 db_connect.php        # Database connection
 ├── 📂 assets/               # CSS & Images
 ├── 📂 sql/                  # SQL Database files
 ├── 📄 README.md             # Project documentation

💾 Database Schema
The database blood_bank_db contains the following tables:

📌 1. users (For User Authentication)
Column	Type	Description
id	INT (PK)	Auto-incremented ID
username	VARCHAR(100)	Unique username
password	VARCHAR(255)	Hashed password
role	ENUM	User/Admin Role
📌 2. donors (For Donor Registration)
Column	Type	Description
id	INT (PK)	Auto-incremented ID
name	VARCHAR(100)	Donor’s Name
blood_type	VARCHAR(10)	Blood Group
contact	VARCHAR(15)	Contact Number
email	VARCHAR(100)	Email Address
hospital_id	INT (FK)	References hospitals(id)
📌 3. blood_inventory (For Blood Stock)
Column	Type	Description
id	INT (PK)	Auto-incremented ID
hospital_id	INT (FK)	References hospitals(id)
blood_type	VARCHAR(10)	Blood Group
quantity	INT	Available Stock
status	VARCHAR(50)	Available/Low/Critical
📌 4. appointments (For Booking Appointments)
Column	Type	Description
id	INT (PK)	Auto-incremented ID
donor_id	INT (FK)	References donors(id)
hospital_id	INT (FK)	References hospitals(id)
appointment_date	DATE	Scheduled Date
status	VARCHAR(50)	Pending/Confirmed/Completed

🔒 Security Considerations
✅ Prepared Statements – Prevent SQL Injection.
✅ Password Hashing – Passwords stored securely using password_hash().
✅ Session Management – Proper session handling for user authentication.
✅ Role-Based Access Control (RBAC) – Restrict pages based on user roles.
✅ HTTPS Enforcement – Encrypt all data transmissions.

🎯 Future Enhancements
📌 Email Notifications – Send reminders for donation drives.
📌 Live Inventory Updates – Track real-time blood stock levels.
📌 Advanced Reports – Generate graphical reports for trends.
📌 Mobile Responsiveness – Improve UI for mobile users.
📌 Admin Approval System – Verify new donor registrations.
