
<?php
session_start();
// Handle adding to cart
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    
    // Connect to database
    $connect = mysqli_connect('localhost', 'root', '', 'login');
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // Get product info from database
    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($connect, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['product_name'],
                'price' => $product['product_price'],
                'image' => $product['product_img'],
                'quantity' => 1
            ];
        }
        
    }
    
    
    mysqli_close($connect);
    
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store - Premium Footwear</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .user-info {
    display: flex;
    flex-direction: column; /* Sắp xếp các phần tử theo chiều dọc */
    align-items: flex-start; /* Căn chỉnh các phần tử sang bên trái */
    color: #f8f9fa;
    font-weight: 500;
}

.user-info span {
    font-size: 1.1rem;
    margin-bottom: 5px; /* Thêm khoảng cách giữa "Welcome, Bui" và "Logout" */
}

.user-info a {
    background-color: #ff6b6b;
    color: white;
    padding: 8px 12px;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.user-info a:hover {
    background-color: #ff5252;
}
        
        body {
            background-color: #f8f9fa;
            color: #212529;
            line-height: 1.6;
        }
        
        header {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            padding: 15px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .logo img {
            height: 50px;
            transition: transform 0.3s;
        }
        
        .logo:hover img {
            transform: scale(1.05);
        }
        
        .nav-links {
            display: flex;
            align-items: center;
        }
        
        .nav-links a {
            color: #f8f9fa;
            text-decoration: none;
            margin-left: 25px;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s, transform 0.3s;
            position: relative;
            padding: 5px 0;
        }
        
        .nav-links a:hover {
            color: #ff6b6b;
            transform: translateY(-2px);
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #ff6b6b;
            transition: width 0.3s;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .cart-icon {
            position: relative;
            margin-left: 25px;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff6b6b;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .search-container {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.1);
            border-radius: 30px;
            padding: 5px 15px;
            transition: all 0.3s;
        }
        
        .search-container:focus-within {
            background: rgba(255,255,255,0.2);
            box-shadow: 0 0 0 2px rgba(255,107,107,0.3);
        }
        
        .search-input {
            padding: 8px 15px;
            border: none;
            background: transparent;
            color: white;
            width: 200px;
            outline: none;
            font-size: 14px;
        }
        
        .search-input::placeholder {
            color: rgba(255,255,255,0.7);
        }
        
        .search-button {
            background: transparent;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: transform 0.3s;
        }
        
        .search-button:hover {
            transform: scale(1.1);
            color: #ff6b6b;
        }
        
        .banner {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('banner.jpg');
            background-size: cover;
            background-position: center;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        
        .banner::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(transparent, #f8f9fa);
        }
        
        .banner-content h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
            animation: fadeInUp 1s ease;
        }
        
        .banner-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            max-width: 700px;
            animation: fadeInUp 1s ease 0.3s forwards;
            opacity: 0;
        }
        
        .main-content {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }
        
        .section-title h2 {
            font-size: 2.2rem;
            color: #343a40;
            display: inline-block;
            padding-bottom: 10px;
        }
        
        .section-title h2::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 3px;
            background: #ff6b6b;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .shoe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .shoe-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .shoe-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .shoe-image-container {
            position: relative;
            overflow: hidden;
            height: 250px;
        }
        
        .shoe-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .shoe-card:hover .shoe-image {
            transform: scale(1.05);
        }
        
        .shoe-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ff6b6b;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .shoe-info {
            padding: 20px;
        }
        
        .shoe-title {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #343a40;
        }
        
        .shoe-price {
            font-weight: bold;
            color: #ff6b6b;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        
        .old-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.9rem;
            margin-left: 8px;
        }
        
        .shoe-specs {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .shoe-specs span {
            background: #f1f3f5;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .btn-container {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background: #343a40;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s;
            flex: 1;
            text-align: center;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #495057;
            transform: translateY(-2px);
        }
        
        .buy-btn {
            background: #ff6b6b;
        }
        
        .buy-btn:hover {
            background: #ff5252;
        }
        
        footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 60px 0 30px;
            margin-top: 60px;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            padding: 0 20px;
        }
        
        .footer-column h3 {
            margin-bottom: 20px;
            font-size: 1.3rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h3::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 2px;
            background: #ff6b6b;
            bottom: 0;
            left: 0;
        }
        
        .footer-column p {
            margin-bottom: 15px;
            color: #adb5bd;
            font-size: 14px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: #ff6b6b;
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #adb5bd;
            font-size: 14px;
        }
        
        #noResults {
            text-align: center;
            font-size: 1.2rem;
            color: #6c757d;
            padding: 60px 0;
            display: none;
        }
        
        .search-tips {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
            text-align: center;
            display: none;
        }
        
        .highlight {
            background-color: #fff9c4;
            padding: 0 2px;
            border-radius: 2px;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive styles */
        @media (max-width: 992px) {
            .banner-content h1 {
                font-size: 2.8rem;
            }
            
            nav {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-links {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .nav-links a {
                margin: 0 10px;
            }
            
            .search-container {
                margin-top: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .banner {
                height: 400px;
            }
            
            .banner-content h1 {
                font-size: 2.2rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .banner {
                height: 350px;
            }
            
            .banner-content h1 {
                font-size: 1.8rem;
            }
            
            .search-container {
                width: 100%;
            }
            
            .search-input {
                width: 100%;
            }
            
            .shoe-grid {
                grid-template-columns: 1fr;
            }
            
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="Screenshot 2025-03-05 072826.png" alt="Shoe Store Logo">
            </div>
            <div class="nav-links">
    <a href="index.php">Home</a>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="add_product.php">Add Product</a>
    <?php endif; ?>
    
    <?php if (!isset($_SESSION['username'])): ?>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
        <a href="add_product.php">Add Product</a>
    <?php else: ?>
        
    <?php endif; ?>
    
    <a href="cart.php" class="cart-icon">
        <i class="fas fa-shopping-cart"></i>
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
        <?php endif; ?>
    </a>
</div>
            <div class="search-container">
                <input type="text" id="searchInput" class="search-input" placeholder="Search shoes...">
                <button id="searchButton" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            </div>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="user-info">
                    <span>Welcome</span>
                    <a href="logout.php">Log Out</a>
                </div>
            <?php endif; ?>
        </nav>
    </header>

    <div class="banner">
        <div class="banner-content">
            <h1>Welcome to Shoe Store</h1>
            <p>Discover our diverse collection of premium quality footwear at affordable prices</p>
        </div>
    </div>

    <main class="main-content">
        <div class="section-title">
            <h2>Featured Products</h2>
            <div class="search-tips" id="searchTips">
            </div>
        </div>
        
        <div class="shoe-grid" id="shoeGrid">
            <?php
            // Connect to database
            $connect = mysqli_connect('localhost', 'root', '', 'login');
            if (!$connect) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Get product data from database
            $sql = "SELECT * FROM products";
            $result = mysqli_query($connect, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="shoe-card">';
                    echo '<div class="shoe-image-container">';
                    echo '<span class="shoe-badge">New</span>';
                    echo '<img src="Image/' . $row['product_img'] . '" alt="' . $row['product_name'] . '" class="shoe-image">';
                    echo '</div>';
                    echo '<div class="shoe-info">';
                    echo '<h3 class="shoe-title">' . $row['product_name'] . '</h3>';
                    echo '<div class="shoe-price">' . number_format($row['product_price']) . ' VND <span class="old-price">' . number_format($row['product_price'] * 1.2) . ' VND</span></div>';
                    echo '<div class="shoe-specs">';
                    echo '<span>Size 36-42</span>';
                    echo '<span>3 colors</span>';
                    echo '<span>Premium leather</span>';
                    echo '</div>';
                    echo '<div class="btn-container">';
                    echo '<a href="product_detail.php?id=' . $row['product_id'] . '" class="btn">Details</a>';
                    echo '<a href="?add_to_cart=' . $row['product_id'] . '" class="btn buy-btn">Add to Cart</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>No products available.</p>";
            }

            // Close connection
            mysqli_close($connect);
            ?>
        </div>
        <div id="noResults">No matching products found.</div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3>About Us</h3>
                <p>Shoe Store - Vietnam's leading premium footwear retailer.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h3>Contact</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 ABC Street, Hoan Kiem District, Hanoi</p>
                <p><i class="fas fa-phone"></i> 0123.456.789</p>
                <p><i class="fas fa-envelope"></i> info@shoestore.com</p>
                <p><i class="fas fa-clock"></i> Open: 8:00 AM - 9:00 PM daily</p>
            </div>
            <div class="footer-column">
                <h3>Customer Support</h3>
                <p><a href="#" style="color: #adb5bd; text-decoration: none;">Shopping Guide</a></p>
                <p><a href="#" style="color: #adb5bd; text-decoration: none;">Return Policy</a></p>
                <p><a href="#" style="color: #adb5bd; text-decoration: none;">FAQs</a></p>
                <p><a href="#" style="color: #adb5bd; text-decoration: none;">Privacy Policy</a></p>
            </div>
        </div>
        <div class="copyright">
            &copy; 2023 Shoe Store. All rights reserved.
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchInput");
            const searchButton = document.getElementById("searchButton");
            const shoeGrid = document.getElementById("shoeGrid");
            const noResults = document.getElementById("noResults");
            const searchTips = document.getElementById("searchTips");
            
            // Store all original products
            let allProducts = [];
            
            // Get product data from PHP (convert to JavaScript)
            const productCards = shoeGrid.querySelectorAll(".shoe-card");
            productCards.forEach(card => {
                const product = {
                    element: card,
                    name: card.querySelector(".shoe-title").textContent.toLowerCase(),
                    price: parseInt(card.querySelector(".shoe-price").textContent.replace(/\D/g, '')),
                    specs: card.querySelector(".shoe-specs").textContent.toLowerCase()
                };
                allProducts.push(product);
            });

            // Show search tips when focusing on search input
            searchInput.addEventListener("focus", function() {
                searchTips.style.display = "block";
            });
            
            searchInput.addEventListener("blur", function() {
                setTimeout(() => {
                    searchTips.style.display = "none";
                }, 200);
            });
            
            // Product search function
            function searchProducts() {
                const query = searchInput.value.trim().toLowerCase();
                
                // If no search term, show all products
                if (query === "") {
                    allProducts.forEach(product => {
                        product.element.style.display = "block";
                        // Remove highlight when not searching
                        removeHighlights(product.element);
                    });
                    noResults.style.display = "none";
                    shoeGrid.style.display = "grid";
                    return;
                }
                
                let foundResults = false;
                
                // Search by multiple criteria
                allProducts.forEach(product => {
                    const matchesName = product.name.includes(query);
                    const matchesSpecs = product.specs.includes(query);
                    
                    // Check if query is a price range (e.g. "500k-1tr")
                    let matchesPrice = false;
                    if (query.includes("-")) {
                        const priceRange = query.split("-");
                        const minPrice = convertPriceToNumber(priceRange[0]);
                        const maxPrice = convertPriceToNumber(priceRange[1]);
                        
                        if (!isNaN(minPrice) && !isNaN(maxPrice)) {
                            matchesPrice = product.price >= minPrice && product.price <= maxPrice;
                        }
                    } else {
                        // Check approximate price
                        const queryNumber = convertPriceToNumber(query);
                        if (!isNaN(queryNumber)) {
                            matchesPrice = product.price >= queryNumber * 0.9 && product.price <= queryNumber * 1.1;
                        }
                    }
                    
                    if (matchesName || matchesSpecs || matchesPrice) {
                        product.element.style.display = "block";
                        foundResults = true;
                        
                        // Highlight search results
                        highlightSearchResults(product.element, query);
                    } else {
                        product.element.style.display = "none";
                        removeHighlights(product.element);
                    }
                });
                
                // Show message if no results found
                if (!foundResults) {
                    noResults.style.display = "block";
                    shoeGrid.style.display = "none";
                } else {
                    noResults.style.display = "none";
                    shoeGrid.style.display = "grid";
                }
            }
            
            // Convert price string to number
            function convertPriceToNumber(priceStr) {
                // Handle common price formats
                priceStr = priceStr.toLowerCase()
                    .replace(/\s/g, '')
                    .replace(/k/g, '000')
                    .replace(/tr/g, '000000')
                    .replace(/vnđ/g, '')
                    .replace(/đ/g, '')
                    .replace(/,/g, '')
                    .replace(/\./g, '');
                
                return parseInt(priceStr) || NaN;
            }
            
            // Highlight search results
            function highlightSearchResults(element, query) {
                // Remove old highlights
                removeHighlights(element);
                
                // Only highlight if query is meaningful
                if (query.length < 2) return;
                
                // Highlight product name
                const titleElement = element.querySelector(".shoe-title");
                highlightText(titleElement, query);
                
                // Highlight specs
                const specsElement = element.querySelector(".shoe-specs");
                highlightText(specsElement, query);
                
                // Highlight price if matching
                const priceElement = element.querySelector(".shoe-price");
                const queryNumber = convertPriceToNumber(query);
                if (!isNaN(queryNumber)) {
                    const price = parseInt(priceElement.textContent.replace(/\D/g, ''));
                    if (price >= queryNumber * 0.9 && price <= queryNumber * 1.1) {
                        priceElement.classList.add("highlight");
                    }
                }
            }
            
            // Remove highlights
            function removeHighlights(element) {
                const highlights = element.querySelectorAll(".highlight");
                highlights.forEach(hl => {
                    hl.classList.remove("highlight");
                });
                
                // Restore original text
                const titleElement = element.querySelector(".shoe-title");
                if (titleElement) {
                    titleElement.innerHTML = titleElement.textContent;
                }
                
                const specsElement = element.querySelector(".shoe-specs");
                if (specsElement) {
                    specsElement.innerHTML = specsElement.textContent;
                }
            }
            
            // Highlight text within element
            function highlightText(element, query) {
                const text = element.textContent;
                const regex = new RegExp(query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), "gi");
                const newText = text.replace(regex, match => 
                    `<span class="highlight">${match}</span>`
                );
                element.innerHTML = newText;
            }
            
            // Search event on button click
            searchButton.addEventListener("click", searchProducts);
            
            // Search event on Enter key
            searchInput.addEventListener("keypress", function (event) {
                if (event.key === "Enter") {
                    searchProducts();
                }
            });
            
            // Auto search on input (with debounce for performance)
            let searchTimeout;
            searchInput.addEventListener("input", function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(searchProducts, 300);
            });
        });
    </script>
</body>
</html>