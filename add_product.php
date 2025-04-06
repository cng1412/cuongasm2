
<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Phần còn lại của trang add_product.php
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        
        .main-header {
            width: 100%;
            max-width: 800px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .main-title {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }
        
        .main-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
            border-radius: 2px;
        }
        
        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 30px;
            width: 100%;
            max-width: 800px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .card-title {
            color: var(--secondary-color);
            font-size: 1.8rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .card-title i {
            margin-right: 10px;
            font-size: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--gray-color);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
            background-color: white;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #d1146a;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #3ab7d8;
            transform: translateY(-2px);
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        
        .alert i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background-color: #fee2e2;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
        }
        
        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            width: 100%;
            max-width: 800px;
        }
        
        @media (min-width: 768px) {
            .grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-btn {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            width: 100%;
        }
        
        .file-upload-btn:hover {
            border-color: var(--primary-color);
            background-color: #e9ecef;
        }
        
        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-text {
            color: var(--gray-color);
            font-size: 14px;
            margin-top: 10px;
        }
        
        .file-upload-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <h1 class="main-title">Product Management</h1>
    </header>

    <div class="grid">
        <!-- Add Product Card -->
        <div class="card">
            <h2 class="card-title"><i class="fas fa-plus-circle"></i> Add New Product</h2>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_id" class="form-label">Product ID</label>
                    <input type="text" id="product_id" name="product_id" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" id="product_name" name="product_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="product_price" class="form-label">Price ($)</label>
                    <input type="number" id="product_price" name="product_price" class="form-control" min="0" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" min="0" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Product Image</label>
                    <div class="file-upload">
                        <label class="file-upload-btn">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <span>Click to upload or drag and drop</span>
                            <input type="file" name="product_img" class="file-upload-input" required accept="image/*">
                        </label>
                        <div class="file-upload-text">Supports JPEG, PNG, JPG (Max 5MB)</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="product_description" class="form-label">Description</label>
                    <textarea id="product_description" name="product_description" class="form-control" required></textarea>
                </div>
                
                <button type="submit" name="add_product" class="btn btn-primary btn-block">
                    <i class="fas fa-save"></i> Save Product
                </button>
            </form>
        </div>

        <!-- Delete Product Card -->
        <div class="card">
            <h2 class="card-title"><i class="fas fa-trash-alt"></i> Delete Product</h2>
            
            <?php if (isset($delete_message)): ?>
                <div class="alert <?php echo strpos($delete_message, 'success') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <i class="fas <?php echo strpos($delete_message, 'success') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo $delete_message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="GET">
                <div class="form-group">
                    <label for="delete_id" class="form-label">Product ID to Delete</label>
                    <input type="text" id="delete_id" name="delete_id" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-danger btn-block">
                    <i class="fas fa-trash"></i> Delete Product
                </button>
            </form>
        </div>
    </div>

    <a href="index.php" class="btn btn-success" style="margin-top: 30px; max-width: 800px; width: 100%;">
        <i class="fas fa-home"></i> Return to Homepage
    </a>

    <?php
    // Database connection
    $connect = mysqli_connect('localhost', 'root', '', 'login');
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Handle form submission for adding product
    if (isset($_POST['add_product'])) {
        // Get form data
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $quantity = $_POST['quantity'];
        $product_description = $_POST['product_description'];

        // Check if product ID already exists
        $check_sql = "SELECT * FROM products WHERE product_id = ?";
        $check_stmt = mysqli_prepare($connect, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $product_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error_message = "Product ID already exists. Please use a different ID.";
        } else {
            // Handle image upload
            if (isset($_FILES['product_img'])) {
                $product_img = $_FILES['product_img']['name'];
                $product_img_tmp = $_FILES['product_img']['tmp_name'];
                $upload_dir = "Image/";

                // Create directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Move uploaded file
                $target_file = $upload_dir . basename($product_img);
                if (move_uploaded_file($product_img_tmp, $target_file)) {
                    $success_message = "Image uploaded successfully";
                } else {
                    $error_message = "Failed to upload image";
                }
            }

            // Insert product using prepared statement
            $sql = "INSERT INTO products (product_id, product_name, product_price, quantity, product_img, product_description)
                        VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connect, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssdiss", $product_id, $product_name, $product_price, $quantity, $product_img, $product_description);

                if (mysqli_stmt_execute($stmt)) {
                    $success_message = "Product added successfully!";
                    // Refresh form after successful submission
                    echo "<script>setTimeout(function(){ window.location.href = window.location.href.split('?')[0]; }, 1500);</script>";
                } else {
                    $error_message = "Failed to add product: " . mysqli_error($connect);
                }

                mysqli_stmt_close($stmt);
            } else {
                $error_message = "SQL preparation error";
            }
        }

        mysqli_stmt_close($check_stmt);
    }

    // Handle product deletion
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        
        // Get image filename for deletion
        $sql = "SELECT product_img FROM products WHERE product_id = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "s", $delete_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $product_img);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        
        // Delete product from database
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "s", $delete_id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Delete image file if exists
            if (!empty($product_img) && file_exists("Image/" . $product_img)) {
                unlink("Image/" . $product_img);
            }
            $delete_message = "Product deleted successfully!";
            // Refresh after deletion
            echo "<script>setTimeout(function(){ window.location.href = window.location.href.split('?')[0]; }, 1500);</script>";
        } else {
            $delete_message = "Error deleting product: " . mysqli_error($connect);
        }
        
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($connect);
    ?>
</body>
</html>