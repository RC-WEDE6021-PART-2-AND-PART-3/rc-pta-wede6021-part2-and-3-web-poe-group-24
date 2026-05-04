<?php
include "DBConn.php";
session_start();
$logged_in = isset($_SESSION['user_email']);

// ── Add your collection items here ──
$collection_items = [
    ['image' => 'col-1.jpg',  'link' => 'product.php?id=1'],
    ['image' => 'col-2.jpg',  'link' => 'product.php?id=2'],
    ['image' => 'col-3.jpg',  'link' => 'product.php?id=3'],
    ['image' => 'col-4.jpg',  'link' => 'product.php?id=4'],
    ['image' => 'col-5.jpg',  'link' => 'product.php?id=5'],
    ['image' => 'col-6.jpg',  'link' => 'product.php?id=6'],
    ['image' => 'col-7.jpg',  'link' => 'product.php?id=7'],
    ['image' => 'col-8.jpg',  'link' => 'product.php?id=8'],
    ['image' => 'col-9.jpg',  'link' => 'product.php?id=9'],
    ['image' => 'col-10.jpg', 'link' => 'product.php?id=10'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bpleasant. – Daily Wear</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap');
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Montserrat', sans-serif; background: #fff; color: #111; min-height: 100vh; }

    /* NAV */
    nav { background: #fff; padding: 0 40px; border-bottom: 1px solid #eee; }
    .nav-row-1 { display: flex; align-items: center; justify-content: space-between; height: 62px; }
    .nav-row-2 { display: flex; align-items: center; height: 46px; }
    .nav-left { display: flex; align-items: center; gap: 38px; }
    nav a { text-decoration: none; color: #111; font-size: 12px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; white-space: nowrap; }
    nav a:hover { color: #555; }

    .nav-dropdown { position: relative; }
    .nav-dropdown > span { color: #111; font-size: 12px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; display: flex; align-items: center; gap: 5px; cursor: pointer; }
    .nav-dropdown > span::after { content: '∨'; font-size: 9px; }
    .dropdown-menu { display: none; position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #eee; min-width: 160px; flex-direction: column; padding: 8px 0; z-index: 200; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .nav-dropdown:hover .dropdown-menu { display: flex; }
    .dropdown-menu a { padding: 9px 18px; font-size: 11px; }
    .dropdown-menu a:hover { background: #f5f5f5; }

    .nav-right { display: flex; align-items: center; gap: 22px; }
    .nav-right a { font-size: 12px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; color: #111; }
    .icon { font-size: 20px; color: #111; cursor: pointer; }
    .nav-row-2 a { font-size: 13px; font-weight: 800; letter-spacing: .16em; }

    /* GRID */
    .page-wrapper { padding: 44px 40px 100px; }
    .grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }
    .grid-item { display: block; overflow: hidden; background: #e8e8e8; aspect-ratio: 3 / 4; text-decoration: none; position: relative; }
    .grid-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .35s ease; }
    .grid-item:hover img { transform: scale(1.05); }
    .grid-item::after { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0); transition: background .3s; }
    .grid-item:hover::after { background: rgba(0,0,0,0.07); }

    /* CHATS */
    .chats-btn { position: fixed; bottom: 26px; right: 26px; background: #111; color: #fff; display: flex; align-items: center; gap: 10px; padding: 14px 24px; border-radius: 50px; font-size: 13px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; text-decoration: none; z-index: 999; transition: background .2s; }
    .chats-btn:hover { background: #333; }
    .chats-btn svg { width: 22px; height: 22px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* RESPONSIVE */
    @media (max-width: 900px) { .grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 600px) { .grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } .page-wrapper { padding: 28px 16px 100px; } nav { padding: 0 16px; } }
  </style>
</head>
<body>

<nav>
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
        <a href="login.php?redirect=daily-wear.php">Login</a>
      <?php endif; ?>
      <span class="icon">&#9906;</span>
      <span class="icon">&#128722;</span>
    </div>
  </div>
  <div class="nav-row-2">
    <a href="collection.php">Collections</a>
    <span>Daily Wear</span>
  </div>
</nav>

<div class="page-wrapper">
  <div class="grid">
    <?php foreach ($collection_items as $item): ?>
      <a href="<?= htmlspecialchars($item['link']) ?>" class="grid-item">
        <img src="<?= htmlspecialchars($item['image']) ?>" alt="Collection Item" loading="lazy">
      </a>
    <?php endforeach; ?>
  </div>
</div>

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