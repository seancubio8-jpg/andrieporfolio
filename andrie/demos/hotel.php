<?php
/**
 * Hotel Booking & Reservation System - Live Demo
 * Created for Sean Adrei Cubio's BSIT Student Portfolio
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Azure Hotel | Reservation System Live Demo</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --gold: #d97706;
            --gold-light: #fef3c7;
            --dark: #0f172a;
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --radius: 14px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: var(--text-main);
            padding-bottom: 60px;
        }

        /* Top Demo Bar */
        .demo-top-bar {
            background: #0f172a;
            color: #94a3b8;
            padding: 8px 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .demo-top-bar a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Hotel Header */
        .hotel-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .hotel-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .hotel-logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1e3a8a, #d97706);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .hotel-brand h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            color: var(--dark);
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .hotel-brand span {
            font-size: 11px;
            color: var(--gold);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
        }

        /* Hero Booking Search */
        .hotel-hero {
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.85)), #1e293b;
            color: #ffffff;
            padding: 50px 20px 70px;
            text-align: center;
        }
        .hotel-hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .hotel-hero p {
            color: #cbd5e1;
            font-size: 1.05rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Search Engine Card */
        .search-engine-box {
            max-width: 1050px;
            margin: -40px auto 30px;
            background: #ffffff;
            border-radius: var(--radius);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 20px 24px;
            border: 1px solid var(--border);
        }
        .search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) 150px;
            gap: 16px;
            align-items: flex-end;
        }
        .search-field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .search-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text-main);
        }
        .btn-check-rate {
            background: var(--gold);
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s ease;
        }
        .btn-check-rate:hover { background: #b45309; }

        /* Container & Suites */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .section-heading {
            margin-bottom: 24px;
        }
        .section-heading h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 26px;
        }
        .room-card {
            background: #ffffff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease;
        }
        .room-card:hover { transform: translateY(-4px); }
        .room-visual {
            height: 200px;
            background: #0f172a;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }
        .room-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(15, 23, 42, 0.85);
            color: #fbbf24;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .room-body {
            padding: 22px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .room-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }
        .room-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }
        .amenity-tag {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .room-rate-line {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            border-top: 1px solid #f1f5f9;
            padding-top: 16px;
            margin-top: auto;
        }
        .room-price-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--primary);
        }
        .room-price-val small { font-size: 12px; font-weight: 500; color: var(--text-muted); }

        .btn-book-room {
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-book-room:hover { background: #1e40af; }

        /* Booking Confirmation Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            z-index: 400;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.open { display: flex; }
        .modal-card {
            background: #ffffff;
            border-radius: 16px;
            max-width: 520px;
            width: 100%;
            padding: 26px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        }

        .voucher-box {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 20px;
        }
        .voucher-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }
        .ref-code {
            font-family: monospace;
            font-weight: 700;
            color: var(--primary);
            background: #dbeafe;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <!-- Demo Status Notice -->
    <div class="demo-top-bar">
        <span><i class="fas fa-flask"></i> Interactive Live Demo &bull; <strong>Hotel Reservation System (PHP/MySQL)</strong></span>
        <a href="../index.php#projects" target="_top"><i class="fas fa-arrow-left"></i> Return to Portfolio</a>
    </div>

    <!-- Hotel Header -->
    <header class="hotel-header">
        <div class="header-content">
            <a href="hotel.php" class="hotel-brand">
                <div class="hotel-logo-icon"><i class="fas fa-hotel"></i></div>
                <div>
                    <h2>Grand Azure</h2>
                    <span>Luxury Resort &amp; Spa</span>
                </div>
            </a>
            <div style="font-size: 13px; font-weight: 600; color: var(--gold);">
                <i class="fas fa-star"></i> 5-Star Hospitality Experience
            </div>
        </div>
    </header>

    <!-- Hotel Hero -->
    <section class="hotel-hero">
        <div class="container">
            <h1>Escape to Luxury &amp; Ocean Views</h1>
            <p>Experience seamless date reservation, dynamic nightly rate calculation, and instant booking voucher generation.</p>
        </div>
    </section>

    <!-- Date Search Engine -->
    <div class="search-engine-box">
        <div class="search-grid">
            <div class="search-field">
                <label><i class="fas fa-calendar-alt"></i> Check-In</label>
                <input type="date" class="search-input" id="checkInDate" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="search-field">
                <label><i class="fas fa-calendar-check"></i> Check-Out</label>
                <input type="date" class="search-input" id="checkOutDate" value="<?php echo date('Y-m-d', strtotime('+2 days')); ?>">
            </div>
            <div class="search-field">
                <label><i class="fas fa-user-friends"></i> Guests</label>
                <select class="search-input" id="guestCount">
                    <option value="1">1 Adult</option>
                    <option value="2" selected>2 Adults</option>
                    <option value="3">3 Adults</option>
                    <option value="4">Family (4 Guests)</option>
                </select>
            </div>
            <div>
                <button type="button" class="btn-check-rate" onclick="calculateDates();" style="width: 100%;">
                    <i class="fas fa-calculator"></i> Calculate
                </button>
            </div>
        </div>
    </div>

    <!-- Rooms Section -->
    <main class="container">
        <div class="section-heading">
            <h3>Featured Suites &amp; Availability</h3>
            <p style="color: var(--text-muted); font-size: 14px;" id="nightlySummary">Calculating for a <strong>2-Night Stay</strong></p>
        </div>

        <div class="rooms-grid">
            <!-- Suite 1 -->
            <div class="room-card">
                <div class="room-visual" style="background: linear-gradient(135deg, #1e3a8a, #0284c7);">
                    <i class="fas fa-bed" style="font-size: 3rem; color: #93c5fd;"></i>
                    <span class="room-badge"><i class="fas fa-star"></i> Ocean View</span>
                </div>
                <div class="room-body">
                    <h4 class="room-name">Deluxe Ocean Suite</h4>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">Spacious 55 sqm suite with private ocean-view balcony and king-size plush bed.</p>
                    <div class="room-amenities">
                        <span class="amenity-tag"><i class="fas fa-wifi"></i> Free High-Speed WiFi</span>
                        <span class="amenity-tag"><i class="fas fa-coffee"></i> Breakfast Included</span>
                        <span class="amenity-tag"><i class="fas fa-bath"></i> Marble Bathtub</span>
                    </div>
                    <div class="room-rate-line">
                        <div>
                            <div class="room-price-val">₱4,500 <small>/ night</small></div>
                            <span style="font-size: 12px; color: var(--text-muted);" id="subtotal-1">Total: ₱9,000 (2 nights)</span>
                        </div>
                        <button type="button" class="btn-book-room" onclick="bookRoom('Deluxe Ocean Suite', 4500);">
                            Book Suite
                        </button>
                    </div>
                </div>
            </div>

            <!-- Suite 2 -->
            <div class="room-card">
                <div class="room-visual" style="background: linear-gradient(135deg, #0f172a, #d97706);">
                    <i class="fas fa-crown" style="font-size: 3rem; color: #fde68a;"></i>
                    <span class="room-badge"><i class="fas fa-gem"></i> Penthouse</span>
                </div>
                <div class="room-body">
                    <h4 class="room-name">Executive Penthouse</h4>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">110 sqm top-floor luxury penthouse with jacuzzi, executive lounge access, and skyline views.</p>
                    <div class="room-amenities">
                        <span class="amenity-tag"><i class="fas fa-hot-tub"></i> Private Jacuzzi</span>
                        <span class="amenity-tag"><i class="fas fa-glass-cheers"></i> Lounge Access</span>
                        <span class="amenity-tag"><i class="fas fa-concierge-bell"></i> 24h Butler</span>
                    </div>
                    <div class="room-rate-line">
                        <div>
                            <div class="room-price-val">₱8,500 <small>/ night</small></div>
                            <span style="font-size: 12px; color: var(--text-muted);" id="subtotal-2">Total: ₱17,000 (2 nights)</span>
                        </div>
                        <button type="button" class="btn-book-room" onclick="bookRoom('Executive Penthouse', 8500);">
                            Book Suite
                        </button>
                    </div>
                </div>
            </div>

            <!-- Suite 3 -->
            <div class="room-card">
                <div class="room-visual" style="background: linear-gradient(135deg, #064e3b, #059669);">
                    <i class="fas fa-tree" style="font-size: 3rem; color: #a7f3d0;"></i>
                    <span class="room-badge"><i class="fas fa-leaf"></i> Garden Villa</span>
                </div>
                <div class="room-body">
                    <h4 class="room-name">Premier Garden Villa</h4>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">Private detached villa surrounded by tropical gardens, plunge pool, and patio lounge.</p>
                    <div class="room-amenities">
                        <span class="amenity-tag"><i class="fas fa-swimming-pool"></i> Plunge Pool</span>
                        <span class="amenity-tag"><i class="fas fa-utensils"></i> Chef Breakfast</span>
                        <span class="amenity-tag"><i class="fas fa-cocktail"></i> Sunset Bar</span>
                    </div>
                    <div class="room-rate-line">
                        <div>
                            <div class="room-price-val">₱6,200 <small>/ night</small></div>
                            <span style="font-size: 12px; color: var(--text-muted);" id="subtotal-3">Total: ₱12,400 (2 nights)</span>
                        </div>
                        <button type="button" class="btn-book-room" onclick="bookRoom('Premier Garden Villa', 6200);">
                            Book Suite
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Reservation Modal -->
    <div class="modal-overlay" id="bookingModal">
        <div class="modal-card">
            <div id="bookingFormStep">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 style="font-family: 'Outfit'; font-size: 1.3rem;"><i class="fas fa-key" style="color: var(--primary);"></i> Complete Reservation</h3>
                    <button type="button" onclick="closeModal();" style="border: none; background: transparent; cursor: pointer; font-size: 18px;"><i class="fas fa-times"></i></button>
                </div>

                <div class="voucher-box">
                    <div class="voucher-header">
                        <strong id="modalSuiteName">Deluxe Ocean Suite</strong>
                        <span class="ref-code" id="modalNightsTag">2 Nights</span>
                    </div>
                    <div style="font-size: 13px; color: var(--text-muted); line-height: 1.6;">
                        <div>Check-in: <strong id="modalCheckIn">--</strong></div>
                        <div>Check-out: <strong id="modalCheckOut">--</strong></div>
                        <div>Guests: <strong id="modalGuests">2 Adults</strong></div>
                        <div style="margin-top: 8px; font-size: 15px; color: var(--primary); font-weight: 800;">
                            Estimated Total: <span id="modalTotalPrice">₱0.00</span>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="font-size: 12px; font-weight: 700;">Lead Guest Name</label>
                    <input type="text" class="search-input" id="guestName" value="Guest Evaluator" placeholder="Enter full name">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="font-size: 12px; font-weight: 700;">Email for Confirmation Voucher</label>
                    <input type="email" class="search-input" id="guestEmail" value="evaluator@datamex.edu.ph" placeholder="email@address.com">
                </div>

                <button type="button" class="btn-check-rate" style="width: 100%;" onclick="confirmReservation();">
                    <i class="fas fa-check-circle"></i> Confirm Booking (Live Demo)
                </button>
            </div>

            <!-- Confirmation Voucher Success -->
            <div id="bookingSuccessStep" style="display: none; text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: #dcfce7; color: #16a34a; font-size: 1.8rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-check"></i>
                </div>
                <h3 style="font-family: 'Outfit'; margin-bottom: 8px;">Reservation Confirmed!</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 18px;">Your room has been locked in the database.</p>

                <div class="voucher-box" style="text-align: left; background: #ffffff;">
                    <div class="voucher-header">
                        <span>Grand Azure Confirmation</span>
                        <span class="ref-code" id="successRefCode">#GA-2026-9281</span>
                    </div>
                    <div style="font-size: 13px; line-height: 1.6;" id="voucherDetails">
                        <!-- Filled by JS -->
                    </div>
                </div>

                <button type="button" class="btn-book-room" style="width: 100%;" onclick="closeModal();">
                    Done
                </button>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        let selectedRoom = '';
        let roomRate = 0;
        let calculatedNights = 2;

        function calculateDates() {
            const inDate = new Date(document.getElementById('checkInDate').value);
            const outDate = new Date(document.getElementById('checkOutDate').value);
            const diffTime = outDate - inDate;
            let nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (nights <= 0) nights = 1;
            calculatedNights = nights;

            document.getElementById('nightlySummary').innerHTML = `Calculating for a <strong>${nights}-Night Stay</strong>`;
            document.getElementById('subtotal-1').textContent = `Total: ₱${(4500 * nights).toLocaleString()} (${nights} nights)`;
            document.getElementById('subtotal-2').textContent = `Total: ₱${(8500 * nights).toLocaleString()} (${nights} nights)`;
            document.getElementById('subtotal-3').textContent = `Total: ₱${(6200 * nights).toLocaleString()} (${nights} nights)`;
        }

        function bookRoom(roomName, rate) {
            selectedRoom = roomName;
            roomRate = rate;
            calculateDates();

            document.getElementById('modalSuiteName').textContent = roomName;
            document.getElementById('modalNightsTag').textContent = calculatedNights + ' Night' + (calculatedNights > 1 ? 's' : '');
            document.getElementById('modalCheckIn').textContent = document.getElementById('checkInDate').value;
            document.getElementById('modalCheckOut').textContent = document.getElementById('checkOutDate').value;
            document.getElementById('modalGuests').textContent = document.getElementById('guestCount').options[document.getElementById('guestCount').selectedIndex].text;
            document.getElementById('modalTotalPrice').textContent = '₱' + (rate * calculatedNights).toLocaleString();

            document.getElementById('bookingFormStep').style.display = 'block';
            document.getElementById('bookingSuccessStep').style.display = 'none';
            document.getElementById('bookingModal').classList.add('open');
        }

        function closeModal() {
            document.getElementById('bookingModal').classList.remove('open');
        }

        function confirmReservation() {
            const ref = '#GA-' + Math.floor(100000 + Math.random() * 900000);
            const guest = document.getElementById('guestName').value || 'Guest';

            document.getElementById('successRefCode').textContent = ref;
            document.getElementById('voucherDetails').innerHTML = `
                <div>Guest: <strong>${guest}</strong></div>
                <div>Suite: <strong>${selectedRoom}</strong></div>
                <div>Dates: <strong>${document.getElementById('checkInDate').value}</strong> to <strong>${document.getElementById('checkOutDate').value}</strong> (${calculatedNights} nights)</div>
                <div>Status: <strong style="color: #16a34a;"><i class="fas fa-check-circle"></i> Guaranteed Booking</strong></div>
            `;

            document.getElementById('bookingFormStep').style.display = 'none';
            document.getElementById('bookingSuccessStep').style.display = 'block';
        }

        // Initialize on load
        calculateDates();
    </script>
</body>
</html>
