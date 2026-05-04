<?php
include "DBConn.php";
session_start();
$logged_in = isset($_SESSION['user_email']);
$user_email = $logged_in ? $_SESSION['user_email'] : '';
$store_name = 'Bpleasant.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($store_name) ?> – Chat</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap');
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Montserrat', sans-serif; background: #f5f5f5; color: #111; min-height: 100vh; }
    nav { display: flex; justify-content: space-between; align-items: center; padding: 18px 28px; background: #111; color: #fff; }
    nav a { color: #fff; text-decoration: none; font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    .page { max-width: 980px; margin: 32px auto; padding: 0 24px; }
    .hero { padding: 28px 30px; background: #fff; border-radius: 22px; box-shadow: 0 22px 48px rgba(0,0,0,.08); }
    .hero h1 { font-size: 2.6rem; margin-bottom: 16px; }
    .hero p { color: #555; line-height: 1.8; margin-bottom: 24px; }
    .chat-card { margin-top: 24px; display: grid; gap: 18px; }
    .chat-card label { font-size: 0.85rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #555; }
    .chat-card input, .chat-card textarea { width: 100%; padding: 16px 18px; border: 1px solid #ddd; border-radius: 16px; font-family: 'Montserrat', sans-serif; font-size: 0.95rem; }
    .chat-card textarea { min-height: 220px; resize: vertical; }
    .chat-card button { width: fit-content; padding: 14px 28px; border: none; border-radius: 999px; background: #111; color: #fff; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; cursor: pointer; }
    .chat-card button:hover { background: #333; }
    .toast { position: fixed; right: 24px; bottom: 24px; min-width: 240px; max-width: 320px; padding: 18px 20px; border-radius: 18px; box-shadow: 0 24px 60px rgba(0,0,0,.18); color: #fff; font-size: 0.95rem; opacity: 0; transform: translateY(20px); transition: opacity .3s ease, transform .3s ease; z-index: 999; pointer-events: none; }
    .toast-show { opacity: 1; transform: translateY(0); }
    .toast-success { background: #1e6f2f; }
    .toast-error { background: #8b0000; }
    .alert { display: none; }
    .note { font-size: 0.95rem; color: #777; margin-top: 16px; }
  </style>
</head>
<body>
  <nav>
    <div><?= htmlspecialchars($store_name) ?> Chat</div>
    <div><a href="index.php">Home</a></div>
  </nav>
  <main class="page">
    <section class="hero">
      <h1>Chat with us</h1>
      <p>Need help with your collection, order, or account? Send us a message and we'll get back to you as soon as possible.</p>
      <?php if (!empty($_SESSION['chat_error']) || !empty($_SESSION['chat_success'])): ?>
        <?php $toast_message = !empty($_SESSION['chat_success']) ? $_SESSION['chat_success'] : $_SESSION['chat_error']; ?>
        <?php $toast_class = !empty($_SESSION['chat_success']) ? 'toast-success' : 'toast-error'; ?>
        <?php unset($_SESSION['chat_error'], $_SESSION['chat_success']); ?>
        <div id="toast" class="toast <?= $toast_class ?>"><?= htmlspecialchars($toast_message) ?></div>
      <?php endif; ?>
      <form class="chat-card" method="post" action="contact-submit.php">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="<?= htmlspecialchars($user_email) ?>" placeholder="you@example.com" required>
        <label for="message">Message</label>
        <textarea id="message" name="message" placeholder="Write your question here..." required></textarea>
        <button type="submit">Send message</button>
      </form>
      <p class="note">If you want, I can also add a working contact form handler so this page saves messages or sends an email.</p>
    </section>
  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var toast = document.getElementById('toast');
      if (!toast) return;
      setTimeout(function () { toast.classList.add('toast-show'); }, 50);
      setTimeout(function () { toast.classList.remove('toast-show'); }, 4500);
    });
  </script>
</body>
</html>
