<?php
/**
 * TapSeaFo: Online Seafood Ordering System - Live Demo
 * Created for Sean Adrei Cubio's BSIT Student Portfolio
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TapSeaFo | Online Food Ordering Live Demo</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --accent: #f97316;
            --dark: #0f172a;
            --light: #f8fafc;
            --border: #e2e8f0;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --radius: 12px;
            --shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            color: var(--text-main);
            padding-bottom: 60px;
        }

        /* Top Demo Banner */
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
        .demo-top-bar a:hover { text-decoration: underline; }

        /* App Navbar */
        .app-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .app-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0284c7, #06b6d4);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .logo-text h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.3rem;
            color: var(--dark);
            font-weight: 800;
            line-height: 1;
        }
        .logo-text span {
            font-size: 11px;
            color: var(--accent);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cart-trigger-btn {
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .cart-trigger-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }
        .cart-count-badge {
            background: var(--accent);
            color: #ffffff;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
        }

        /* Hero Banner */
        .app-hero {
            background: linear-gradient(135deg, #0369a1, #0f172a);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .app-hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .app-hero p {
            color: #bae6fd;
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Container & Categories */
        .app-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .category-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            overflow-x: auto;
            padding-bottom: 8px;
        }
        .cat-btn {
            background: #ffffff;
            border: 1px solid var(--border);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .cat-btn.active, .cat-btn:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 24px;
        }
        .food-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .food-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 30px rgba(0,0,0,0.1);
        }
        .food-img-frame {
            height: 180px;
            background: #e2e8f0;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #0284c7;
        }
        .food-img-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .food-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(15, 23, 42, 0.85);
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            backdrop-filter: blur(4px);
        }
        .food-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .food-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }
        .food-desc {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.45;
            margin-bottom: 16px;
            flex: 1;
        }
        .food-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
        }
        .food-price {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-dark);
        }
        .add-cart-btn {
            background: #e0f2fe;
            color: var(--primary);
            border: 1px solid #bae6fd;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .add-cart-btn:hover {
            background: var(--primary);
            color: #ffffff;
        }

        /* Cart Drawer */
        .cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 200;
            display: none;
            justify-content: flex-end;
        }
        .cart-overlay.open { display: flex; }
        .cart-sidebar {
            width: 100%;
            max-width: 420px;
            height: 100%;
            background: #ffffff;
            box-shadow: -10px 0 30px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            animation: slideIn 0.25s ease forwards;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        .cart-head {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cart-head h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
        }
        .cart-close-btn {
            background: transparent;
            border: none;
            font-size: 18px;
            color: var(--text-muted);
            cursor: pointer;
        }
        .cart-items-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .cart-empty-state {
            text-align: center;
            color: var(--text-muted);
            margin: auto 0;
            padding: 40px 20px;
        }
        .cart-empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 14px;
        }
        .cart-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 8px;
        }
        .cart-row-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--dark);
        }
        .cart-row-price {
            font-size: 12px;
            color: var(--primary);
            font-weight: 700;
        }
        .qty-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qty-btn {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            background: #ffffff;
            border: 1px solid var(--border);
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cart-foot {
            padding: 20px;
            border-top: 1px solid var(--border);
            background: #f8fafc;
        }
        .cart-total-line {
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 16px;
            color: var(--dark);
        }
        .checkout-btn {
            width: 100%;
            background: var(--accent);
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s ease;
        }
        .checkout-btn:hover { background: #ea580c; }

        /* Checkout Modal */
        .checkout-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            z-index: 300;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .checkout-modal-overlay.open { display: flex; }
        .checkout-card {
            background: #ffffff;
            border-radius: 16px;
            max-width: 500px;
            width: 100%;
            padding: 26px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }

        /* Success Card */
        .order-success-box {
            text-align: center;
            padding: 20px 0;
        }
        .order-success-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #dcfce7;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 16px;
        }
    </style>
</head>
<body>

    <!-- Demo Status Notice -->
    <div class="demo-top-bar">
        <span><i class="fas fa-flask"></i> Interactive Live Demo &bull; <strong>Tapseafo Online Food Ordering</strong></span>
        <a href="../index.php#projects" target="_top"><i class="fas fa-arrow-left"></i> Return to Portfolio</a>
    </div>

    <!-- App Header -->
    <header class="app-header">
        <div class="header-container">
            <a href="tapseafo.php" class="app-logo">
                <div class="logo-icon"><i class="fas fa-utensils"></i></div>
                <div class="logo-text">
                    <h2>TapSeaFo</h2>
                    <span>Seafood Express</span>
                </div>
            </a>

            <button type="button" class="cart-trigger-btn" onclick="toggleCart();">
                <i class="fas fa-shopping-bag"></i> My Cart
                <span class="cart-count-badge" id="cartCount">0</span>
            </button>
        </div>
    </header>

    <!-- App Hero -->
    <section class="app-hero">
        <div class="app-container">
            <h1>Fresh Catch Delivered Straight to Your Table</h1>
            <p>Interactive student demo featuring real-time cart computation, category filtering, and instant checkout dispatch.</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="app-container">
        <!-- Categories -->
        <div class="category-filter">
            <button class="cat-btn active" onclick="filterCategory('all', this);">All Dishes</button>
            <button class="cat-btn" onclick="filterCategory('crab', this);">Crabs &amp; Shellfish</button>
            <button class="cat-btn" onclick="filterCategory('shrimp', this);">Prawns &amp; Squid</button>
            <button class="cat-btn" onclick="filterCategory('specials', this);">House Specialties</button>
            <button class="cat-btn" onclick="filterCategory('drinks', this);">Beverages</button>
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid" id="menuGrid">
            <!-- Item 1 -->
            <div class="food-card" data-cat="crab">
                <div class="food-img-frame" style="background: linear-gradient(135deg, #fed7aa, #fb923c);">
                    <i class="fas fa-utensils" style="color: #9a3412;"></i>
                    <span class="food-badge">Bestseller</span>
                </div>
                <div class="food-body">
                    <h4 class="food-title">Garlic Butter Mud Crab</h4>
                    <p class="food-desc">Fresh mangrove mud crab sautéed in aromatic garlic butter sauce with chili flakes.</p>
                    <div class="food-footer">
                        <span class="food-price">₱680.00</span>
                        <button type="button" class="add-cart-btn" onclick="addToCart('Garlic Butter Mud Crab', 680);">
                            <i class="fas fa-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="food-card" data-cat="shrimp">
                <div class="food-img-frame" style="background: linear-gradient(135deg, #bae6fd, #38bdf8);">
                    <i class="fas fa-fish" style="color: #0369a1;"></i>
                    <span class="food-badge">Chef's Choice</span>
                </div>
                <div class="food-body">
                    <h4 class="food-title">Crispy Salted Egg Prawns</h4>
                    <p class="food-desc">Deep-fried giant tiger prawns coated in creamy salted egg yolk glaze and curry leaves.</p>
                    <div class="food-footer">
                        <span class="food-price">₱540.00</span>
                        <button type="button" class="add-cart-btn" onclick="addToCart('Crispy Salted Egg Prawns', 540);">
                            <i class="fas fa-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="food-card" data-cat="shrimp">
                <div class="food-img-frame" style="background: linear-gradient(135deg, #e2e8f0, #94a3b8);">
                    <i class="fas fa-drumstick-bite" style="color: #334155;"></i>
                    <span class="food-badge">Popular</span>
                </div>
                <div class="food-body">
                    <h4 class="food-title">Golden Calamari Rings</h4>
                    <p class="food-desc">Tender squid rings breaded to golden perfection, served with homemade tartar sauce.</p>
                    <div class="food-footer">
                        <span class="food-price">₱320.00</span>
                        <button type="button" class="add-cart-btn" onclick="addToCart('Golden Calamari Rings', 320);">
                            <i class="fas fa-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="food-card" data-cat="specials">
                <div class="food-img-frame" style="background: linear-gradient(135deg, #fef08a, #eab308);">
                    <i class="fas fa-fire" style="color: #854d0e;"></i>
                    <span class="food-badge">House Special</span>
                </div>
                <div class="food-body">
                    <h4 class="food-title">Sizzling Seafood Sisig</h4>
                    <p class="food-desc">Minced milkfish, squid, and shrimp served on a sizzling hot plate with egg and calamansi.</p>
                    <div class="food-footer">
                        <span class="food-price">₱360.00</span>
                        <button type="button" class="add-cart-btn" onclick="addToCart('Sizzling Seafood Sisig', 360);">
                            <i class="fas fa-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Item 5 -->
            <div class="food-card" data-cat="specials">
                <div class="food-img-frame" style="background: linear-gradient(135deg, #bbf7d0, #4ade80);">
                    <i class="fas fa-bowl-rice" style="color: #166534;"></i>
                    <span class="food-badge">Platter</span>
                </div>
                <div class="food-body">
                    <h4 class="food-title">Seafood Fried Rice Platter</h4>
                    <p class="food-desc">Wok-tossed jasmine rice infused with crab fat, shrimp meat, vegetables, and scrambled egg.</p>
                    <div class="food-footer">
                        <span class="food-price">₱280.00</span>
                        <button type="button" class="add-cart-btn" onclick="addToCart('Seafood Fried Rice Platter', 280);">
                            <i class="fas fa-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Item 6 -->
            <div class="food-card" data-cat="drinks">
                <div class="food-img-frame" style="background: linear-gradient(135deg, #fbcfe8, #f472b6);">
                    <i class="fas fa-glass-water" style="color: #9d174d;"></i>
                    <span class="food-badge">Refreshing</span>
                </div>
                <div class="food-body">
                    <h4 class="food-title">Iced Blue Lagoon Mocktail</h4>
                    <p class="food-desc">Sparkling citrus blend with blue curaçao syrup, fresh lime slices, and crushed mint.</p>
                    <div class="food-footer">
                        <span class="food-price">₱120.00</span>
                        <button type="button" class="add-cart-btn" onclick="addToCart('Iced Blue Lagoon Mocktail', 120);">
                            <i class="fas fa-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Cart Drawer -->
    <div class="cart-overlay" id="cartOverlay" onclick="if(event.target === this) toggleCart();">
        <div class="cart-sidebar">
            <div class="cart-head">
                <h3><i class="fas fa-shopping-bag" style="color: var(--primary);"></i> Your Order Cart</h3>
                <button type="button" class="cart-close-btn" onclick="toggleCart();"><i class="fas fa-times"></i></button>
            </div>

            <div class="cart-items-container" id="cartItemsList">
                <div class="cart-empty-state">
                    <i class="fas fa-shopping-basket"></i>
                    <h4>Your Cart is Empty</h4>
                    <p>Select delicious dishes from our menu to start your order.</p>
                </div>
            </div>

            <div class="cart-foot" id="cartFooter" style="display: none;">
                <div class="cart-total-line">
                    <span>Order Total:</span>
                    <span id="cartTotalPrice">₱0.00</span>
                </div>
                <button type="button" class="checkout-btn" onclick="openCheckout();">
                    <i class="fas fa-credit-card"></i> Proceed to Checkout
                </button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="checkout-modal-overlay" id="checkoutModal">
        <div class="checkout-card">
            <div id="checkoutFormContent">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 style="font-family: 'Outfit'; font-size: 1.3rem;"><i class="fas fa-truck" style="color: var(--primary);"></i> Order Checkout</h3>
                    <button type="button" onclick="closeCheckout();" style="border: none; background: transparent; cursor: pointer; font-size: 16px;"><i class="fas fa-times"></i></button>
                </div>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" id="custName" value="Guest Evaluator" placeholder="Your Name">
                </div>
                <div class="form-group">
                    <label>Delivery Address</label>
                    <input type="text" class="form-control" id="custAddress" value="Datamex College Campus, Parañaque" placeholder="Street Address">
                </div>
                <div class="form-group">
                    <label>Payment Method</label>
                    <select class="form-control" id="custPayMethod">
                        <option value="Cash on Delivery">Cash on Delivery (COD)</option>
                        <option value="GCash / Maya">GCash / Maya E-Wallet</option>
                    </select>
                </div>

                <div style="background: #f1f5f9; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 13px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Items Subtotal:</span>
                        <strong id="modalSubtotal">₱0.00</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: var(--text-muted);">
                        <span>Estimated Delivery:</span>
                        <span>25 - 35 mins</span>
                    </div>
                </div>

                <button type="button" class="checkout-btn" onclick="submitOrder();">
                    <i class="fas fa-check-circle"></i> Confirm Order (Demo)
                </button>
            </div>

            <!-- Success State -->
            <div id="checkoutSuccessContent" style="display: none;" class="order-success-box">
                <div class="order-success-icon"><i class="fas fa-check"></i></div>
                <h3 style="font-family: 'Outfit'; margin-bottom: 8px;">Order Placed Successfully!</h3>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;" id="orderSuccessMsg">Order #TSF-8492 has been dispatched to kitchen.</p>
                <button type="button" class="cat-btn active" style="width: 100%; padding: 12px;" onclick="resetDemo();">
                    Order Another Meal
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript Interactive Logic -->
    <script>
        let cart = {};

        function addToCart(title, price) {
            if (cart[title]) {
                cart[title].qty++;
            } else {
                cart[title] = { price: price, qty: 1 };
            }
            updateCartUI();
            toggleCart(true);
        }

        function updateQty(title, delta) {
            if (!cart[title]) return;
            cart[title].qty += delta;
            if (cart[title].qty <= 0) {
                delete cart[title];
            }
            updateCartUI();
        }

        function updateCartUI() {
            const list = document.getElementById('cartItemsList');
            const countBadge = document.getElementById('cartCount');
            const footer = document.getElementById('cartFooter');
            const totalEl = document.getElementById('cartTotalPrice');

            let totalCount = 0;
            let totalPrice = 0;
            let html = '';

            for (const [title, item] of Object.entries(cart)) {
                totalCount += item.qty;
                totalPrice += item.price * item.qty;

                html += `
                    <div class="cart-row">
                        <div>
                            <div class="cart-row-title">${title}</div>
                            <div class="cart-row-price">₱${item.price.toFixed(2)} × ${item.qty}</div>
                        </div>
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty('${title}', -1)">-</button>
                            <span style="font-weight: 700; font-size: 13px;">${item.qty}</span>
                            <button class="qty-btn" onclick="updateQty('${title}', 1)">+</button>
                        </div>
                    </div>
                `;
            }

            countBadge.textContent = totalCount;

            if (totalCount > 0) {
                list.innerHTML = html;
                footer.style.display = 'block';
                totalEl.textContent = '₱' + totalPrice.toFixed(2);
            } else {
                list.innerHTML = `
                    <div class="cart-empty-state">
                        <i class="fas fa-shopping-basket"></i>
                        <h4>Your Cart is Empty</h4>
                        <p>Select delicious dishes from our menu to start your order.</p>
                    </div>
                `;
                footer.style.display = 'none';
            }
        }

        function toggleCart(forceOpen = false) {
            const overlay = document.getElementById('cartOverlay');
            if (forceOpen) {
                overlay.classList.add('open');
            } else {
                overlay.classList.toggle('open');
            }
        }

        function filterCategory(cat, btn) {
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const cards = document.querySelectorAll('.food-card');
            cards.forEach(card => {
                if (cat === 'all' || card.getAttribute('data-cat') === cat) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function openCheckout() {
            toggleCart(false);
            const modal = document.getElementById('checkoutModal');
            document.getElementById('checkoutFormContent').style.display = 'block';
            document.getElementById('checkoutSuccessContent').style.display = 'none';

            let totalPrice = 0;
            for (const item of Object.values(cart)) {
                totalPrice += item.price * item.qty;
            }
            document.getElementById('modalSubtotal').textContent = '₱' + totalPrice.toFixed(2);
            modal.classList.add('open');
        }

        function closeCheckout() {
            document.getElementById('checkoutModal').classList.remove('open');
        }

        function submitOrder() {
            const orderNum = Math.floor(1000 + Math.random() * 9000);
            const name = document.getElementById('custName').value || 'Guest';
            document.getElementById('orderSuccessMsg').innerHTML = `
                Thank you, <strong>${name}</strong>!<br>Order <strong>#TSF-${orderNum}</strong> has been transmitted to the kitchen.<br>Payment via <strong>${document.getElementById('custPayMethod').value}</strong>.
            `;
            document.getElementById('checkoutFormContent').style.display = 'none';
            document.getElementById('checkoutSuccessContent').style.display = 'block';
            cart = {};
            updateCartUI();
        }

        function resetDemo() {
            closeCheckout();
        }
    </script>
</body>
</html>
