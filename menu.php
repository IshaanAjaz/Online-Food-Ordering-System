<?php
// menu.php
// Displays the menu items for customers to browse and add to cart.

require_once 'db_connect.php';

$menu_items = [];
$sql = "SELECT id, name, description, price, category, image_url FROM menu_items WHERE is_available = TRUE ORDER BY category, name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
}
$conn->close();

// Group menu items by category
$categorized_menu = [];
foreach ($menu_items as $item) {
    $categorized_menu[$item['category']][] = $item;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Menu - SpiceVilla</title>
    <link rel="stylesheet" href="spicevilla.css">
    <link rel="stylesheet" href="phone.css" media="screen and (max-width: 1160px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Custom styles for menu page */
        body {
            background-color: #000000e7;
            color: aliceblue;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        .menu-section {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 0 15px gold;
        }
        .menu-section h1 {
            color: gold;
            text-align: center;
            margin-bottom: 40px;
            font-size: 3em;
        }
        .category-container {
            margin-bottom: 50px;
        }
        .category-container h2 {
            color: #ffd700;
            font-size: 2.2em;
            margin-bottom: 25px;
            border-bottom: 2px solid #ffd700;
            padding-bottom: 10px;
            text-align: center;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        .menu-item-card {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid gold;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
            transition: transform 0.2s ease-in-out;
        }
        .menu-item-card:hover {
            transform: translateY(-5px);
        }
        .menu-item-card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 215, 0, 0.5);
        }
        .menu-item-card h3 {
            color: gold;
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .menu-item-card p {
            font-size: 0.9em;
            color: #ccc;
            margin-bottom: 15px;
            min-height: 40px; /* Ensure consistent height for description */
        }
        .menu-item-card .price {
            font-size: 1.3em;
            color: #ffd700;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .menu-item-card .add-to-cart-btn {
            background-color: gold;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        .menu-item-card .add-to-cart-btn:hover {
            background-color: #ffd700;
        }

        /* Cart styles */
        #cart-sidebar {
            position: fixed;
            top: 0;
            right: -350px; /* Hidden by default */
            width: 290px;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            box-shadow: -5px 0 15px rgba(255, 215, 0, 0.5);
            padding: 20px;
            transition: right 0.3s ease-in-out;
            z-index: 1000;
            overflow-y: auto;
        }
        #cart-sidebar.open {
            right: 0;
        }
        #cart-sidebar h2 {
            color: gold;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid gold;
            padding-bottom: 10px;
        }
        #cart-items {
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed rgba(255, 215, 0, 0.3);
            color: aliceblue;
        }
        .cart-item-info {
            flex-grow: 1;
            text-align: left;
        }
        .cart-item-info span {
            display: block;
        }
        .cart-item-info .item-name {
            font-weight: bold;
            color: gold;
        }
        .cart-item-actions {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .cart-item-actions button {
            background-color: transparent;
            border: 1px solid gold;
            color: gold;
            padding: 3px 8px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.2s;
        }
        .cart-item-actions button:hover {
            background-color: rgba(255, 215, 0, 0.2);
        }
        #cart-total {
            color: gold;
            font-size: 1.5em;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid gold;
        }
        #checkout-btn {
            background-color: #4CAF50;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            width: 100%;
            margin-top: 30px;
            transition: background-color 0.3s ease;
        }
        #checkout-btn:hover {
            background-color: #45a049;
        }
        #toggle-cart-btn {
            position: fixed;
            top: 10px;
            right: 20px;
            background-color: gold;
            color: black;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.8em;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.7);
            z-index: 1001;
            transition: transform 0.2s ease-in-out;
        }
        #cart-count {
            position: absolute;
            top: -6px;
            right: -6px;
            background-color: red;
            color: white;
            font-size: 0.5em;
            padding: 2px 7px;
            border-radius: 50%;
            font-weight: bold;
            opacity: 78%;
        }
        #toggle-cart-btn:hover {
            transform: scale(1.1);

        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1002; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.8); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: rgba(0, 0, 0, 0.9);
            margin: auto;
            padding: 30px;
            border: 1px solid gold;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 0 20px gold;
            text-align: center;
            color: aliceblue;
        }
        .modal-content h2 {
            color: gold;
            margin-bottom: 20px;
        }
        .modal-content label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: gold;
        }
        .modal-content input[type="text"],
        .modal-content textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid gold;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            color: aliceblue;
        }
        .modal-content button {
            background-color: gold;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }
        .modal-content button:hover {
            background-color: #ffd700;
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-button:hover,
        .close-button:focus {
            color: gold;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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
            <li><a href="view_orders.php">My Orders</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
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

    <div class="menu-section">
        <h1>Our Delicious Menu</h1>

        <?php if (empty($categorized_menu)): ?>
            <p style="text-align: center; color: gold;">No menu items available at the moment. Please check back later!</p>
        <?php else: ?>
            <?php foreach ($categorized_menu as $category => $items): ?>
                <div class="category-container">
                    <h2><?php echo htmlspecialchars($category); ?></h2>
                    <div class="menu-grid">
                        <?php foreach ($items as $item): ?>
                            <div class="menu-item-card">
                                <?php if (!empty($item['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <?php else: ?>
                                    <img src="[https://placehold.co/300x150/333/gold?text=No+Image](https://placehold.co/300x150/333/gold?text=No+Image)" alt="No Image">
                                <?php endif; ?>
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p><?php echo htmlspecialchars($item['description']); ?></p>
                                <div class="price">&#8377;<?php echo number_format($item['price'], 2); ?></div>
                                <button class="add-to-cart-btn" data-id="<?php echo $item['id']; ?>" data-name="<?php echo htmlspecialchars($item['name']); ?>" data-price="<?php echo $item['price']; ?>">Add to Cart</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Floating Cart Button -->
    <button id="toggle-cart-btn">
        <i class="fa-solid fa-cart-shopping"></i>
        <span id="cart-count">0</span>
    </button>

    <!-- Cart Sidebar -->
    <div id="cart-sidebar">
        <h2>Your Cart</h2>
        <ul id="cart-items">
            <!-- Cart items will be dynamically added here -->
        </ul>
        <div id="cart-total">Total: &#8377;0.00</div>
        <button id="checkout-btn">Checkout</button>
    </div>

    <!-- Checkout Modal -->
    <div id="checkout-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Place Your Order</h2>
            <form id="checkout-form">
                <label for="delivery_address">Delivery Address:</label>
                <textarea id="delivery_address" name="delivery_address" rows="4" required></textarea>

                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>

                <button type="submit">Confirm Order</button>
                <button type="button" id="cancel-checkout">Cancel</button>
            </form>
        </div>
    </div>

    <script src="phone.js"></script>
    <script>
        let cart = JSON.parse(localStorage.getItem('spicevilla_cart')) || {};
        const cartItemsContainer = document.getElementById('cart-items');
        const cartTotalDisplay = document.getElementById('cart-total');
        const cartCountDisplay = document.getElementById('cart-count');
        const toggleCartBtn = document.getElementById('toggle-cart-btn');
        const cartSidebar = document.getElementById('cart-sidebar');
        const checkoutBtn = document.getElementById('checkout-btn');
        const checkoutModal = document.getElementById('checkout-modal');
        const closeModalBtn = document.querySelector('.close-button');
        const cancelCheckoutBtn = document.getElementById('cancel-checkout');
        const checkoutForm = document.getElementById('checkout-form');

        function saveCart() {
            localStorage.setItem('spicevilla_cart', JSON.stringify(cart));
        }

        function updateCartDisplay() {
            cartItemsContainer.innerHTML = '';
            let total = 0;
            let itemCount = 0;

            for (const itemId in cart) {
                const item = cart[itemId];
                const listItem = document.createElement('li');
                listItem.classList.add('cart-item');
                listItem.innerHTML = `
                    <div class="cart-item-info">
                        <span class="item-name">${item.name}</span>
                        <span>&#8377;${item.price.toFixed(2)} x ${item.quantity}</span>
                    </div>
                    <div class="cart-item-actions">
                        <button data-id="${itemId}" data-action="decrease">-</button>
                        <span>${item.quantity}</span>
                        <button data-id="${itemId}" data-action="increase">+</button>
                        <button data-id="${itemId}" data-action="remove">Remove</button>
                    </div>
                `;
                cartItemsContainer.appendChild(listItem);
                total += item.price * item.quantity;
                itemCount += item.quantity;
            }

            cartTotalDisplay.textContent = `Total: ₹${total.toFixed(2)}`;
            cartCountDisplay.textContent = itemCount;

            if (itemCount === 0) {
                cartItemsContainer.innerHTML = '<li style="text-align: center; color: #ccc;">Your cart is empty.</li>';
                checkoutBtn.disabled = true;
            } else {
                checkoutBtn.disabled = false;
            }

            saveCart();
        }

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const id = event.target.dataset.id;
                const name = event.target.dataset.name;
                const price = parseFloat(event.target.dataset.price);

                if (cart[id]) {
                    cart[id].quantity++;
                } else {
                    cart[id] = { id, name, price, quantity: 1 };
                }
                updateCartDisplay();
            });
        });

        cartItemsContainer.addEventListener('click', (event) => {
            const target = event.target;
            if (target.tagName === 'BUTTON') {
                const id = target.dataset.id;
                const action = target.dataset.action;

                if (action === 'increase') {
                    cart[id].quantity++;
                } else if (action === 'decrease') {
                    if (cart[id].quantity > 1) {
                        cart[id].quantity--;
                    } else {
                        delete cart[id];
                    }
                } else if (action === 'remove') {
                    delete cart[id];
                }
                updateCartDisplay();
            }
        });

        toggleCartBtn.addEventListener('click', () => {
            cartSidebar.classList.toggle('open');
        });

        checkoutBtn.addEventListener('click', () => {
            // Check if user is logged in before showing checkout modal
            <?php if (!isset($_SESSION['user_id'])): ?>
                alert('Please login to proceed with checkout.');
                window.location.href = 'login.php'; // Redirect to login page
            <?php else: ?>
                if (Object.keys(cart).length > 0) {
                    checkoutModal.style.display = 'flex';
                } else {
                    alert('Your cart is empty. Please add items before checking out.');
                }
            <?php endif; ?>
        });

        closeModalBtn.addEventListener('click', () => {
            checkoutModal.style.display = 'none';
        });

        cancelCheckoutBtn.addEventListener('click', () => {
            checkoutModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target == checkoutModal) {
                checkoutModal.style.display = 'none';
            }
        });

        checkoutForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const deliveryAddress = document.getElementById('delivery_address').value;
            const contactNumber = document.getElementById('contact_number').value;

            const orderDetails = {
                delivery_address: deliveryAddress,
                contact_number: contactNumber,
                items: Object.values(cart)
            };

            try {
                const response = await fetch('place_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderDetails)
                });

                const result = await response.json();

                if (result.success) {
                    alert('Order placed successfully! Your Order ID is: ' + result.order_id);
                    cart = {}; // Clear cart
                    saveCart();
                    updateCartDisplay();
                    checkoutModal.style.display = 'none';
                    cartSidebar.classList.remove('open'); // Close cart sidebar
                } else {
                    alert('Failed to place order: ' + result.message);
                }
            } catch (error) {
                console.error('Error placing order:', error);
                alert('An error occurred while placing your order. Please try again.');
            }
        });

        // Initial display update
        updateCartDisplay();
    </script>
</body>
</html>