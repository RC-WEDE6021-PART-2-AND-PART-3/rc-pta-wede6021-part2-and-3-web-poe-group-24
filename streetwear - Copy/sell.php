<?php
include "DBConn.php";
session_start();
$logged_in = isset($_SESSION['user_email']);

if (!$logged_in) {
    header('Location: login.php?redirect=sell.php');
    exit;
}

$message = '';
$success = false;

// Create seller_requests table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS tblSellerRequest (
    request_id   INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT,
    product_name VARCHAR(255),
    brand        VARCHAR(255),
    description  TEXT,
    image_path   VARCHAR(500),
    status       ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
)");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name'] ?? '');
    $brand        = trim($_POST['brand'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $user_id      = $_SESSION['user_id'];
    $image_path   = '';

    if (empty($product_name) || empty($brand) || empty($description)) {
        $message = 'Please fill in all fields.';
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $ftype   = $_FILES['image']['type'];
            if (!in_array($ftype, $allowed)) {
                $message = 'Only JPG, PNG, WEBP, GIF images are allowed.';
            } else {
                $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename   = 'seller_uploads/' . uniqid('item_') . '.' . $ext;
                if (!is_dir('seller_uploads')) mkdir('seller_uploads', 0755, true);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $filename)) {
                    $image_path = $filename;
                } else {
                    $message = 'Image upload failed. Try again.';
                }
            }
        } else {
            $message = 'Please upload a photo of the item.';
        }

        if (empty($message)) {
            $stmt = $conn->prepare(
                "INSERT INTO tblSellerRequest (user_id, product_name, brand, description, image_path)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("issss", $user_id, $product_name, $brand, $description, $image_path);
            if ($stmt->execute()) {
                $success = true;
                $message = 'Your item has been submitted! The admin will review it and get back to you.';
            } else {
                $message = 'Something went wrong. Please try again.';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bpleasant. – Sell Your Clothes</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;600;700;800&display=swap');
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Montserrat', sans-serif; background: #f7f7f7; color: #111; min-height: 100vh; }

    nav { background: #111; padding: 0 40px; }
    .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 62px; }
    nav a { text-decoration: none; color: #fff; font-size: 12px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; }
    nav a:hover { color: #aaa; }
    .nav-brand { font-size: 16px; letter-spacing: .2em; }
    .nav-right { display: flex; gap: 22px; align-items: center; }

    .page { max-width: 680px; margin: 50px auto; padding: 0 24px 80px; }
    .page-header { margin-bottom: 32px; }
    .page-header .eyebrow { font-size: 11px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #888; margin-bottom: 8px; }
    .page-header h1 { font-family: 'Bebas Neue', sans-serif; font-size: 42px; letter-spacing: .08em; line-height: 1; }
    .page-header p { margin-top: 12px; font-size: 14px; color: #555; line-height: 1.7; }

    .card { background: #fff; padding: 36px; border: 1px solid #e8e8e8; }

    .msg { padding: 14px 18px; border-radius: 2px; font-size: 13px; font-weight: 600; margin-bottom: 24px; }
    .msg.success { background: #e6f9ee; color: #1a7a40; border-left: 4px solid #1a7a40; }
    .msg.error   { background: #fdecea; color: #b71c1c; border-left: 4px solid #b71c1c; }

    .form-group { margin-bottom: 22px; }
    .form-group label { display: block; font-size: 10px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #555; margin-bottom: 7px; }
    .form-group input[type="text"],
    .form-group textarea { width: 100%; border: 1px solid #ddd; padding: 11px 14px; font-family: 'Montserrat', sans-serif; font-size: 14px; color: #111; outline: none; background: #fafafa; transition: border .2s; }
    .form-group input[type="text"]:focus,
    .form-group textarea:focus { border-color: #111; background: #fff; }
    .form-group textarea { resize: vertical; min-height: 110px; }

    /* Image upload */
    .upload-area { border: 2px dashed #ccc; padding: 30px; text-align: center; cursor: pointer; transition: border .2s; background: #fafafa; position: relative; }
    .upload-area:hover { border-color: #111; }
    .upload-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .upload-icon { font-size: 32px; margin-bottom: 8px; }
    .upload-area p { font-size: 12px; color: #888; }
    .upload-area strong { font-size: 13px; color: #111; }
    #preview { display: none; margin-top: 14px; }
    #preview img { max-width: 100%; max-height: 220px; object-fit: cover; border: 1px solid #eee; }

    .submit-btn { display: block; width: 100%; background: #111; color: #fff; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; border: none; padding: 16px; cursor: pointer; margin-top: 8px; transition: background .2s; }
    .submit-btn:hover { background: #333; }

    .note { margin-top: 16px; font-size: 11px; color: #999; text-align: center; line-height: 1.6; }

    /* CHAT */
    .chats-btn { position: fixed; bottom: 26px; right: 26px; background: #111; color: #fff; display: flex; align-items: center; gap: 10px; padding: 14px 24px; border-radius: 50px; font-size: 13px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; text-decoration: none; z-index: 999; transition: background .2s; }
    .chats-btn:hover { background: #333; }
    .chats-btn svg { width: 22px; height: 22px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
  </style>
</head>
<body>

<nav>
  <div class="nav-inner">
    <a href="index.php" class="nav-brand">Bpleasant.</a>
    <div class="nav-right">
      <a href="shop.php">Shop</a>
      <a href="cart.php">Cart</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="page">
  <div class="page-header">
    <div class="eyebrow">Seller Portal</div>
    <h1>SELL YOUR CLOTHES</h1>
    <p>Submit your clothing item for review. Include your brand name, a description, and a clear photo. Our admin team will review your request and contact you.</p>
  </div>

  <div class="card">
    <?php if ($message): ?>
      <div class="msg <?= $success ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST" enctype="multipart/form-data" action="">

      <div class="form-group">
        <label for="product_name">Item / Product Name *</label>
        <input type="text" id="product_name" name="product_name" placeholder="e.g. Oversized Cargo Pants" required value="<?= htmlspecialchars($_POST['product_name'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label for="brand">Brand Name *</label>
        <input type="text" id="brand" name="brand" placeholder="e.g. Nike, Off-White, Vintage, Custom..." required value="<?= htmlspecialchars($_POST['brand'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label for="description">Description *</label>
        <textarea id="description" name="description" placeholder="Describe the item: size, condition, colour, material, why it's special..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label>Photo of Item *</label>
        <div class="upload-area" onclick="document.getElementById('imageInput').click()">
          <input type="file" id="imageInput" name="image" accept="image/*" onchange="previewImage(this)">
          <div class="upload-icon">📷</div>
          <strong>Click to upload a photo</strong>
          <p>JPG, PNG, WEBP or GIF — clear photo on a plain background preferred</p>
        </div>
        <div id="preview">
          <img id="previewImg" src="" alt="Preview">
        </div>
      </div>

      <button type="submit" class="submit-btn">Submit for Review</button>
      <p class="note">By submitting, you confirm this item is yours to sell. Admin will contact you via your registered email: <strong><?= htmlspecialchars($_SESSION['user_email']) ?></strong></p>
    </form>
    <?php else: ?>
      <div style="text-align:center; padding: 20px 0;">
        <p style="font-size:16px; margin-bottom:20px;">✅ Submitted successfully!</p>
        <a href="sell.php" style="display:inline-block; background:#111; color:#fff; padding:12px 28px; font-size:12px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; text-decoration:none;">Submit Another Item</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<a href="chat.php" class="chats-btn">
  <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="13" y2="14"/></svg>
  CHATS
</a>

<script>
function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('previewImg').src = e.target.result;
      document.getElementById('preview').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
</body>
</html>