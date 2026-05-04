<?php
include "DBConn.php";
session_start();
$logged_in = isset($_SESSION['user_email']);

// Product data 
$products = [
    1 => ['image' => 'shop clothing/02ae30a9d316ee47d1d760f034e3c3a4.jpg', 'title' => 'Cargo pants', 'price' => 75, 'description' => 'Comfortable cargo pants perfect for streetwear.'],
    2 => ['image' => 'shop clothing/1201de3ecb040a9012cc889d5e25d4f5.jpg', 'title' => 'Shirt', 'price' => 35, 'description' => 'Stylish graphic shirt with unique design.'],
    3 => ['image' => 'shop clothing/480edc8f2fefed1b8afe2f1df0d5cace.jpg', 'title' => 'Tracksuit', 'price' => 85, 'description' => 'Durable tracksuit with warm.'],
    4 => ['image' => 'shop clothing/c4b32fc9ae9001f69e53d42f5f89b352.jpg', 'title' => 'Street short shirt', 'price' => 25, 'description' => 'Classic street short shirt for everyday wear.'],
    5 => ['image' => 'shop clothing/cd8dddbe4075f087e48d6eb74c60ac03.jpg', 'title' => 'Outwear', 'price' => 95, 'description' => 'Vintage-style outwear.'],
    6 => ['image' => 'shop clothing/ef8cdbe8e63327b304adc871d30fa71f.jpg', 'title' => 'Jacket', 'price' => 120, 'description' => 'High-top jacket for street style.'],
    7 => ['image' => 'shop clothing/f306974e0cddc1ff13789139837f08ec.jpg', 'title' => 'Nike Bomber Jacket', 'price' => 110, 'description' => 'Sleek bomber jacket with modern fit.'],
    8 => ['image' => 'shop clothing/Screenshot_20260414_104307_Instagram.jpg', 'title' => 'Long sleeve shirt', 'price' => 20, 'description' => 'Warm long sleeve t shirt for cold days.'],
    9 => ['image' => 'shop clothing/Screenshot_20260414_104315_Instagram.jpg', 'title' => 'Winter jacket', 'price' => 60, 'description' => 'Comfortable jacket for winter.'],
    10 => ['image' => 'shop clothing/5102ceeb064591ddfd29bb0d333b1506.jpg', 'title' => 'T-shirt', 'price' => 40, 'description' => 'Stylish t shirt with graphics.'],
];

$id = $_GET['id'] ?? 1;
$product = $products[$id] ?? null;

if (!$product) {
    header('Location: shop.php');
    exit;
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $qty = (int)($_POST['quantity'] ?? 1);
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    header('Location: cart.php');
    exit;
}

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $qty = (int)($_POST['quantity'] ?? 1);
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    header('Location: checkout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bpleasant. – <?= htmlspecialchars($product['title']) ?></title>
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

    /* PRODUCT */
    .product-page { padding: 44px 40px; display: flex; gap: 40px; }
    .product-image { flex: 1; max-width: 500px; }
    .product-image img { width: 100%; height: auto; border-radius: 8px; }
    .product-details { flex: 1; }
    .product-title { font-size: 28px; font-weight: 700; margin-bottom: 16px; }
    .product-price { font-size: 24px; font-weight: 600; color: #111; margin-bottom: 20px; }
    .product-description { font-size: 16px; line-height: 1.6; margin-bottom: 30px; }
    .quantity { margin-bottom: 20px; display: flex; align-items: center; }
    .quantity label { font-weight: 600; margin-right: 10px; }
    .quantity input { width: 60px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
    .buttons { display: flex; gap: 16px; }
    .btn { padding: 14px 28px; border: none; border-radius: 6px; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; cursor: pointer; transition: background .2s; }
    .btn-add { background: #111; color: #fff; }
    .btn-add:hover { background: #333; }
    .btn-checkout { background: #fff; color: #111; border: 2px solid #111; }
    .btn-checkout:hover { background: #111; color: #fff; }

    /* CHATS */
    .chats-btn { position: fixed; bottom: 26px; right: 26px; background: #111; color: #fff; display: flex; align-items: center; gap: 10px; padding: 14px 24px; border-radius: 50px; font-size: 13px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; text-decoration: none; z-index: 999; transition: background .2s; }
    .chats-btn:hover { background: #333; }
    .chats-btn svg { width: 22px; height: 22px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* RESPONSIVE */
    @media (max-width: 900px) { .product-page { flex-direction: column; } }
    @media (max-width: 600px) { .page-wrapper { padding: 28px 16px 100px; } nav { padding: 0 16px; } .buttons { flex-direction: column; } }
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
        <a href="login.php?redirect=product.php?id=<?= $id ?>">Login</a>
      <?php endif; ?>
      <span class="icon">&#9906;</span>
      <span class="icon">&#128722;</span>
    </div>
  </div>
  <div class="nav-row-2">
    <span>Shop</span>
  </div>
</nav>

<div class="product-page">
  <div class="product-image">
    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
  </div>
  <div class="product-details">
    <h1 class="product-title"><?= htmlspecialchars($product['title']) ?></h1>
    <p class="product-price">$<?= htmlspecialchars($product['price']) ?></p>
    <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
    <form method="post">
      <div class="quantity">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1">
      </div>
      <div class="buttons">
        <button type="submit" name="add_to_cart" class="btn btn-add">Add to Cart</button>
        <button type="submit" name="checkout" class="btn btn-checkout">Checkout</button>
      </div>
    </form>
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