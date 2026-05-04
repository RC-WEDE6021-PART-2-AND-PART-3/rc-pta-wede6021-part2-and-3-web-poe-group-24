<?php
include "DBConn.php";
session_start();
$store_name = "Bpleasant.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $store_name ?> – Our Story</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Montserrat', sans-serif;
}

body {
    background: #0f0f0f;
    color: #fff;
}

/* HERO */
.hero {
    height: 100vh;
    background: url('cover.jpg') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
}

.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero h1 {
    font-size: 50px;
    letter-spacing: 5px;
}

.hero p {
    margin-top: 15px;
    font-size: 16px;
    opacity: 0.8;
}

/* SECTION */
.section {
    padding: 80px 20px;
    max-width: 900px;
    margin: auto;
    text-align: center;
}

.section h2 {
    font-size: 28px;
    margin-bottom: 20px;
    letter-spacing: 3px;
}

.section p {
    line-height: 1.8;
    font-size: 15px;
    color: #ccc;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 40px;
}

.card {
    background: #1a1a1a;
    padding: 20px;
    border-radius: 10px;
    text-align: left;
}

/* CTA */
.cta {
    margin-top: 50px;
}

.cta a {
    display: inline-block;
    padding: 14px 40px;
    background: #fff;
    color: #000;
    text-decoration: none;
    font-weight: 700;
    letter-spacing: 2px;
    border-radius: 30px;
    transition: 0.3s;
}

.cta a:hover {
    background: #ccc;
}

/* FOOTER */
footer {
    text-align: center;
    padding: 30px;
    font-size: 12px;
    color: #777;
    border-top: 1px solid #222;
}
</style>

</head>

<body>

<!-- HERO -->
<div class="hero">
    <div class="hero-content">
        <h1><?= $store_name ?></h1>
        <p>Built from culture. Worn with purpose.</p>
    </div>
</div>

<!-- STORY -->
<div class="section">
    <h2>OUR STORY</h2>
    <p>
        <?= $store_name ?> started as a small idea in Pretoria — driven by street culture, fashion, and the need to create something real.
        We are not just a clothing brand. We are a movement that connects people through style, identity, and expression.
        Every piece we drop carries meaning, culture, and authenticity.
    </p>
</div>

<!-- VALUES -->
<div class="section">
    <h2>WHAT WE STAND FOR</h2>

    <div class="grid">
        <div class="card">
            <h3>Authenticity</h3>
            <p>Real streetwear, real culture, no fake energy.</p>
        </div>

        <div class="card">
            <h3>Community</h3>
            <p>We grow with the people who wear our brand.</p>
        </div>

        <div class="card">
            <h3>Exclusivity</h3>
            <p>Limited drops. Rare pieces. No mass production.</p>
        </div>
    </div>

    <div class="cta">
        <a href="shop.php">SHOP NOW</a>
    </div>
</div>

<!-- FOOTER -->
<footer>
    © <?= date("Y") ?> <?= $store_name ?>. All rights reserved.
</footer>
       
</body>
</html>