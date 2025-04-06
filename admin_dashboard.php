<?php
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Shoe Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Sử dụng CSS từ trang chính của bạn */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 15px 0;
        }
        
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .admin-main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .admin-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .admin-card h3 {
            color: #343a40;
            border-bottom: 2px solid #ff6b6b;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="logo">
                <img src="Screenshot 2025-03-05 072826.png" alt="Shoe Store Logo" style="height: 40px;">
            </div>
            <div style="color: white;">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)
            </div>
            <div>
                <a href="index.php" style="color: white; margin-right: 15px;">View Site</a>
                <a href="logout.php" style="color: white;">Logout</a>
            </div>
        </nav>
    </header>
    
    <main class="admin-main">
        <div class="admin-card">
            <h3>Quick Actions</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                <a href="add_product.php" style="background: #ff6b6b; color: white; padding: 10px; border-radius: 5px; text-align: center; text-decoration: none;">
                    <i class="fas fa-plus"></i> Add Product
                </a>
                <a href="manage_products.php" style="background: #343a40; color: white; padding: 10px; border-radius: 5px; text-align: center; text-decoration: none;">
                    <i class="fas fa-shoe-prints"></i> Manage Products
                </a>
                <a href="manage_users.php" style="background: #28a745; color: white; padding: 10px; border-radius: 5px; text-align: center; text-decoration: none;">
                    <i class="fas fa-users"></i> Manage Users
                </a>
                <a href="manage_orders.php" style="background: #17a2b8; color: white; padding: 10px; border-radius: 5px; text-align: center; text-decoration: none;">
                    <i class="fas fa-shopping-cart"></i> Manage Orders
                </a>
            </div>
        </div>
        
        <div class="admin-card">
            <h3>Recent Activities</h3>
            <p>Here you can display recent activities or statistics</p>
        </div>
    </main>
</body>
</html>