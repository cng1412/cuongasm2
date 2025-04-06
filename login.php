<?php
session_start();

// Database connection
$connect = mysqli_connect('localhost', 'root', '', 'login');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$error_message = '';
// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($connect, $_POST["username"]);
    $password = mysqli_real_escape_string($connect, $_POST["password"]);

    // Sử dụng prepared statement để bảo mật hơn
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Kiểm tra mật khẩu (nên dùng password_verify nếu bạn hash mật khẩu)
        if ($user['password'] === $password) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Lưu role vào session
            
            // Chuyển hướng dựa trên role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}

mysqli_close($connect);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Shoe Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('banner.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #212529;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 0 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.2);
            padding: 40px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        
        .logo {
            margin-bottom: 30px;
        }
        
        .logo img {
            height: 50px;
        }
        
        .login-header h2 {
            font-size: 24px;
            color: #343a40;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .login-header p {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #343a40;
            font-size: 14px;
            font-weight: 500;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .input-with-icon input:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 2px rgba(255,107,107,0.2);
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            background: #ff5252;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,107,0.3);
        }
        
        .login-footer {
            margin-top: 25px;
            color: #6c757d;
            font-size: 14px;
        }
        
        .login-footer a {
            color: #ff6b6b;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .alert-error {
            background-color: #fff5f5;
            color: #ff6b6b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #ffdddd;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="logo">
                <img src="Screenshot 2025-03-05 072826.png" alt="Shoe Store Logo">
            </div>
            
            <div class="login-header">
                <h2>Account Login</h2>
                <p>Please enter your login credentials</p>
            </div>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                
                <div class="login-footer">
                    Don't have an account? <a href="register.php">Create new account</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show/hide password
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this;
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>