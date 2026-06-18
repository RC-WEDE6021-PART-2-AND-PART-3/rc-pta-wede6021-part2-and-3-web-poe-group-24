<?php
include "DBConn.php";
session_start();
$logged_in = isset($_SESSION['user_email']);

$collection_items = [
  ['image' => 'collection album/loop.jpg',  'link' => 'product.php?id=1',  'title' => 'Loop'],
  ['image' => 'collection album/17.jpg',    'link' => 'product.php?id=2',  'title' => '17'],
  ['image' => 'collection album/map.jpg',   'link' => 'product.php?id=3',  'title' => 'style'],
  ['image' => 'collection album/loot.jpg',  'link' => 'product.php?id=4',  'title' => 'Loot'],
  ['image' => 'collection album/look.jpg',  'link' => 'product.php?id=5',  'title' => 'Look'],
  ['image' => 'collection album/jump.jpg',  'link' => 'product.php?id=6',  'title' => 'Denim'],
  ['image' => 'collection album/12345.jpg', 'link' => 'product.php?id=7',  'title' => 'Graphic'],
  ['image' => 'collection album/P.jpg',     'link' => 'product.php?id=8',  'title' => 'P'],
  ['image' => 'collection album/NMMMMMMM.jpeg', 'link' => 'product.php?id=9', 'title' => 'shirt'],
  ['image' => 'collection album/GWACHA.jpeg', 'link' => 'product.php?id=10', 'title' => 'Coming Soon'],
];
function get_collection_image($path, $label) {
  if (file_exists(__DIR__ . '/' . $path)) {
    return $path;
  }
  $color = substr(md5($label), 0, 6);
  $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="800"><rect width="100%" height="100%" fill="#' . $color . '"/><text x="50%" y="48%" dominant-baseline="middle" text-anchor="middle" font-family="Montserrat, sans-serif" font-size="40" fill="#ffffff">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</text><text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-family="Montserrat, sans-serif" font-size="20" fill="#ffffff">Photo coming soon</text></svg>';
  return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bpleasant. – Collections</title>
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
    .collection-header { max-width: 980px; margin: 0 auto 28px; padding-top: 30px; }
    .collection-header h1 { font-size: 2.4rem; margin-bottom: 10px; }
    .collection-header p { font-size: 1rem; color: #5a5a5a; max-width: 760px; line-height: 1.7; }
    .page-wrapper { padding: 44px 40px 100px; }
    .grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }
    .grid-item { display: block; overflow: hidden; background: #e8e8e8; aspect-ratio: 3 / 4; text-decoration: none; position: relative; }
    .grid-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .35s ease; }
    .grid-item:hover img { transform: scale(1.05); }
    .grid-item .item-caption { position: absolute; left: 0; right: 0; bottom: 0; padding: 12px 14px; background: rgba(0,0,0,.45); color: #fff; font-size: 0.88rem; letter-spacing: .08em; text-transform: uppercase; }
    .grid-item:hover .item-caption { background: rgba(0,0,0,.6); }
    .grid-item::after { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0); transition: background .3s; }
    .grid-item:hover::after { background: rgba(0,0,0,0.07); }

    .media-bottom { max-width: 980px; margin: 42px auto 20px; display: grid; grid-template-columns: repeat(3, minmax(220px, 1fr)); gap: 18px; }
    .media-card { background: #111; color: #fff; border-radius: 20px; padding: 24px; display: grid; gap: 14px; min-height: 180px; box-shadow: 0 20px 50px rgba(0, 0, 0, .12); }
    .media-card h2 { font-size: 1rem; letter-spacing: .16em; text-transform: uppercase; margin: 0; color: #fff; }
    .media-card p { color: #d0d0d0; line-height: 1.7; font-size: 0.95rem; margin: 0; }
    .media-card a { display: inline-flex; align-items: center; gap: 10px; color: #fff; font-weight: 700; font-size: 0.85rem; letter-spacing: .12em; text-transform: uppercase; text-decoration: none; }
    .media-card a svg { width: 18px; height: 18px; fill: currentColor; }
    .media-card:hover { transform: translateY(-2px); transition: transform .2s ease; }

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
        <a href="login.php?redirect=collection.php">Login</a>
      <?php endif; ?>
      <span class="icon">&#9906;</span>
      <span class="icon">&#128722;</span>
    </div>
  </div>
  <div class="nav-row-2">
    <a href="collection.php">Collections</a>
  </div>
</nav>

<div class="page-wrapper">
  <header class="collection-header">
    <h1>Collection Album</h1>
    <p>Click an item to view its page. If you haven't added the photo files yet, placeholders appear instead.</p>
  </header>
  <div class="grid">
    <?php foreach ($collection_items as $index => $item): ?>
      <?php $image_label = 'Collection ' . ($index + 1); ?>
      <a href="<?= htmlspecialchars($item['link']) ?>" class="grid-item">
        <img src="<?= htmlspecialchars(get_collection_image($item['image'], $image_label)) ?>" alt="<?= htmlspecialchars($image_label) ?>" loading="lazy">
        <div class="item-caption"><?= htmlspecialchars($item['title'] ?? $image_label) ?></div>
      </a>
    <?php endforeach; ?>
  </div>
    <div class="media-bottom">
      <div class="media-card">
        <h2>Instagram</h2>
        <p>Follow our latest drops, street style updates, and exclusive collection previews on Instagram.</p>
        <a href="https://instagram.com/don.ai" target="_blank" rel="noopener noreferrer">@don.ai
          <svg viewBox="0 0 24 24"><path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5A4.25 4.25 0 0 0 20.5 16.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5Zm4.25 3.25a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Zm0 1.5a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm4.65-.4a1.05 1.05 0 1 1-2.1 0 1.05 1.05 0 0 1 2.1 0Z"/></svg>
        </a>
      </div>
      <div class="media-card">
        <h2>Facebook</h2>
        <p>Connect with us on Facebook for news, fresh drops, and streetwear community updates.</p>
        <a href="https://facebook.com/mulalo.jr" target="_blank" rel="noopener noreferrer">mulal.jr
          <svg viewBox="0 0 24 24"><path d="M13.5 22V13h3l.5-4h-3.5V6.5c0-1.1.3-1.9 1.9-1.9H17V1.5A25.09 25.09 0 0 0 14.4 1.4C11.1 1.4 9 3.2 9 6.9V9H6v4h3v9h4.5Z"/></svg>
        </a>
      </div>
      <div class="media-card">
        <h2>WhatsApp</h2>
        <p>Chat with us directly on WhatsApp for orders, delivery questions, or quick support.</p>
        <a href="https://wa.me/27639419674" target="_blank" rel="noopener noreferrer">+27 63 941 9674
          <svg viewBox="0 0 24 24"><path d="M20.5 3.5A10.46 10.46 0 0 0 13.4.6 10.67 10.67 0 0 0 2 11.1c0 1.8.5 3.4 1.4 4.8L2 22l5.5-1.4a10.41 10.41 0 0 0 4.8 1.3 10.61 10.61 0 0 0 7.8-3.2A10.44 10.44 0 0 0 20.5 3.5Zm-7.1 15.4a8.19 8.19 0 0 1-4.1-1.1l-.3-.2-3.3.8.9-3.2-.2-.3A8.15 8.15 0 1 1 13.4 18.9Zm3.3-4.4c-.2-.1-1.1-.5-1.3-.6-.2-.1-.4-.1-.6.1s-.7.6-.9.7-.3.2-.5.1c-.2 0-.8-.3-1.5-.9a5.4 5.4 0 0 1-1-1.2c-.1-.2 0-.4.1-.5.1-.1.2-.2.3-.3.1-.1.2-.2.3-.3.1-.1.2-.1.3-.1.1 0 .2 0 .3.1.1.1.4.3.6.5.2.2.4.4.5.5.2.2.1.3.1.4s0 .2-.1.3-.4.7-.4.8-.2.2-.5.2-.9-.4-1.2-.7c-.3-.3-.6-.8-.7-1.1s-.1-.6 0-.8.2-.3.4-.4.4-.1.6-.1c.2 0 .5 0 .8.1.3.1.8.3 1.2.7.4.3.7.8.8 1.1.1.4.1.7 0 1s-.1.4-.2.5c-.1.1-.4.3-.7.4s-.7.2-.9.1c-.2 0-.5-.2-.7-.3l-.2-.1c-.1 0-.2-.1-.3-.1s-.2 0-.2.1c-.1 0-.1.1-.1.2s0 .3.1.4c.1.2.2.4.3.6l.4.5c.4.4 1 .9 1.8 1.2 1.4.5 2.4.4 2.6.3.2-.1.7-.4.8-.7.1-.3.1-.6 0-.9-.1-.4-.4-.6-.6-.7Z"/></svg>
        </a>
      </div>
      <div class="media-card">
        <h2>X</h2>
        <p>Follow our latest streetwear updates on X for quick news, drops, and behind-the-scenes content.</p>
        <a href="https://x.com/don.ai" target="_blank" rel="noopener noreferrer">@don.ai
          <svg viewBox="0 0 24 24"><path d="M22 7.5c-.2.7-.5 1.2-1 1.6.4-.1.9-.2 1.2-.5-.4.6-1 1-1.6 1.2-.5.2-1.2.4-1.8.5-.6.1-1.3.2-1.9.2-.6 0-1.1 0-1.5-.1-.9-.1-1.8-.4-2.6-.8-.6-.3-1.1-.7-1.6-1.2-.2-.1-.4-.2-.5-.3s-.2-.1-.2-.1c.1.4.2.7.2 1.1.1.4.1.9.1 1.4 0 .6-.1 1.2-.2 1.8-.2.6-.5 1.1-.9 1.6-.7.9-1.6 1.5-2.6 1.9-.5.2-1 .3-1.5.4-.5.1-1 .1-1.5 0-1.1-.2-2.1-.7-2.9-1.4-.7-.7-1.2-1.6-1.4-2.7-.1-.5-.1-1-.1-1.5 0-.4 0-.9.1-1.3.1-.4.2-.7.3-1.1.1-.3.3-.5.4-.8s.3-.4.5-.6c.2-.2.4-.3.6-.4.2-.1.4-.2.6-.3.2-.1.4-.1.6-.1l.1.1s.1.1.2.2c0 .1.1.1.1.1.2.3.4.6.6.8.4.4.8.7 1.2.9.3.2.7.4 1.1.5 0 .1.1.2.1.3 0 .1 0 .2-.1.2-.1.1-.2.1-.4.1-.2 0-.5 0-.7-.1-.3-.1-.7-.2-1-.3-.4-.1-.8-.3-1.2-.5-.3-.1-.6-.3-.9-.5-.2-.1-.3-.2-.5-.4-.2-.1-.4-.3-.6-.5-.1-.2-.2-.4-.3-.6-.1-.2-.2-.5-.2-.7 0-.4 0-.7.1-1.1.1-.4.2-.7.4-1.1.2-.4.5-.7.9-.9.3-.2.7-.3 1.1-.3.2 0 .3 0 .5.1.2 0 .4.1.5.1.1 0 .2 0 .3-.1s.2-.1.2-.2c.1-.1.1-.2.1-.3s0-.2 0-.3c0-.3-.1-.5-.2-.8-.1-.3-.2-.6-.3-.9C4.6 3.9 4.3 3.7 4 3.5L3.9 3.4c.3-.1.6-.1.9 0 .3.1.6.2.9.4.3.2.6.4.9.7.2.2.4.4.5.7.1.2.2.4.3.6.1.2.1.4.1.6 0 .2 0 .4-.1.5-.1.2-.2.4-.3.6-.1.3-.2.5-.2.8 0 .1 0 .2-.1.3 0 .1 0 .2 0 .3s0 .2.1.3c.1.1.2.2.4.2.1.1.3.1.4.1.3 0 .5 0 .8-.1.2 0 .4-.1.6-.1.5-.1 1-.3 1.4-.6.1 0 .2-.1.3-.2.1 0 .2 0 .2-.1s.2-.2.3-.2c.1 0 .1-.1.2-.1.1-.1.1-.2.1-.3s0-.2 0-.3c0-.5-.2-1-.6-1.3-.4-.4-.9-.6-1.4-.6-.2 0-.4 0-.6.1-.2 0-.4.1-.5.1-.1 0-.1 0-.2-.1-.1 0-.1-.1 -.2-.1-.1 0-.1-.1-.2-.1-.1-.1-.2-.1-.2-.2-.1-.1-.1-.2-.1-.3 0-.1 0-.2 0-.3 0-.8.1-1.6.4-2.4.3-.9.7-1.7 1.3-2.4.6-.7 1.4-1.2 2.2-1.6.8-.4 1.7-.6 2.7-.6.6 0 1.2.1 1.9.2 1 .1 1.8.4 2.7.8.9.4 1.7 1 2.3 1.7.7.7 1.2 1.6 1.5 2.5C21.9 5.9 22 6.7 22 7.5Z"/></svg>
        </a>
      </div>
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