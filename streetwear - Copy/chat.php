<?php
include "DBConn.php";
session_start();
$logged_in  = isset($_SESSION['user_email']);
$is_admin   = isset($_SESSION['admin']);
$user_name  = $logged_in ? ($_SESSION['user_name'] ?? 'Customer') : ($is_admin ? 'Admin' : 'Guest');
$user_email = $_SESSION['user_email'] ?? 'admin';

// Save new message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['message'] ?? ''))) {
    $sender  = $is_admin ? 'ADMIN' : htmlspecialchars($user_name);
    $email   = htmlspecialchars($user_email);
    $msg_txt = htmlspecialchars(trim($_POST['message']));
    $line    = date('Y-m-d H:i') . "|$sender|$email|$msg_txt\n";
    file_put_contents('chat-messages.txt', $line, FILE_APPEND);
    header('Location: chat.php');
    exit;
}

// Load messages
$messages = [];
if (file_exists('chat-messages.txt')) {
    foreach (file('chat-messages.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $parts = explode('|', $line, 4);
        if (count($parts) === 4) {
            $messages[] = [
                'time'   => $parts[0],
                'sender' => $parts[1],
                'email'  => $parts[2],
                'text'   => $parts[3],
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bpleasant. – Live Chat</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap');
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Montserrat', sans-serif; background: #f4f4f4; color: #111; display: flex; flex-direction: column; height: 100vh; }

    /* NAV */
    nav { background: #111; padding: 0 28px; display: flex; align-items: center; justify-content: space-between; height: 56px; flex-shrink: 0; }
    nav a { text-decoration: none; color: #fff; font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    nav a:hover { color: #aaa; }
    .nav-brand { font-size: 18px; font-weight: 800; }

    /* LAYOUT */
    .chat-layout { display: flex; flex: 1; overflow: hidden; }
    .chat-sidebar { width: 200px; background: #fff; border-right: 1px solid #eee; padding: 20px 16px; flex-shrink: 0; }
    .chat-sidebar h3 { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #888; margin-bottom: 14px; }
    .who-badge { display: flex; align-items: center; gap: 10px; padding: 10px 12px; background: #f8f8f8; border-radius: 6px; }
    .who-badge .dot { width: 10px; height: 10px; border-radius: 50%; background: #1a7c3e; flex-shrink: 0; }
    .who-badge p { font-size: 12px; font-weight: 600; }
    .who-badge small { font-size: 10px; color: #888; display: block; }
    .sidebar-note { margin-top: 20px; font-size: 11px; color: #aaa; line-height: 1.7; }

    /* CHAT AREA */
    .chat-area { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .chat-header { background: #fff; padding: 16px 24px; border-bottom: 1px solid #eee; flex-shrink: 0; }
    .chat-header h2 { font-size: 15px; font-weight: 700; letter-spacing: .05em; }
    .chat-header p { font-size: 11px; color: #888; margin-top: 2px; }

    .chat-messages { flex: 1; overflow-y: auto; padding: 20px 24px; display: flex; flex-direction: column; gap: 14px; }

    /* BUBBLES */
    .bubble-wrap { display: flex; flex-direction: column; max-width: 68%; }
    .bubble-wrap.mine  { align-self: flex-end; align-items: flex-end; }
    .bubble-wrap.other { align-self: flex-start; align-items: flex-start; }
    .bubble-wrap.admin-msg { align-self: flex-start; align-items: flex-start; }

    .bubble { padding: 11px 16px; border-radius: 18px; font-size: 13px; line-height: 1.55; word-break: break-word; }
    .bubble-wrap.mine  .bubble { background: #111; color: #fff; border-bottom-right-radius: 4px; }
    .bubble-wrap.other .bubble { background: #fff; color: #111; border-bottom-left-radius: 4px; box-shadow: 0 1px 4px rgba(0,0,0,.07); }
    .bubble-wrap.admin-msg .bubble { background: #1a1a5e; color: #fff; border-bottom-left-radius: 4px; }

    .bubble-meta { font-size: 10px; color: #aaa; margin-top: 4px; padding: 0 4px; }

    /* INPUT */
    .chat-input { background: #fff; border-top: 1px solid #eee; padding: 16px 24px; flex-shrink: 0; }
    .input-row { display: flex; gap: 10px; align-items: flex-end; }
    .input-row textarea { flex: 1; border: 1.5px solid #ddd; padding: 11px 14px; font-family: 'Montserrat', sans-serif; font-size: 13px; resize: none; outline: none; border-radius: 24px; max-height: 100px; line-height: 1.5; }
    .input-row textarea:focus { border-color: #111; }
    .send-btn { background: #111; color: #fff; border: none; width: 44px; height: 44px; border-radius: 50%; font-size: 18px; cursor: pointer; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: background .2s; }
    .send-btn:hover { background: #333; }

    .guest-msg { text-align: center; padding: 20px; font-size: 13px; color: #888; }
    .guest-msg a { color: #111; font-weight: 700; text-decoration: underline; }

    @media (max-width: 600px) { .chat-sidebar { display: none; } }
  </style>
</head>
<body>

<nav>
  <a href="index.php" class="nav-brand">Bpleasant.</a>
  <div style="display:flex;gap:20px;">
    <a href="shop.php">Shop</a>
    <?php if ($logged_in): ?>
      <a href="logout.php">Logout</a>
    <?php elseif ($is_admin): ?>
      <a href="admin.php">Admin Panel</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </div>
</nav>

<div class="chat-layout">

  <!-- SIDEBAR -->
  <div class="chat-sidebar">
    <h3>You are</h3>
    <div class="who-badge">
      <div class="dot"></div>
      <div>
        <p><?= htmlspecialchars($is_admin ? 'Admin' : $user_name) ?></p>
        <small><?= $is_admin ? 'Administrator' : ($logged_in ? 'Customer' : 'Guest') ?></small>
      </div>
    </div>
    <p class="sidebar-note">
      <?php if ($is_admin): ?>
        You can communicate with customers and sellers here to confirm orders and deliveries.
      <?php else: ?>
        Chat with our team about your order, delivery, or any questions.
      <?php endif; ?>
    </p>
  </div>

  <!-- MAIN CHAT -->
  <div class="chat-area">
    <div class="chat-header">
      <h2>💬 Bpleasant. Support Chat</h2>
      <p>Admin is here to help with orders, deliveries and seller requests</p>
    </div>

    <div class="chat-messages" id="chatBox">
      <?php if (empty($messages)): ?>
        <p style="text-align:center;color:#aaa;font-size:13px;margin-top:30px;">No messages yet. Say hello! 👋</p>
      <?php endif; ?>

      <?php foreach ($messages as $m):
        $is_my_msg    = ($m['email'] === $user_email) && !$is_admin && $m['sender'] !== 'ADMIN';
        $is_admin_msg = ($m['sender'] === 'ADMIN');
        $wrap_class   = $is_admin_msg ? 'admin-msg' : ($is_my_msg ? 'mine' : 'other');
      ?>
      <div class="bubble-wrap <?= $wrap_class ?>">
        <div class="bubble"><?= nl2br(htmlspecialchars($m['text'])) ?></div>
        <div class="bubble-meta">
          <?= $is_admin_msg ? '🛡 Admin' : htmlspecialchars($m['sender']) ?> &middot; <?= htmlspecialchars($m['time']) ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="chat-input">
      <?php if ($logged_in || $is_admin): ?>
      <form method="POST" action="" id="chatForm">
        <div class="input-row">
          <textarea name="message" id="msgInput" placeholder="Type your message..."
                    rows="1" required
                    onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();document.getElementById('chatForm').submit();}"></textarea>
          <button type="submit" class="send-btn" title="Send">➤</button>
        </div>
      </form>
      <?php else: ?>
        <p class="guest-msg">
          <a href="login.php?redirect=chat.php">Login</a> to send a message to our team.
        </p>
      <?php endif; ?>
    </div>
  </div>

</div>

<script>
  // Auto scroll to bottom
  const chatBox = document.getElementById('chatBox');
  if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>
</body>
</html>