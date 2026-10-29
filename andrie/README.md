# Sean Andrie Cubio - Personal Portfolio Website

A simple, modern, database-driven portfolio website designed in an elegant **Blue & White aesthetic** and built with **PHP**, **MySQL**, **Vanilla CSS**, and **JavaScript**.

---

## 📁 Project Structure

```
c:/xampp/htdocs/andrie/
│
├── index.php             # Main portfolio homepage (PHP + Dynamic DB queries)
├── login.php             # Administrator login page (username: andrie123 / andrie12345)
├── admin.php             # Admin control panel (inbox, message management, project overview)
├── logout.php            # Logout & session termination
├── db.php                # Database connection (PDO) with auto-install & fallback
├── database.sql          # MySQL database schema & seed data (profile, projects, messages, users)
├── setup_db.php          # 1-Click database installer / reset utility
│
├── demos/                # Interactive Live Project Demos
│   ├── tapseafo.php      # Live seafood ordering web app with interactive cart
│   ├── hotel.php         # Live Grand Azure hotel reservation engine
│   └── arduino.php       # Live touchless water dispenser IoT simulator
│
├── assets/
│   ├── css/
│   │   └── style.css     # Clean Blue & White styling, soft cards, & responsive layout
│   └── js/
│       └── main.js       # Navigation, filtering, counter animations, & scroll handling
└── README.md             # Documentation and setup instructions
```

---

## 🔐 Administrator Login
- **Login Tab**: Located in the main navigation bar on [index.php](http://localhost/andrie/)
- **Direct Login URL**: [http://localhost/andrie/login.php](http://localhost/andrie/login.php)
- **Username**: `andrie123`
- **Password**: `andrie12345`
- **Dashboard**: [http://localhost/andrie/admin.php](http://localhost/andrie/admin.php) (View contact inquiries, mark as read, delete, reply, live system stats).

---

## 🚀 How to Run with XAMPP

1. **Start Apache and MySQL**:
   - Open your **XAMPP Control Panel**.
   - Click **Start** for **Apache** and **MySQL**.

2. **Database Setup (Choose Option A or Option B)**:

   - **Option A (Fastest - 1 Click)**:
     - Open your browser and navigate to:
       [http://localhost/andrie/setup_db.php](http://localhost/andrie/setup_db.php)
     - Click **"Initialize Database Now"**. All tables and seed data will be created automatically!

   - **Option B (phpMyAdmin)**:
     - Open [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
     - Go to the **Import** tab.
     - Choose `database.sql` from `c:/xampp/htdocs/andrie/database.sql` and click **Import**.

3. **View the Website**:
   - Navigate to:
     [http://localhost/andrie/](http://localhost/andrie/)

---

## 💻 Featured Systems (Only 3 Projects)

1. **Tapseafo: Online Food Ordering** (`Web App` - PHP, MySQL, JavaScript, CSS3, Bootstrap)
   - Online seafood & food ordering platform with cart management, order dispatch, and checkout.
2. **Hotel Booking Website** (`PHP/MySQL` - PHP, PDO, MySQL, Ajax, CSS3)
   - Comprehensive hospitality reservation portal with live date checks, suite catalog, and booking confirmations.
3. **Arduino Water Dispenser** (`IoT & Hardware` - Arduino, C/C++, Sensors, Solenoid Valve)
   - Touchless automated water dispensing mechanism using ultrasonic sensors and solenoid valve control.

---

## 🎨 Color Palette & Modern Design

- **Primary Colors**: Pure White (`#ffffff`), Ice Blue (`#f0f7ff`, `#eff6ff`), Royal Blue (`#2563eb`, `#1d4ed8`), Deep Navy (`#0f172a`).
- **Interactive UI**:
  - Filterable Project Gallery specifically configured for Sean's 3 systems.
  - Floating tech chips (PHP/MySQL, Arduino & IoT, Full-Stack Dev).
  - Modern cards with category tags, custom icons, and hover lift effects.
  - Functional Contact Form that saves messages directly to the MySQL `messages` table.
  - Fully responsive on mobile, tablet, and desktop screens.

