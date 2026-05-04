<?php
session_start();

if(isset($_SESSION['user_name'])){
    echo "User " . $_SESSION['user_name'] . " is logged in";
} else {
    echo "Please login first";
}

$store_name = 'Bpleasant.';
$user_email = $_SESSION['user_email'];
$user_name = ucfirst(explode('@', $user_email)[0]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($store_name) ?> – Dashboard</title>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap');

		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
		body {
			font-family: 'Montserrat', sans-serif;
			background: #f6f6f6;
			color: #111;
			min-height: 100vh;
		}
		a { color: inherit; text-decoration: none; }

		nav {
			background: #111;
			color: #fff;
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			justify-content: space-between;
			gap: 12px;
			padding: 18px 28px;
		}
		.nav-brand { font-size: 14px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
		.nav-links { display: flex; align-items: center; gap: 22px; font-size: 12px; letter-spacing: .14em; }
		.nav-links a { color: #fff; }
		.nav-links a:hover { color: #ccc; }

		.page-head {
			max-width: 1180px;
			margin: 34px auto 0;
			padding: 0 24px;
		}
		.intro {
			display: grid;
			grid-template-columns: 1fr auto;
			gap: 18px;
			align-items: center;
			margin-bottom: 28px;
		}
		.intro h1 { font-size: 32px; margin-bottom: 10px; }
		.intro p { font-size: 14px; color: #555; max-width: 720px; }
		.btn-secondary {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 12px 22px;
			background: #111;
			color: #fff;
			font-size: 12px;
			font-weight: 700;
			letter-spacing: .16em;
			text-transform: uppercase;
			border-radius: 999px;
		}
		.btn-secondary:hover { background: #333; }

		.grid-cards {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
			gap: 18px;
			margin-bottom: 32px;
		}
		.card {
			background: #fff;
			border-radius: 18px;
			padding: 24px;
			box-shadow: 0 18px 40px rgba(11, 11, 11, .06);
		}
		.card h2 {
			font-size: 14px;
			letter-spacing: .16em;
			text-transform: uppercase;
			margin-bottom: 18px;
			color: #222;
		}
		.stat {
			display: flex;
			align-items: baseline;
			justify-content: space-between;
		}
		.stat strong { font-size: 36px; display: block; }
		.stat span { font-size: 13px; color: #666; }
		.card p { font-size: 14px; line-height: 1.8; color: #555; }

		.section {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
			gap: 20px;
			margin-bottom: 32px;
		}
		.panel {
			background: #fff;
			border-radius: 18px;
			padding: 26px;
			box-shadow: 0 18px 40px rgba(11, 11, 11, .05);
		}
		.panel h3 { font-size: 18px; margin-bottom: 18px; }
		.panel ul { list-style: none; }
		.panel li { margin-bottom: 14px; font-size: 14px; color: #444; }
		.panel li:last-child { margin-bottom: 0; }
		.panel .item-label { display: block; font-size: 12px; color: #999; margin-bottom: 4px; text-transform: uppercase; letter-spacing: .12em; }
		.panel .item-value { font-weight: 600; color: #111; }
		.panel .badge { display: inline-flex; padding: 6px 10px; background: #f2f2f2; border-radius: 999px; font-size: 11px; text-transform: uppercase; letter-spacing: .12em; color: #555; }

		footer {
			max-width: 1180px;
			margin: 0 auto 32px;
			padding: 0 24px;
			color: #666;
			font-size: 12px;
			text-align: center;
		}

		@media (max-width: 720px) {
			.intro { grid-template-columns: 1fr; }
			.nav { padding: 18px 18px; }
			.nav-links { gap: 14px; }
		}
	</style>
</head>
<body>

	<nav>
		<div class="nav-brand"><?= htmlspecialchars($store_name) ?></div>
		<div class="nav-links">
			<a href="index.php">Home</a>
			<a href="ourstory.php">Our Story</a>
			<a href="shop.php">Shop</a>
			<a href="logout.php">Logout</a>
		</div>
	</nav>

	<main class="page-head">
		<section class="intro">
			<div>
				<h1>Welcome back, <?= htmlspecialchars($user_name) ?>.</h1>
				<p>Here is your customer dashboard for <?= htmlspecialchars($store_name) ?>. Track orders, saved items, and discover new drops from one place.</p>
			</div>
			<a class="btn-secondary" href="shop.php">Browse new drops</a>
		</section>

		<div class="grid-cards">
			<div class="card">
				<h2>Account summary</h2>
				<div class="stat">
					<strong>3</strong>
					<span>Orders placed</span>
				</div>
			</div>
			<div class="card">
				<h2>Saved items</h2>
				<div class="stat">
					<strong>5</strong>
					<span>Favorites waiting</span>
				</div>
			</div>
			<div class="card">
				<h2>Last login</h2>
				<p><?= date('F j, Y \a\t g:i A') ?></p>
			</div>
		</div>

		<div class="section">
			<div class="panel">
				<h3>Quick actions</h3>
				<ul>
					<li><span class="item-label">Shop</span><span class="item-value">Browse latest collections & drops</span></li>
					<li><span class="item-label">Profile</span><span class="item-value">Update your account details</span></li>
					<li><span class="item-label">Orders</span><span class="item-value">Track shipment status</span></li>
					<li><span class="item-label">Support</span><span class="item-value">Contact the team for help</span></li>
				</ul>
			</div>

			<div class="panel">
				<h3>Recent activity</h3>
				<ul>
					<li>
						<span class="item-label">Order #4592</span>
						<span class="item-value">Processing</span>
					</li>
					<li>
						<span class="item-label">Wish list</span>
						<span class="item-value">3 items saved</span>
					</li>
					<li>
						<span class="item-label">New drop</span>
						<span class="item-value">Streetwear Essentials - available now</span>
					</li>
				</ul>
			</div>

			<div class="panel">
				<h3>Your account</h3>
				<ul>
					<li>
						<span class="item-label">Email</span>
						<span class="item-value"><?= htmlspecialchars($user_email) ?></span>
					</li>
					<li>
						<span class="item-label">Membership</span>
						<span class="badge">Streetwear Member</span>
					</li>
					<li>
						<span class="item-label">Support</span>
						<span class="item-value">support@bpleasant.co.za</span>
					</li>
				</ul>
			</div>
		</div>
	</main>

	<footer>
		© <?= date('Y') ?> <?= htmlspecialchars($store_name) ?>. All rights reserved.
	</footer>

</body>
</html>
