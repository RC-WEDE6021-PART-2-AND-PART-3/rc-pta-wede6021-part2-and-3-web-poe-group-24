<?php
include "DBConn.php";
session_start();

$message = "";
$success = false;

if (isset($_POST['register'])) {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');   // ✅ FIXED: was missing
    $password = trim($_POST['password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        $message = "Please fill in all fields.";
    } else {

        // Check if email already exists
        $stmt = $conn->prepare("SELECT user_id FROM tblUser WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email already registered. Try logging in.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare(
                "INSERT INTO tblUser (name, email, password, is_verified) VALUES (?, ?, ?, 0)"
            );
            $insert->bind_param("sss", $name, $email, $hashedPassword);

            if ($insert->execute()) {
                $success = true;
                $message = "Registration successful! Wait for admin to verify your account.";
            } else {
                $message = "Error registering. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bpleasant. – Register</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;600;700;800&display=swap');
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Montserrat', sans-serif; background: #fff; min-height: 100vh; }

    nav { display: flex; justify-content: flex-end; align-items: center; gap: 36px; padding: 14px 44px; background: #fff; border-bottom: 1px solid #ddd; }
    nav a { text-decoration: none; font-size: 12px; font-weight: 700; color: #111; letter-spacing: .12em; text-transform: uppercase; }
    nav a:hover { color: #555; }

    .page { display: flex; justify-content: center; align-items: flex-start; padding: 60px 20px 80px; }
    .page-inner { width: 100%; max-width: 620px; }

    .card { position: relative; width: 100%; overflow: hidden; background: #1a1a1a; display: flex; align-items: stretch; min-height: 480px; }
    .card-bg { position: absolute; inset: 0; background-image: url('./cover.jpg'); background-size: cover; background-position: center top; filter: brightness(0.55) grayscale(30%); z-index: 0; }
    .card-overlay { position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.10), rgba(0,0,0,0.02)); z-index: 1; }

    .welcome { position: relative; z-index: 2; display: flex; align-items: flex-end; padding: 36px 0 36px 28px; flex: 0 0 42%; }
    .welcome h1 { font-family: 'Bebas Neue', sans-serif; font-size: 34px; color: #fff; letter-spacing: .08em; line-height: 1.05; text-shadow: 1px 2px 8px rgba(0,0,0,.5); }

    .form-side { position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 40px 32px 40px 24px; }
    .brand { font-size: 11px; font-weight: 700; letter-spacing: .14em; color: #ccc; text-transform: lowercase; margin-bottom: 4px; }
    .form-side h2 { font-family: 'Bebas Neue', sans-serif; font-size: 28px; letter-spacing: .14em; color: #fff; margin-bottom: 20px; }

    .msg { font-size: 11px; font-weight: 600; letter-spacing: .06em; padding: 8px 12px; margin-bottom: 14px; border-radius: 2px; }
    .msg.error { background: rgba(220,50,50,0.85); color: #fff; }
    .msg.success { background: rgba(30,160,80,0.85); color: #fff; }

    .field-label { display: block; font-size: 9px; font-weight: 700; letter-spacing: .18em; color: #bbb; text-transform: uppercase; margin-bottom: 5px; }
    .form-side input[type="text"],
    .form-side input[type="email"],
    .form-side input[type="password"] { width: 100%; background: rgba(255,255,255,0.92); border: none; outline: none; font-family: 'Montserrat', sans-serif; font-size: 12px; color: #111; padding: 9px 12px; margin-bottom: 14px; }
    .form-side input:focus { background: #fff; box-shadow: 0 0 0 2px rgba(255,255,255,0.6); }

    .btn-register { display: block; width: 100%; background: #111; color: #fff; font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; border: none; padding: 13px 0; cursor: pointer; transition: background .2s; }
    .btn-register:hover { background: #333; }

    .login-link { color: lightblue; margin-top: 10px; display: block; font-size: 11px; }
  </style>
</head>
<body>

<nav>
  <a href="shop.php">Shop Now</a>
  <a href="login.php">Login</a>
</nav>

<div class="page">
  <div class="page-inner">
    <div class="card">
      <div class="card-bg"></div>
      <div class="card-overlay"></div>

      <div class="welcome">
        <h1>JOIN<br>US</h1>
      </div>

      <div class="form-side">
        <span class="brand">Bpleasant.</span>
        <h2>REGISTER</h2>

        <?php if ($message): ?>
          <div class="msg <?= $success ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="">
          <label class="field-label" for="name">Full Name</label>
          <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

          <label class="field-label" for="email">Email Address</label>
          <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

          <label class="field-label" for="password">Password</label>
          <input type="password" id="password" name="password" required>

          <button type="submit" name="register" class="btn-register">CREATE ACCOUNT</button>
        </form>
        <?php endif; ?>

        <p style="color:white; margin-top:12px; font-size:11px;">
          Already have an account?
          <a href="login.php" class="login-link">Sign in here</a>
        </p>
      </div>
    </div>
  </div>
</div>

</body>
</html>