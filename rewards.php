<?php
include "DBConn.php";
session_start();
$store_name = 'Bpleasant.';
$logged_in = isset($_SESSION['user_email']);
$user_name = $logged_in ? ucfirst(explode('@', $_SESSION['user_email'])[0]) : 'Guest';
$reward_points = $logged_in ? 120 : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($store_name) ?> – Rewards</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap');
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Montserrat', sans-serif; background: #fafafa; color: #131313; min-height: 100vh; }
    a { color: inherit; text-decoration: none; }
    nav { background: #111; color: #fff; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; padding: 18px 28px; }
    .nav-brand { font-size: 13px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; }
    .nav-links { display: flex; flex-wrap: wrap; gap: 18px; font-size: 12px; letter-spacing: .12em; }
    .nav-links a { color: #fff; }
    .nav-links a:hover { color: #ccc; }
    .page { max-width: 1080px; margin: 30px auto 40px; padding: 0 24px; }
    .hero { display: grid; grid-template-columns: 1fr auto; gap: 28px; align-items: center; padding: 34px 0; }
    .hero .eyebrow { font-size: 12px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #ff5e5e; margin-bottom: 14px; }
    .hero h1 { font-size: clamp(2.25rem, 3vw, 3.5rem); line-height: 1.05; max-width: 620px; }
    .hero p { margin-top: 18px; font-size: 1rem; line-height: 1.8; color: #4b4b4b; max-width: 660px; }
    .hero-actions { display: flex; flex-direction: column; gap: 14px; }
    .btn-primary, .btn-secondary { display: inline-flex; align-items: center; justify-content: center; border: none; border-radius: 999px; padding: 14px 24px; font-size: 12px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; cursor: pointer; }
    .btn-primary { background: #111; color: #fff; }
    .btn-primary:hover { background: #333; }
    .btn-secondary { background: #f4f4f4; color: #111; }
    .btn-secondary:hover { background: #e2e2e2; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-top: 32px; }
    .card { background: #fff; border-radius: 22px; padding: 26px; box-shadow: 0 20px 40px rgba(18, 18, 18, .06); }
    .card h2 { font-size: 14px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; margin-bottom: 18px; color: #111; }
    .points { font-size: 3rem; font-weight: 800; color: #111; line-height: 1; }
    .points-note { margin-top: 10px; font-size: 0.95rem; color: #5a5a5a; }
    .reward-list { margin-top: 18px; display: grid; gap: 14px; }
    .reward-item { display: flex; justify-content: space-between; align-items: center; background: #f9f9f9; padding: 16px 18px; border-radius: 16px; }
    .reward-item strong { font-weight: 700; }
    .reward-item span { font-size: 0.95rem; color: #5a5a5a; }
    .start-earning { margin: 32px 0; display: grid; gap: 18px; }
    .start-earning h2 { font-size: 22px; margin-bottom: 12px; }
    .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; }
    .step { background: #fff; border-radius: 18px; padding: 20px; box-shadow: 0 18px 32px rgba(18, 18, 18, .05); }
    .step strong { display: block; margin-bottom: 10px; font-size: 16px; }
    .step p { font-size: 0.95rem; line-height: 1.75; color: #555; }
    .panel { padding: 24px; background: #fff; border-radius: 22px; box-shadow: 0 20px 40px rgba(18, 18, 18, .05); }
    .panel h3 { font-size: 18px; margin-bottom: 18px; }
    .panel ul { list-style: none; display: grid; gap: 14px; }
    .panel li { padding: 14px 18px; background: #f9f9f9; border-radius: 14px; }
    .panel li strong { display: block; margin-bottom: 6px; font-size: 0.97rem; }
    .panel li p { font-size: 0.95rem; line-height: 1.7; color: #5b5b5b; }
    .subtle-note { margin-top: 22px; font-size: 0.95rem; color: #7b7b7b; }
    footer { max-width: 1080px; margin: 0 auto; padding: 26px 24px 0; font-size: 12px; color: #777; text-align: center; }
    @media (max-width: 840px) { .hero { grid-template-columns: 1fr; } .hero-actions { flex-direction: row; flex-wrap: wrap; } }
  </style>
</head>
<body>
  <nav>
    <div class="nav-brand"><?= htmlspecialchars($store_name) ?></div>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="ourstory.php">Our Story</a>
      <a href="shop.php">Shop</a>
      <a href="rewards.php">Rewards</a>
      <?php if ($logged_in): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php?redirect=rewards.php">Login</a>
      <?php endif; ?>
    </div>
  </nav>

  <main class="page">
    <section class="hero">
      <div>
        <p class="eyebrow">Rewards Club</p>
        <h1>Get more from every purchase.</h1>
        <p>Earn points on every order, unlock exclusive offers, and claim free drops with the Bpleasant rewards program. The more you shop, the more you earn.</p>
      </div>
      <div class="hero-actions">
        <div class="card">
          <h2>Welcome back, <?= htmlspecialchars($user_name) ?></h2>
          <div class="points"><?= number_format($reward_points) ?></div>
          <p class="points-note"><?= $logged_in ? 'Your current reward points balance' : 'Sign in to start earning points' ?></p>
        </div>
        <a href="shop.php" class="btn-primary">Start Earning</a>
        <?php if ($logged_in): ?>
          <a href="ourstory.php" class="btn-secondary">Learn how it works</a>
        <?php endif; ?>
      </div>
    </section>

    <section class="stats-grid">
      <div class="card">
        <h2>How points work</h2>
        <div class="reward-list">
          <div class="reward-item"><strong>1 Point</strong><span>per R1 spent</span></div>
          <div class="reward-item"><strong>100 Points</strong><span>= R25 off your next order</span></div>
          <div class="reward-item"><strong>300 Points</strong><span>= free shipping on your next order</span></div>
        </div>
      </div>
      <div class="card">
        <h2>Current level</h2>
        <div class="reward-list">
          <div class="reward-item"><strong><?= $reward_points >= 200 ? 'Gold Member' : ($reward_points >= 100 ? 'Silver Member' : 'Starter Member') ?></strong><span><?= $reward_points >= 200 ? 'Exclusive early access and surprise drops' : ($reward_points >= 100 ? 'Bonus points and seasonal offers' : 'Earn points with every purchase') ?></span></div>
        </div>
      </div>
      <div class="card">
        <h2>Next reward</h2>
        <div class="reward-list">
          <div class="reward-item"><strong><?= 300 - min($reward_points, 300) ?> points left</strong><span>to unlock free shipping</span></div>
        </div>
      </div>
    </section>

    <section class="start-earning">
      <h2>Start earning points today</h2>
      <div class="steps">
        <div class="step">
          <strong>1. Sign in or join</strong>
          <p>Use your account to collect points automatically on every purchase. New members start earning from their first order.</p>
        </div>
        <div class="step">
          <strong>2. Shop streetwear</strong>
          <p>Every R1 spent earns 1 reward point across our store. The more you browse, the more points you collect.</p>
        </div>
        <div class="step">
          <strong>3. Claim rewards</strong>
          <p>Redeem points for discounts, free shipping, and special offers once you reach each reward tier.</p>
        </div>
      </div>
    </section>

    <section class="panel">
      <h3>Rewards perks</h3>
      <ul>
        <li>
          <strong>Exclusive drop access</strong>
          <p>Be first in line for limited edition streetwear, collabs, and early access launches.</p>
        </li>
        <li>
          <strong>Birthday bonus</strong>
          <p>Receive a special reward gift on your birthday when you are logged in and active.</p>
        </li>
        <li>
          <strong>Member-only deals</strong>
          <p>Unlock secret sales, double-point weekends, and VIP promotions throughout the year.</p>
        </li>
      </ul>
      <p class="subtle-note">Tip: Shop now to earn points faster and move to the next rewards tier.</p>
    </section>
  </main>

  <footer>
    © <?= date('Y') ?> <?= htmlspecialchars($store_name) ?>. All rights reserved.
  </footer>
</body>
</html>
