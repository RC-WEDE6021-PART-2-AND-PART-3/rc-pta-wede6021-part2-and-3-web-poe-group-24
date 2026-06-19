<?php
include "DBConn.php";
session_start();

// Already logged in? Go home
if (isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember_me']);
    $redirect = $_POST['redirect'] ?? 'index.php';

    // ✅ FIXED: prepared statement (no SQL injection)
    $stmt = $conn->prepare("SELECT * FROM tblUser WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (!$user['is_verified']) {
            $error = "Account not yet verified by admin. Please wait.";
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_id']    = $user['user_id'];

            if ($remember) {
                setcookie('remember_email', $email, time() + (86400 * 30), '/');
            }

            // ✅ FIXED: actually redirects now
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Incorrect password. Try again.';
        }
    } else {
        $error = 'No account found with that email.';
    }
    $stmt->close();
}

$cookie_email = $_COOKIE['remember_email'] ?? '';
$email_value  = $_POST['email'] ?? $cookie_email;
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
    body { font-family: 'Montserrat', sans-serif; background: #fff; min-height: 100vh; }

    nav { display: flex; justify-content: flex-end; align-items: center; gap: 36px; padding: 14px 44px; background: #fff; border-bottom: 1px solid #ddd; }
    nav a { text-decoration: none; font-size: 12px; font-weight: 700; color: #111; letter-spacing: .12em; text-transform: uppercase; }
    nav a:hover { color: #555; }

    .page { display: flex; justify-content: center; align-items: flex-start; padding: 60px 20px 80px; }
    .page-inner { width: 100%; max-width: 620px; }

    .card { position: relative; width: 100%; min-height: 420px; overflow: hidden; background: #1a1a1a; display: flex; align-items: stretch; }
    .card-bg { position: absolute; inset: 0; background-image: url('./cover.jpg'); background-size: cover; background-position: center top; filter: brightness(0.55) grayscale(30%); z-index: 0; }
    .card-overlay { position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.10), rgba(0,0,0,0.02)); z-index: 1; }

    .welcome { position: relative; z-index: 2; display: flex; align-items: flex-end; padding: 36px 0 36px 28px; flex: 0 0 42%; }
    .welcome h1 { font-family: 'Bebas Neue', sans-serif; font-size: 34px; color: #fff; letter-spacing: .08em; line-height: 1.05; text-shadow: 1px 2px 8px rgba(0,0,0,.5); }

    .form-side { position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 40px 32px 40px 24px; }
    .brand { font-size: 11px; font-weight: 700; letter-spacing: .14em; color: #ccc; text-transform: lowercase; margin-bottom: 4px; }
    .form-side h2 { font-family: 'Bebas Neue', sans-serif; font-size: 28px; letter-spacing: .14em; color: #fff; margin-bottom: 20px; }

    .error-msg { background: rgba(220,50,50,0.85); color: #fff; font-size: 11px; font-weight: 600; letter-spacing: .06em; padding: 8px 12px; margin-bottom: 14px; border-radius: 2px; }

    .field-label { display: block; font-size: 9px; font-weight: 700; letter-spacing: .18em; color: #bbb; text-transform: uppercase; margin-bottom: 5px; }
    .form-side input[type="email"],
    .form-side input[type="password"] { width: 100%; background: rgba(255,255,255,0.92); border: none; outline: none; font-family: 'Montserrat', sans-serif; font-size: 12px; color: #111; padding: 9px 12px; margin-bottom: 14px; }
    .form-side input:focus { background: #fff; box-shadow: 0 0 0 2px rgba(255,255,255,0.6); }

    .remember { display: flex; align-items: center; gap: 8px; margin-bottom: 22px; }
    .remember input[type="checkbox"] { width: 14px; height: 14px; accent-color: #fff; cursor: pointer; }
    .remember label { font-size: 9px; font-weight: 700; letter-spacing: .18em; color: #bbb; text-transform: uppercase; cursor: pointer; }

    .btn-login { display: block; width: 100%; background: #111; color: #fff; font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; border: none; padding: 13px 0; cursor: pointer; transition: background .2s; }
    .btn-login:hover { background: #333; }
  </style>
</head>
<body>

<nav>
  <a href="shop.php">Shop Now</a>
  <a href="rewards.php">Rewards</a>
</nav>

<div class="page">
  <div class="page-inner">
    <div class="card">
      <div class="card-bg"></div>
      <div class="card-overlay"></div>

      <div class="welcome">
        <h1>WELCOME<br>BACK</h1>
      </div>

      <div class="form-side">
        <span class="brand">Bpleasant.</span>
        <h2>SIGN IN</h2>

        <?php if ($error): ?>
          <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
          <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

          <label class="field-label" for="email">Email Address</label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($email_value) ?>" required autocomplete="email">

          <label class="field-label" for="password">Password</label>
          <input type="password" id="password" name="password" required autocomplete="current-password">

          <div class="remember">
            <input type="checkbox" id="remember_me" name="remember_me" <?= $cookie_email ? 'checked' : '' ?>>
            <label for="remember_me">Remember Me</label>
          </div>

          <button type="submit" class="btn-login">LOGIN</button>
        </form>

        <p style="color:white; margin-top:12px; font-size:11px;">
          Don't have an account?
          <a href="register.php" style="color:lightblue;">Register here</a>
        </p>
      </div>
    </div>
  </div>
</div>

</body>
</html>