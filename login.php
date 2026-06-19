<?php
include "DBConn.php";
// logian.php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember_me']);

    $res = $conn->query("SELECT * FROM tblUser WHERE email='$email'");

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();

        if (!$user['is_verified']) {
            $error = "Account not verified by admin";
        }
        else if (password_verify($password, $user['password'])) {

            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_id']    = $user['user_id'];

            if ($remember) {
                setcookie('remember_email', $email, time() + (86400 * 30), '/');
            }

            echo "User " . $_SESSION['user_name'] . " is logged in";
            exit;
        } else {
            $error = 'Wrong password';
        }

    } else {
        $error = 'User not found';
    }
}


$cookie_email = $_COOKIE['remember_email'] ?? '';
$email_value = $_POST['email'] ?? $cookie_email;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bpleasant. – Sign In</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;600;700;800&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Montserrat', sans-serif;
      background: #fff;
      min-height: 100vh;
    }

    /* ── NAV ── */
    nav {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 36px;
      padding: 14px 44px;
      background: #fff;
      border-bottom: 1px solid #ddd;
    }
    nav a {
      text-decoration: none;
      font-size: 12px;
      font-weight: 700;
      color: #111;
      letter-spacing: .12em;
      text-transform: uppercase;
    }
    nav a:hover { color: #555; }

    /* ── PAGE WRAPPER ── */
    .page {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 60px 20px 80px;
    }

    /* ── LOGIN CARD ── */
    .card {
      position: relative;
      width: 100%;
      max-width: 620px;
      min-height: 420px;
      overflow: hidden;
      background: #1a1a1a;
      display: flex;
      align-items: stretch;
    }

    /* Background image – user replaces this */
    .card-bg {
      position: absolute;
      inset: 0;
      background-image: url('./cover.jpg');
      background-size: cover;
      background-position: center top;
      filter: brightness(0.55) grayscale(30%);
      z-index: 0;
    }

    /* Dark gradient overlay on left side */
    .card-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to right, rgba(0,0,0,0.10) 0%, rgba(0,0,0,0.02) 100%);
      z-index: 1;
    }

    /* ── WELCOME BACK text (left) ── */
    .welcome {
      position: relative;
      z-index: 2;
      display: flex;
      align-items: flex-end;
      padding: 36px 0 36px 28px;
      flex: 0 0 42%;
    }
    .welcome h1 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 34px;
      color: #fff;
      letter-spacing: .08em;
      line-height: 1.05;
      text-shadow: 1px 2px 8px rgba(0,0,0,.5);
    }

    /* ── SIGN-IN FORM (right) ── */
    .form-side {
      position: relative;
      z-index: 2;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 40px 32px 40px 24px;
    }

    .brand {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .14em;
      color: #ccc;
      text-transform: lowercase;
      margin-bottom: 4px;
    }

    .form-side h2 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 28px;
      letter-spacing: .14em;
      color: #fff;
      margin-bottom: 20px;
    }

    /* Error message */
    .error-msg {
      background: rgba(220, 50, 50, 0.85);
      color: #fff;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: .06em;
      padding: 8px 12px;
      margin-bottom: 14px;
      border-radius: 2px;
    }

    /* Field label */
    .field-label {
      display: block;
      font-size: 9px;
      font-weight: 700;
      letter-spacing: .18em;
      color: #bbb;
      text-transform: uppercase;
      margin-bottom: 5px;
    }

    /* Input */
    .form-side input[type="email"],
    .form-side input[type="password"] {
      width: 100%;
      background: rgba(255,255,255,0.92);
      border: none;
      outline: none;
      font-family: 'Montserrat', sans-serif;
      font-size: 12px;
      color: #111;
      padding: 9px 12px;
      margin-bottom: 14px;
      border-radius: 0;
    }
    .form-side input[type="email"]:focus,
    .form-side input[type="password"]:focus {
      background: #fff;
      box-shadow: 0 0 0 2px rgba(255,255,255,0.6);
    }

    /* Remember Me */
    .remember {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 22px;
    }
    .remember input[type="checkbox"] {
      width: 14px;
      height: 14px;
      accent-color: #fff;
      cursor: pointer;
    }
    .remember label {
      font-size: 9px;
      font-weight: 700;
      letter-spacing: .18em;
      color: #bbb;
      text-transform: uppercase;
      cursor: pointer;
    }

    /* LOGIN button */
    .btn-login {
      display: block;
      width: 100%;
      background: #111;
      color: #fff;
      font-family: 'Montserrat', sans-serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .22em;
      text-transform: uppercase;
      border: none;
      padding: 13px 0;
      cursor: pointer;
      transition: background .2s;
    }
    .btn-login:hover { background: #333; }

    /* ── Section heading above the card (document style) ── */
    .section-label {
      max-width: 620px;
      width: 100%;
      margin: 0 auto 18px;
    }
    .section-label h3 {
      font-size: 14px;
      font-weight: 700;
      color: #111;
      margin-bottom: 6px;
    }
    .section-label p {
      font-size: 12px;
      color: #333;
      line-height: 1.6;
    }
    .section-label p strong {
      font-weight: 700;
    }

    .page-inner {
      width: 100%;
      max-width: 620px;
    }
  </style>
</head>
<body>

  <!-- NAV -->
  <nav>
    <a href="#">Shop Now</a>
    <a href="#">Rewards</a>
  </nav>

  <!-- PAGE -->
  <div class="page">
    <div class="page-inner">

      <!-- Card -->
      <div class="card">
        <div class="card-bg"></div>
        <div class="card-overlay"></div>

        <!-- Left: Welcome Back -->
        <div class="welcome">
          <h1>WELCOME<br>BACK</h1>
        </div>

        <!-- Right: Sign In Form -->
        <div class="form-side">
          <span class="brand">Bpleasant.</span>
          <h2>SIGN IN</h2>

       

          <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <label class="field-label" for="email">Email Address</label>
            <input
              type="email"
              id="email"
              name="email"
              value="<?= htmlspecialchars($email_value) ?>"
              required
              autocomplete="email"
            >

            <label class="field-label" for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              required
              autocomplete="current-password"
            >

            <div class="remember">
              <input type="checkbox" id="remember_me" name="remember_me"
                <?= $cookie_email ? 'checked' : '' ?>>
              <label for="remember_me">Remember Me</label>
            </div>

            <button type="submit" class="btn-login">LOGIN</button>
</form>

<p style="color:white; margin-top:10px;">
    Don't have an account? 
    <a href="register.php" style="color:lightblue;">Register here</a>
</p>
        </div>
      </div>

    </div>
  </div>

</body>
</html>