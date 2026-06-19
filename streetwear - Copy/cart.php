<?php
include "DBConn.php";
session_start();
$logged_in = isset($_SESSION['user_email']);

if (!$logged_in) {
    header('Location: login.php?redirect=cart.php');
    exit;
}

$products = [
    1  => ['image' => 'shop clothing/02ae30a9d316ee47d1d760f034e3c3a4.jpg', 'title' => 'Cargo Pants',        'price' => 75],
    2  => ['image' => 'shop clothing/1201de3ecb040a9012cc889d5e25d4f5.jpg', 'title' => 'Shirt',               'price' => 35],
    3  => ['image' => 'shop clothing/480edc8f2fefed1b8afe2f1df0d5cace.jpg', 'title' => 'Tracksuit',           'price' => 85],
    4  => ['image' => 'shop clothing/c4b32fc9ae9001f69e53d42f5f89b352.jpg', 'title' => 'Street Short Shirt',  'price' => 25],
    5  => ['image' => 'shop clothing/cd8dddbe4075f087e48d6eb74c60ac03.jpg', 'title' => 'Outwear',             'price' => 95],
    6  => ['image' => 'shop clothing/ef8cdbe8e63327b304adc871d30fa71f.jpg', 'title' => 'Jacket',              'price' => 120],
    7  => ['image' => 'shop clothing/f306974e0cddc1ff13789139837f08ec.jpg', 'title' => 'Nike Bomber Jacket',  'price' => 110],
    8  => ['image' => 'shop clothing/Screenshot_20260414_104307_Instagram.jpg', 'title' => 'Long Sleeve Shirt','price' => 20],
    9  => ['image' => 'shop clothing/Screenshot_20260414_104315_Instagram.jpg', 'title' => 'Winter Jacket',   'price' => 60],
    10 => ['image' => 'shop clothing/5102ceeb064591ddfd29bb0d333b1506.jpg', 'title' => 'T-Shirt',             'price' => 40],
];

// ✅ Handle remove
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header('Location: cart.php');
    exit;
}

// ✅ Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    $id  = (int)$_POST['product_id'];
    $qty = (int)$_POST['qty'];
    if ($qty <= 0) {
        unset($_SESSION['cart'][$id]);
    } elseif (isset($products[$id])) {
        $_SESSION['cart'][$id] = $qty;
    }
    header('Location: cart.php');
    exit;
}

$cart  = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $id => $qty) {
    if (isset($products[$id])) $total += $products[$id]['price'] * $qty;
}
$cart_count = array_sum($cart);
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

    nav { background: #fff; padding: 0 40px; border-bottom: 1px solid #eee; }
    .nav-row-1 { display: flex; align-items: center; justify-content: space-between; height: 62px; }
    .nav-left { display: flex; align-items: center; gap: 38px; }
    nav a { text-decoration: none; color: #111; font-size: 12px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; white-space: nowrap; }
    nav a:hover { color: #555; }
    .nav-right { display: flex; align-items: center; gap: 22px; }
    .nav-dropdown { position: relative; }
    .nav-dropdown > span { color: #111; font-size: 12px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; display: flex; align-items: center; gap: 5px; cursor: pointer; }
    .nav-dropdown > span::after { content: '∨'; font-size: 9px; }
    .dropdown-menu { display: none; position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #eee; min-width: 160px; flex-direction: column; padding: 8px 0; z-index: 200; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .nav-dropdown:hover .dropdown-menu { display: flex; }
    .dropdown-menu a { padding: 9px 18px; font-size: 11px; }
    .dropdown-menu a:hover { background: #f5f5f5; }

    /* CART PAGE */
    .cart-page { max-width: 860px; margin: 0 auto; padding: 44px 24px 100px; }
    .cart-page h1 { font-size: 28px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; margin-bottom: 32px; }

    .cart-item { display: flex; align-items: center; gap: 20px; padding: 20px 0; border-bottom: 1px solid #eee; }
    .cart-item img { width: 90px; height: 90px; object-fit: cover; border-radius: 4px; flex-shrink: 0; }
    .cart-details { flex: 1; }
    .cart-title { font-size: 15px; font-weight: 700; margin-bottom: 4px; }
    .cart-unit-price { font-size: 13px; color: #777; }

    /* ✅ Quantity controls */
    .qty-controls { display: flex; align-items: center; gap: 0; margin-top: 10px; }
    .qty-btn { background: #111; color: #fff; border: none; width: 28px; height: 28px; font-size: 16px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .2s; }
    .qty-btn:hover { background: #444; }
    .qty-input { width: 44px; height: 28px; text-align: center; border: 1px solid #ddd; font-size: 14px; font-family: 'Montserrat', sans-serif; font-weight: 600; outline: none; }
    .qty-update-btn { margin-left: 8px; background: none; border: 1px solid #111; color: #111; font-size: 10px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; padding: 5px 10px; cursor: pointer; transition: all .2s; }
    .qty-update-btn:hover { background: #111; color: #fff; }

    .cart-item-price { font-size: 16px; font-weight: 700; min-width: 70px; text-align: right; }
    .remove-btn { background: none; border: none; color: #bbb; font-size: 20px; cursor: pointer; padding: 4px; line-height: 1; transition: color .2s; }
    .remove-btn:hover { color: #e00; }

    /* TOTALS */
    .cart-footer { margin-top: 28px; display: flex; flex-direction: column; align-items: flex-end; gap: 16px; }
    .cart-total { font-size: 22px; font-weight: 800; }
    .cart-total span { color: #555; font-size: 14px; font-weight: 400; margin-right: 8px; }
    .cart-actions { display: flex; gap: 14px; flex-wrap: wrap; justify-content: flex-end; }
    .btn-continue { background: #fff; color: #111; border: 2px solid #111; padding: 13px 28px; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; text-decoration: none; transition: all .2s; }
    .btn-continue:hover { background: #111; color: #fff; }
    .btn-checkout { background: #111; color: #fff; border: 2px solid #111; padding: 13px 28px; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; text-decoration: none; transition: all .2s; }
    .btn-checkout:hover { background: #333; }

    .empty-cart { text-align: center; padding: 60px 0; }
    .empty-cart p { font-size: 18px; color: #777; margin-bottom: 20px; }

    /* CHAT */
    .chats-btn { position: fixed; bottom: 26px; right: 26px; background: #111; color: #fff; display: flex; align-items: center; gap: 10px; padding: 14px 24px; border-radius: 50px; font-size: 13px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; text-decoration: none; z-index: 999; transition: background .2s; }
    .chats-btn:hover { background: #333; }
    .chats-btn svg { width: 22px; height: 22px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    @media (max-width: 600px) { .cart-page { padding: 28px 16px 100px; } nav { padding: 0 16px; } .cart-item { flex-direction: column; align-items: flex-start; } .cart-item-price { text-align: left; } }
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
      <a href="collection.php">Collections</a>
    </div>
    <div class="nav-right">
      <a href="logout.php">Logout</a>
      <a href="cart.php">🛒 <?= $cart_count ?></a>
    </div>
  </div>
</nav>

<div class="cart-page">
  <h1>Your Cart</h1>

  <?php if (empty($cart)): ?>
    <div class="empty-cart">
      <p>Your cart is empty.</p>
      <a href="shop.php" class="btn-checkout">Start Shopping</a>
    </div>
  <?php else: ?>

    <?php foreach ($cart as $id => $qty): ?>
      <?php if (!isset($products[$id])) continue; $p = $products[$id]; ?>
      <div class="cart-item">
        <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
        <div class="cart-details">
          <div class="cart-title"><?= htmlspecialchars($p['title']) ?></div>
          <div class="cart-unit-price">$<?= $p['price'] ?> each</div>

          <!-- ✅ Quantity edit form -->
          <form method="POST" action="cart.php" class="qty-controls">
            <input type="hidden" name="product_id" value="<?= $id ?>">
            <button type="button" class="qty-btn" onclick="changeQty(this, -1)">−</button>
            <input type="number" name="qty" class="qty-input" value="<?= $qty ?>" min="1" max="99">
            <button type="button" class="qty-btn" onclick="changeQty(this, 1)">+</button>
            <button type="submit" name="update_qty" class="qty-update-btn">Update</button>
          </form>
        </div>
        <div class="cart-item-price">$<?= $p['price'] * $qty ?></div>
        <a href="cart.php?remove=<?= $id ?>" class="remove-btn" title="Remove">✕</a>
      </div>
    <?php endforeach; ?>

    <div class="cart-footer">
      <div class="cart-total"><span>Total</span>$<?= $total ?></div>
      <div class="cart-actions">
        <!-- ✅ Continue Shopping button -->
        <a href="shop.php" class="btn-continue">← Continue Shopping</a>
        <a href="checkout.php" class="btn-checkout">Proceed to Checkout →</a>
      </div>
    </div>

  <?php endif; ?>
</div>

<a href="chat.php" class="chats-btn">
  <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="13" y2="14"/></svg>
  CHATS
</a>

<script>
function changeQty(btn, delta) {
  const input = btn.parentElement.querySelector('.qty-input');
  let v = parseInt(input.value) + delta;
  if (v < 1) v = 1;
  if (v > 99) v = 99;
  input.value = v;
}
</script>

</body>
</html>