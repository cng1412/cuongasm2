<?php
session_start();

// Connect to database
$connect = mysqli_connect('localhost', 'root', '', 'login');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle adding to cart
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    
    // Get product info from database
    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($connect, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        // Check if cart exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Check if product already in cart
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
        
        header("Location: cart.php");
        exit();
    }
}

// Handle quantity update
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle product removal
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: cart.php");
    exit();
}

// Handle checkout - PHẦN SỬA CHÍNH Ở ĐÂY
if (isset($_POST['checkout'])) {
    // Lưu thông tin đơn hàng vào session để hiển thị thông báo
    $_SESSION['checkout_message'] = [
        'status' => 'success',
        'message' => 'Payment successful! Thank you for your purchase.'
    ];
    
    // Xóa giỏ hàng sau khi thanh toán
    unset($_SESSION['cart']);
    
    // Chuyển hướng trở lại trang giỏ hàng
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Shoe Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* GIỮ NGUYÊN TOÀN BỘ CSS CŨ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        
        .main-content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .cart-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .cart-title {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #343a40;
            border-bottom: 2px solid #ff6b6b;
            padding-bottom: 10px;
            display: inline-block;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .cart-table th {
            text-align: left;
            padding: 12px;
            background: #f1f3f5;
            border-bottom: 2px solid #dee2e6;
        }
        
        .cart-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .quantity-input {
            width: 60px;
            padding: 8px;
            text-align: center;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        .remove-btn {
            color: #dc3545;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            transition: transform 0.3s;
        }
        
        .remove-btn:hover {
            transform: scale(1.2);
        }
        
        .cart-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
        }
        
        .cart-total {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .cart-total span {
            color: #ff6b6b;
        }
        
        .cart-actions {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-continue {
            background: #6c757d;
            color: white;
        }
        
        .btn-continue:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .btn-update {
            background: #17a2b8;
            color: white;
        }
        
        .btn-update:hover {
            background: #138496;
            transform: translateY(-2px);
        }
        
        .btn-checkout {
            background: #28a745;
            color: white;
        }
        
        .btn-checkout:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .empty-cart {
            text-align: center;
            padding: 50px 0;
        }
        
        .empty-cart i {
            font-size: 5rem;
            color: #6c757d;
            margin-bottom: 20px;
        }
        
        .empty-cart p {
            font-size: 1.2rem;
            margin-bottom: 20px;
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
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #adb5bd;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .cart-table {
                display: block;
                overflow-x: auto;
            }
            
            .cart-summary {
                flex-direction: column;
                gap: 20px;
                align-items: flex-end;
            }
            
            .cart-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
        
        @media (max-width: 576px) {
            .cart-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: inherit;
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
                <a href="cart.php" style="color: #ff6b6b;">
                    <i class="fas fa-shopping-cart"></i> Cart
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span>(<?php echo count($_SESSION['cart']); ?>)</span>
                    <?php endif; ?>
                </a>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="cart-container">
            <h1 class="cart-title">Your Shopping Cart</h1>
            
            
            <?php if (isset($_SESSION['checkout_message'])): ?>
                <div class="alert alert-success">
                    <div>
                        <i class="fas fa-check-circle"></i>
                        <?php echo $_SESSION['checkout_message']['message']; ?>
                    </div>
                    <button class="alert-close" onclick="this.parentElement.style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php unset($_SESSION['checkout_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <form action="cart.php" method="post">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($_SESSION['cart'] as $product_id => $item): 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 15px;">
                                            <img src="Image/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-item-img">
                                            <div>
                                                <h3><?php echo $item['name']; ?></h3>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($item['price']); ?> VND</td>
                                    <td>
                                        <input type="number" name="quantity[<?php echo $product_id; ?>]" 
                                               value="<?php echo $item['quantity']; ?>" 
                                               min="1" class="quantity-input">
                                    </td>
                                    <td><?php echo number_format($subtotal); ?> VND</td>
                                    <td>
                                        <a href="cart.php?remove=<?php echo $product_id; ?>" class="remove-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div class="cart-summary">
                        <div class="cart-total">
                            Grand Total: <span><?php echo number_format($total); ?> VND</span>
                        </div>
                        <div class="cart-actions">
                            <a href="index.php" class="btn btn-continue">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                            <button type="submit" name="update_cart" class="btn btn-update">
                                <i class="fas fa-sync-alt"></i> Update Cart
                            </button>
                            <button type="submit" name="checkout" class="btn btn-checkout">
                                <i class="fas fa-credit-card"></i> Checkout
                            </button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                    <a href="index.php" class="btn btn-continue">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3>About Us</h3>
                <p>Shoe Store - Vietnam's leading footwear retailer.</p>
            </div>
            <div class="footer-column">
                <h3>Contact</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 ABC Street, Hoan Kiem District, Hanoi</p>
                <p><i class="fas fa-phone"></i> 0123.456.789</p>
                <p><i class="fas fa-envelope"></i> info@shoestore.com</p>
            </div>
            <div class="footer-column">
                <h3>Customer Support</h3>
                <p><a href="#" style="color: #adb5bd; text-decoration: none;">Shopping Guide</a></p>
                <p><a href="#" style="color: #adb5bd; text-decoration: none;">Return Policy</a></p>
            </div>
        </div>
        <div class="copyright">
            &copy; 2023 Shoe Store. All rights reserved.
        </div>
    </footer>
</body>
</html>