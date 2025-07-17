<?php require_once 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-adsense-account" content="ca-pub-7025615062481871">
    <meta name="description" content="Spice Villa Sopore official website,SpiceVilla Grill and Cafe Sopore is one of the most famous restaurants in Sopore. We offer a wide variety of foods.  ">
    <meta name="keywords" content="SpiceVilla, spice-villa Grill and Cafe, Spice Villa Sopore official site">
    <title>SpiceVilla</title>
    <link rel="stylesheet" href="spicevilla.css">
    <link rel="stylesheet" media="screen and (max-width: 1160px)" href="phone.css">
    <link rel="shortcut icon" type="x-icon" href="spl.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav id="navbar" class="h-nav-resp navbar">
        <div id="spl">
            <img src="spl.png" alt="SpiceVilla Sopore">
        </div>
        <ul type="none" class="navList v-class-resp nav-list">
    <li id="item1"><a href="index.php">Home</a></li>
    <li id="item2"><a href="menu.php">Our Menu</a></li>
    <li id="item2"><a href="index.php#services-container">Services</a></li>
    <li id="item3"><a href="[https://maps.app.goo.gl/jC4bgHg9yQMtGdLh7?g_st=ic](https://maps.app.goo.gl/jC4bgHg9yQMtGdLh7?g_st=ic)">Directions</a></li>
    <li id="item4"><a href="[https://www.gatoes.com/shop/spice-villa-sopore-193201-1539](https://www.gatoes.com/shop/spice-villa-sopore-193201-1539)">Gatoes</a></li>
    <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="view_orders.php">My Orders</a></li>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <li><a href="admin_dashboard.php">Admin Panel</a></li>
        <?php endif; ?>
        <li><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
    <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    <?php endif; ?>
</ul>
        <div class="burger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </nav>
    <section id="home">
        <h1 class="hprimary">SpiceVilla Grill & Cafe</h1>
        <button class="btn">
            <a href="menu.php">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Order Now
            </a>
        </button>
    </section>
    <section id="services-container">
        <h1 class="s-primary center">CHOOSE WHAT SUITS YOU BEST! WE STILL LOVE YOU.</h1>
        <div id="services">
            <div class="box">
                <img src="dine.png" alt="">
                <h2 class="s-secondry center">BOOK TABLE</h2>
                <p class="para">Now you can book table just by one click.
                    Please click on the button below to book your table now.

                    <br>
                    <button class="button">
                        <a href="https://wa.me/917780861722?text=Hi, I would like to make a reservation.">BOOK TABLE
                        </a>
                    </button>
                </p>
            </div>
            <div class="box">
                <img src="delivery.png" alt="">
                <h2 class="s-secondry center">DELIVERY</h2>
                <p class="para2">SpiceVilla provides delivery services, you can order food online
                    from us through Gatoes. Please click on the button below to order from us. You will
                    be redirected to Gatoes official website.<br>
                    <button class="button">
                        <a href="menu.php">
                            CLICK HERE TO ORDER
                        </a>
                    </button>
                </p>
            </div>
            <div class="box">
                <img src="pizza.png" alt="">
                <h2 class="s-secondry center">DINE IN</h2>
                <p class="para">OR you can just walk into SpiceVilla and dine in.
                    SpiceVilla provides a great ambience to customers.
                </p>
            </div>
        </div>
    </section>
    <section class="menu">
        <h1 class="menu-title center">CHECK OUT OUR SPECIALS</h1>
        <div id="menu">
            <div class="menu-box">
                <h2 class="m-primary center">PIZZA</h2>
                <ul class="leaders">
                    <li>
                        <span class="name">Chicken Blast </span>
                        <span class="name"> &#8377;220</span>
                    </li>
                    <li><span class="name">Chicken Peri-Peri</span>
                        <span class="name">&#8377;240</span>
                    </li>
                    <li><span class="name">99 Special</span>
                        <span class="name">&#8377;99</span>
                    </li>
                    <li><span class="name">Cheese Burst</span>
                        <span class="name">&#8377;170</span>
                    </li>
                    <li><span class="name">Tandoori Paneer</span>
                        <span class="name">&#8377;270</span>
                    </li>
                    <li><span class="name">Mushroom Pizza</span>
                        <span class="name">&#8377;220</span>
                    </li>
                    <li><span class="name">Chicken Kabab</span>
                        <span class="name">&#8377;270</span>
                    </li>
                    <li><span class="name">SpiceVilla Special</span>
                        <span class="name">&#8377;350</span>
                    </li>
                    <li><span class="name">Note: These are starting prices.</span>
                    </li>
                </ul>
            </div>
            <div class="menu-box">
                <h2 class="m-primary center">INDIAN</h2>
                <ul class="leaders">
                    <li>
                        <span class="name">Butter Chicken</span>
                        <span class="name">&#8377;650</span>
                    </li>
                    <li><span class="name">Chicken Changezi</span>
                        <span class="name">&#8377;650</span>
                    </li>
                    <li><span class="name">Chicken Korma</span>
                        <span class="name">&#8377;650</span>
                    </li>
                    <li><span class="name">Kabab Kanti</span>
                        <span class="name">&#8377;200</span>
                    </li>
                    <li><span class="name">Wazwan Biryani</span>
                        <span class="name">&#8377;449</span>
                        <li><span class="name">Crispy Chicken</span>
                            <span class="name">&#8377;699</span>
                        </li>
                        <li><span class="name">Chicken Tandoori</span>
                            <span class="name">&#8377;500</span>
                        </li>
                        <li><span class="name">Chicken Afghani</span>
                            <span class="name">&#8377;600</span>
                        </li>
                        <li><span class="name">Note: These are full dish prices.</span></li>
                </ul>
            </div>
            <div class="menu-box">
                <h2 class="m-primary center">CHINESE</h2>
                <ul class="leaders">
                    <li>
                        <span class="name">Chicken Fried Rice</span>
                        <span class="name">&#8377;220</span>
                    </li>
                    <li><span class="name">Veg Fried Rice</span>
                        <span class="name">&#8377;170</span>
                    </li>
                    <li><span class="name">Honey Chilli Potato</span>
                        <span class="name">&#8377;140</span>
                    </li>
                    <li><span class="name">Chicken Chowmein</span>
                        <span class="name">&#8377;170</span>
                    </li>
                    <li><span class="name">Veg Chowmein</span>
                        <span class="name">&#8377;120</span>
                        <li><span class="name">Chilli Chicken</span>
                            <span class="name">&#8377;500</span>
                        </li>
                        <li><span class="name">Chicken Tandoori</span>
                            <span class="name">&#8377;500</span>
                        </li>
                        <li><span class="name">Schezwan Fried Rice</span>
                            <span class="name">&#8377;260</span>
                        </li>
                        <li><span class="name">Note: These are full dish prices.</span></li>
                </ul>
            </div>
            
        </div>
    </section>
    <section class="menu">
        <h1 class="menu-title center">OTHER SPECIAL DISHES</h1>
        <div id="menu">
            <div class="menu-box">
                <h2 class="m-primary center">PASTA & ROLLS</h2>
                <ul class="leaders">
                    <li>
                        <span class="name">White Chicken Pasta </span>
                        <span class="name"> &#8377;220</span>
                    </li>
                    <li><span class="name">Red Chicken Pasta</span>
                        <span class="name">&#8377;220</span>
                    </li>
                    <li><span class="name">Mushroom Pasta</span>
                        <span class="name">&#8377;200</span>
                    </li>
                    <li><span class="name">Cheesy Chicken Pasta</span>
                        <span class="name">&#8377;170</span>
                    </li>
                    <li><span class="name">Chicken Roll</span>
                        <span class="name">&#8377;120</span>
                    </li>
                    <li><span class="name">Shawarma</span>
                        <span class="name">&#8377;99</span>
                    </li>
                    <li><span class="name">Jumbo Shawarma</span>
                        <span class="name">&#8377;150</span>
                    </li>
                    <li><span class="name">Labanese Shawarma</span>
                        <span class="name">&#8377;199</span>
                    </li>
                </ul>
            </div>
            <div class="menu-box">
                <h2 class="m-primary center">BURGERS & SANDWICHS</h2>
                <ul class="leaders">
                    <li>
                        <span class="name">Classic Chicken Burger </span>
                        <span class="name"> &#8377;150</span>
                    </li>
                    <li><span class="name"> Crispy Chicken Burger</span>
                        <span class="name">&#8377;249</span>
                    </li>
                    <li><span class="name">Smashed Chicken Burger</span>
                        <span class="name">&#8377;200</span>
                    </li>
                    <li><span class="name">Veg Burger</span>
                        <span class="name">&#8377;59</span>
                    </li>
                    <li><span class="name">Chicken Sandwich Plain</span>
                        <span class="name">&#8377;60</span>
                    </li>
                    <li><span class="name">Chicken Sandwich Grilled</span>
                        <span class="name">&#8377;70</span>
                    </li>
                    <li><span class="name">Veg Sandwich Plain</span>
                        <span class="name">&#8377;30</span>
                    </li>
                    <li><span class="name">Veg Sanwich Grilled</span>
                        <span class="name">&#8377;40</span>
                    </li>
                </ul>
            </div>
            <div class="menu-box">
                <h2 class="m-primary center">SHAKES & MOCKTAILS</h2>
                <ul class="leaders">
                    <li>
                        <span class="name">Kitkat Shake</span>
                        <span class="name"> &#8377;150</span>
                    </li>
                    <li><span class="name">Oreo Shake</span>
                        <span class="name">&#8377;150</span>
                    </li>
                    <li><span class="name">Cold Coffe Shake</span>
                        <span class="name">&#8377;100</span>
                    </li>
                    <li><span class="name">Green Apple</span>
                        <span class="name">&#8377;99</span>
                    </li>
                    <li><span class="name">Blue Angel</span>
                        <span class="name">&#8377;99</span>
                    </li>
                    <li><span class="name">Mint Mojito</span>
                        <span class="name">&#8377;99</span>
                    </li>
                    <li><span class="name">Orange Malt</span>
                        <span class="name">&#8377;99</span>
                    </li>
                    <li><span class="name">Watermelon</span>
                        <span class="name">&#8377;99</span>
                    </li>
                    <li><span class="name">Fresh Lime</span>
                        <span class="name">&#8377;50</span>
                    </li>
                    <li><span class="name">Mint Lime</span>
                        <span class="name">&#8377;60</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <footer>
        <div class="footerContainer">
            <div class="socialicons">
            <a href="https://www.facebook.com/profile.php?id=100083209028271&mibextid=LQQJ4d"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://www.instagram.com/spicevilla2022"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://maps.app.goo.gl/jC4bgHg9yQMtGdLh7?g_st=ic"><i class="fa-brands fa-google"></i></a>
    </div>
    <div class="footerNav">
        <ul>
            <li><a href="about.php">About Us</a></li>
            <li><a href="#home">Home</a></li>
            <li><a href="#home">Go to Top</a></li>
            <li><a href="https://wa.me/917780861722">Contact Us</a></li>
        </ul>
    </div>
    <div class="footerbottom">
     <p>Copyright SpiceVilla Sopore&copy; 2025. Designed By <span class="designer">Ishaan Ajaz & Musaib Lone</span></p>
    </div>
    </div>
</footer>
<script src="phone.js"></script>
</body>

</html>