<?php
include "DBConn.php";
session_start();
$logged_in = isset($_SESSION['user_email']);

if (!$logged_in) {
    header('Location: login.php?redirect=cart.php');
    exit;
}

// Product data
$products = [
    1 => ['image' => 'shop clothing/02ae30a9d316ee47d1d760f034e3c3a4.jpg', 'title' => 'Cargo pants', 'price' => 75],
    2 => ['image' => 'shop clothing/1201de3ecb040a9012cc889d5e25d4f5.jpg', 'title' => 'Shirt', 'price' => 35],
    3 => ['image' => 'shop clothing/480edc8f2fefed1b8afe2f1df0d5cace.jpg', 'title' => 'Tracksuit', 'price' => 85],
    4 => ['image' => 'shop clothing/c4b32fc9ae9001f69e53d42f5f89b352.jpg', 'title' => 'Street short shirt', 'price' => 25],
    5 => ['image' => 'shop clothing/cd8dddbe4075f087e48d6eb74c60ac03.jpg', 'title' => 'Outwear', 'price' => 95],
    6 => ['image' => 'shop clothing/ef8cdbe8e63327b304adc871d30fa71f.jpg', 'title' => 'Jacket', 'price' => 120],
    7 => ['image' => 'shop clothing/f306974e0cddc1ff13789139837f08ec.jpg', 'title' => 'Nike Bomber Jacket', 'price' => 110],
    8 => ['image' => 'shop clothing/Screenshot_20260414_104307_Instagram.jpg', 'title' => 'Long sleeve shirt', 'price' => 20],
    9 => ['image' => 'shop clothing/Screenshot_20260414_104315_Instagram.jpg', 'title' => 'Winter jacket', 'price' => 60],
    10 => ['image' => 'shop clothing/5102ceeb064591ddfd29bb0d333b1506.jpg', 'title' => 'T-shirt', 'price' => 40],
];

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $id => $qty) {
    if (isset($products[$id])) {
        $total += $products[$id]['price'] * $qty;
    }
}

// Handle remove
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($cart[$id]);
    $_SESSION['cart'] = $cart;
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bpleasant. – Cart</title>
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

    /* CART */
    .cart-page { padding: 44px 40px; }
    .cart-item { display: flex; align-items: center; gap: 20px; padding: 20px 0; border-bottom: 1px solid #eee; }
    .cart-item img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; }
    .cart-details { flex: 1; }
    .cart-title { font-size: 18px; font-weight: 600; }
    .cart-price { font-size: 16px; color: #555; }
    .cart-qty { font-size: 14px; }
    .remove-btn { background: #f00; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; }
    .total { text-align: right; font-size: 20px; font-weight: 700; margin: 20px 0; }
    .checkout-btn { background: #111; color: #fff; border: none; padding: 14px 28px; border-radius: 6px; font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; cursor: pointer; }
    .checkout-btn:hover { background: #333; }
    .empty-cart { text-align: center; font-size: 18px; margin: 50px 0; }

    /* CHATS */
    .chats-btn { position: fixed; bottom: 26px; right: 26px; background: #111; color: #fff; display: flex; align-items: center; gap: 10px; padding: 14px 24px; border-radius: 50px; font-size: 13px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; text-decoration: none; z-index: 999; transition: background .2s; }
    .chats-btn:hover { background: #333; }
    .chats-btn svg { width: 22px; height: 22px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* RESPONSIVE */
    @media (max-width: 600px) { .cart-page { padding: 28px 16px; } nav { padding: 0 16px; } .cart-item { flex-direction: column; align-items: flex-start; } }
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
      <a href="logout.php">Logout</a>
      <span class="icon">&#9906;</span>
      <span class="icon">&#128722;</span>
    </div>
  </div>
  <div class="nav-row-2">
    <span>Cart</span>
  </div>
</nav>

<div class="cart-page">
  <h1>Your Cart</h1>
  <?php if (empty($cart)): ?>
    <p class="empty-cart">Your cart is empty. <a href="shop.php">Shop now</a>.</p>
  <?php else: ?>
    <?php foreach ($cart as $id => $qty): ?>
      <?php if (isset($products[$id])): ?>
        <div class="cart-item">
          <img src="<?= htmlspecialchars($products[$id]['image']) ?>" alt="<?= htmlspecialchars($products[$id]['title']) ?>">
          <div class="cart-details">
            <h3 class="cart-title"><?= htmlspecialchars($products[$id]['title']) ?></h3>
            <p class="cart-price">$<?= htmlspecialchars($products[$id]['price']) ?></p>
            <p class="cart-qty">Quantity: <?= $qty ?></p>
          </div>
          <a href="cart.php?remove=<?= $id ?>" class="remove-btn">Remove</a>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
    <div class="total">Total: $<?= $total ?></div>
    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
  <?php endif; ?>
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