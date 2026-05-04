<?php
include "DBConn.php";
session_start();
$logged_in = isset($_SESSION['user_email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bpleasant.</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;600;700&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Montserrat', sans-serif;
      background: #000;
      color: #fff;
      overflow-x: hidden;
    }

    /* ── NAVBAR ── */
    nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #000;
      padding: 0 40px;
      height: 60px;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 32px;
    }

    .nav-left a, .nav-right a {
      text-decoration: none;
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .13em;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .nav-left a:hover, .nav-right a:hover { color: #aaa; }

    /* Dropdown trigger */
    .has-dropdown {
      position: relative;
      cursor: pointer;
    }
    .has-dropdown span {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .13em;
      text-transform: uppercase;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .has-dropdown span::after {
      content: '∨';
      font-size: 9px;
    }
    .dropdown {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      background: #111;
      min-width: 160px;
      flex-direction: column;
      padding: 10px 0;
    }
    .has-dropdown:hover .dropdown { display: flex; }
    .dropdown a {
      padding: 9px 18px;
      font-size: 11px;
      color: #ccc !important;
    }
    .dropdown a:hover { color: #fff !important; background: #222; }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 22px;
    }

    /* Login link — changes to user email or logout when logged in */
    .nav-right .login-link {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .13em;
      text-transform: uppercase;
      color: #fff;
      text-decoration: none;
    }

    /* Search icon */
    .nav-right .icon {
      font-size: 16px;
      cursor: pointer;
      color: #fff;
    }

    /* Cart icon */
    .cart-icon {
      position: relative;
      cursor: pointer;
    }
    .cart-icon svg { width: 22px; height: 22px; fill: #fff; }
    .cart-badge {
      position: absolute;
      top: -6px;
      right: -6px;
      background: #fff;
      color: #000;
      font-size: 9px;
      font-weight: 700;
      width: 16px;
      height: 16px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* ── HERO ── */
    .hero {
      position: relative;
      width: 100%;
      height: calc(100vh - 60px);
      min-height: 500px;
      overflow: hidden;
      background: #222;
    }

    .hero-bg {
      position: absolute;
      inset: 0;
      background-image: url('urban.jpg'); /* ← REPLACE WITH YOUR IMAGE */
      background-size: cover;
      background-position: center;
      filter: grayscale(100%) brightness(0.75);
      z-index: 0;
    }

    /* CTA buttons — bottom center */
    .hero-cta {
      position: absolute;
      bottom: 80px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 20px;
      z-index: 2;
    }

    .btn-hero {
      display: inline-block;
      padding: 18px 52px;
      border-radius: 50px;
      font-family: 'Montserrat', sans-serif;
      font-size: 16px;
      font-weight: 500;
      letter-spacing: .04em;
      text-decoration: none;
      cursor: pointer;
      border: none;
      transition: opacity .2s, transform .2s;
      white-space: nowrap;
    }
    .btn-hero:hover { opacity: .85; transform: scale(1.03); }

    .btn-shop {
      background: rgba(0,0,0,0.75);
      color: #fff;
      border: 2px solid rgba(255,255,255,0.25);
    }

    .btn-rewards {
      background: #111;
      color: #fff;
    }
  </style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav>
  <div class="nav-left">

    <!-- SHOP dropdown -->
    <div class="has-dropdown">
      <span>Shop</span>
      <div class="dropdown">
        <a href="shop.php">All Products</a>
        <a href="new-arrivals.php">New Arrivals</a>
        <a href="sale.php">Sale</a>
      </div>
    </div>

    <a href="daily-wear.php">Daily Wear</a>
    <a href="collab.php">Our Collab x Mateki2Shoes</a>

    <!-- LOOKBOOK dropdown -->
    <div class="has-dropdown">
      <span>Lookbook</span>
      <div class="dropdown">
        <a href="lookbook.php">Season 1</a>
        <a href="lookbook2.php">Season 2</a>
      </div>
    </div>

    <a href="collection.php">Collections</a>
    <a href="ourstory.php">Our Story</a>
  </div>

  <div class="nav-right">
    <?php if ($logged_in): ?>
      <a href="logout.php" class="login-link">Logout</a>
    <?php else: ?>
      <!-- ✅ KEY: pass current page as "redirect" so login.php knows where to send user back -->
      <a href="login.php?redirect=index.php" class="login-link">Login</a>
    <?php endif; ?>

    <span class="icon">&#9906;</span>

    <div class="cart-icon">
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
        <line x1="3" y1="6" x2="21" y2="6" stroke="#fff" stroke-width="2" fill="none"/>
        <path d="M16 10a4 4 0 01-8 0" fill="none" stroke="#fff" stroke-width="2"/>
      </svg>
    </div>
  </div>
</nav>

<!-- ── HERO ── -->
<div class="hero">
  <div class="hero-bg"></div>

  <div class="hero-cta">
    <a href="shop.php" class="btn-hero btn-shop">Shop Now</a>
    <a href="rewards.php" class="btn-hero btn-rewards">Rewards</a>
  </div>
</div>

</body>
</html>