<?php
include "DBConn.php";
session_start();
$logged_in  = isset($_SESSION['user_email']);
$store_name = 'Bpleasant.'; // ← Change to your store name
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $store_name ?> – Our Story</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Montserrat', sans-serif;
      background: #fff;
      color: #111;
      min-height: 100vh;
    }

    /* ── NAVBAR ── */
    nav {
      background: #000;
      padding: 0 40px;
    }

    .nav-row-1 {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 62px;
    }

    .nav-row-2 {
      display: flex;
      align-items: center;
      height: 52px;
      gap: 44px;
      border-top: 1px solid #222;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 38px;
    }

    nav a {
      text-decoration: none;
      color: #fff;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .14em;
      text-transform: uppercase;
      white-space: nowrap;
    }
    nav a:hover { color: #aaa; }

    /* Dropdown */
    .nav-dropdown { position: relative; }
    .nav-dropdown > span {
      color: #fff;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .14em;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      gap: 5px;
      cursor: pointer;
    }
    .nav-dropdown > span::after { content: '∨'; font-size: 9px; }
    .dropdown-menu {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      background: #111;
      min-width: 160px;
      flex-direction: column;
      padding: 10px 0;
      z-index: 200;
    }
    .nav-dropdown:hover .dropdown-menu { display: flex; }
    .dropdown-menu a { padding: 9px 18px; font-size: 11px; color: #ccc !important; }
    .dropdown-menu a:hover { color: #fff !important; background: #222; }

    /* Right icons */
    .nav-right {
      display: flex;
      align-items: center;
      gap: 22px;
    }
    .nav-right a {
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .14em;
      text-transform: uppercase;
    }
    .nav-right .icon { font-size: 19px; color: #fff; cursor: pointer; }

    /* ── ABOUT CONTENT ── */
    .about-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 80px 24px 130px;
      max-width: 900px;
      margin: 0 auto;
    }

    .store-name {
      font-size: 15px;
      font-weight: 700;
      letter-spacing: .08em;
      margin-bottom: 24px;
    }

    .about-desc {
      font-size: 15px;
      font-weight: 400;
      line-height: 1.9;
      color: #111;
      margin-bottom: 36px;
    }

    .focus-title {
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 12px;
    }

    .focus-list {
      list-style: disc;
      display: inline-block;
      text-align: center;
      margin-bottom: 26px;
    }
    .focus-list li {
      font-size: 15px;
      line-height: 2.1;
      list-style-position: inside;
      color: #111;
    }

    .tagline {
      font-size: 15px;
      color: #111;
      margin-bottom: 52px;
    }

    /* Button */
    .btn-find {
      display: inline-block;
      background: #111;
      color: #fff;
      font-family: 'Montserrat', sans-serif;
      font-size: 16px;
      font-weight: 500;
      letter-spacing: .04em;
      padding: 19px 60px;
      border-radius: 50px;
      text-decoration: none;
      transition: background .2s, transform .15s;
    }
    .btn-find:hover { background: #333; transform: scale(1.03); }

    /* ── CHATS FIXED BUTTON ── */
    .chats-btn {
      position: fixed;
      bottom: 26px;
      right: 26px;
      background: #111;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 14px 24px;
      border-radius: 50px;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: .13em;
      text-transform: uppercase;
      text-decoration: none;
      z-index: 999;
      transition: background .2s;
    }
    .chats-btn:hover { background: #333; }
    .chats-btn svg {
      width: 22px; height: 22px;
      fill: none; stroke: #fff;
      stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
  </style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav>
  <!-- Row 1 -->
  <div class="nav-row-1">
    <div class="nav-left">
      <div class="nav-dropdown">
        <span>Shop</span>
        <div class="dropdown-menu">
          <a href="shop.php">All Products</a>
          <a href="new-arrivals.php">New Arrivals</a>
          <a href="sale.php">Sale</a>
        </div>
      </div>
      <a href="daily-wear.php">Daily Wear</a>
      <a href="collab.php">Our Collab x Mateki2Shoes</a>
    </div>

    <div class="nav-right">
      <?php if ($logged_in): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php?redirect=ourstory.php">Login</a>
      <?php endif; ?>
      <span class="icon">&#9906;</span>
      <span class="icon">&#128722;</span>
    </div>
  </div>

  <!-- Row 2 -->
  <div class="nav-row-2">
    <a href="collection.php">Collections</a>
    <div class="nav-dropdown">
      <span>Lookbook</span>
      <div class="dropdown-menu">
        <a href="lookbook.php">Season 1</a>
        <a href="lookbook2.php">Season 2</a>
      </div>
    </div>
  </div>
</nav>

<!-- ── ABOUT CONTENT ── -->
<div class="about-wrapper">

  <p class="store-name"><?= htmlspecialchars($store_name) ?></p>

  <p class="about-desc">
    <?= htmlspecialchars($store_name) ?> is a modern streetwear marketplace built for collectors, resellers, and fashion enthusiasts.<br>
    We focus on sourcing and reselling authentic streetwear pieces — from limited drops to rare finds.<br>
    Inspired by street culture and trendsetters like dOn Ai, Okmalumkoolkat, Young Stilo, and Scoop Makhathini,<br>
    our platform connects people who value style, exclusivity, and culture.<br>
    We make it easy to buy, sell, and discover unique clothing while giving fashion a second life through resale.
  </p>

  <p class="focus-title">Our Focus</p>

  <ul class="focus-list">
    <li>Authentic streetwear only</li>
    <li>Limited and rare pieces</li>
    <li>Easy buying and selling</li>
    <li>Community-driven fashion</li>
  </ul>

  <p class="tagline">Wear the culture. Resell the story.</p>

  <a href="ourstory_full.php" class="btn-find">Find Out More</a>

</div>

<!-- ── CHATS BUTTON ── -->
<a href="chat.php" class="chats-btn">
  <svg viewBox="0 0 24 24">
    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
    <line x1="8" y1="10" x2="16" y2="10"/>
    <line x1="8" y1="14" x2="13" y2="14"/>
  </svg>
  CHATS
</a>

</body>
</html>