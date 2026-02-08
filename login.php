<?php
session_start();
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password!";
    } else {
        // Check user in database
        $stmt = $conn->prepare("SELECT id, username, password, full_name FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                
                // Set cookie (30 days)
                setcookie('jobportal_user', $user['id'], time() + (86400 * 30), "/");
                
                // Update last login
                $update_stmt = $conn->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
                $update_stmt->bind_param("i", $user['id']);
                $update_stmt->execute();
                $update_stmt->close();
                
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Invalid username or password!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini Job Portal</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Use the same styling as signup.php but adjust for login */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
            pointer-events: none;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            border-top: 5px solid #4cc9f0;
            transition: transform 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .login-header h2 {
            color: #1e3c72;
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 1rem;
        }
        
        .login-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #4cc9f0, #4361ee);
            border-radius: 2px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1e3c72;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e7ff;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8faff;
            color: #333;
        }
        
        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            background: white;
            outline: none;
        }
        
        .form-control:hover {
            border-color: #a5b4fc;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
            z-index: -1;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.4);
            background: linear-gradient(135deg, #3a0ca3 0%, #7209b7 100%);
        }
        
        .error-message {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5d5d 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .signup-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e7ff;
        }
        
        .signup-link a {
            color: #4361ee;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }
        
        .signup-link a:hover {
            color: #3a0ca3;
            text-decoration: underline;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                padding: 30px 25px;
                margin: 20px;
            }
            
            .login-header h2 {
                font-size: 1.8rem;
            }
            
            .form-control {
                padding: 14px 18px;
            }
            
            .btn-login {
                padding: 15px;
            }
        }
        
        /* Floating animation for background */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .floating-bg {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(76, 201, 240, 0.1) 0%, rgba(67, 97, 238, 0.05) 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
        }
        
        .floating-bg:nth-child(2) {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(58, 12, 163, 0.1) 0%, transparent 70%);
            animation-delay: 2s;
            top: 10%;
            right: 10%;
        }
        
        .floating-bg:nth-child(3) {
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(114, 9, 183, 0.1) 0%, transparent 70%);
            animation-delay: 4s;
            bottom: 20%;
            left: 10%;
        }
    </style>
</head>
<body>
    <!-- Floating background elements -->
    <div class="floating-bg" style="top: 20%; left: 15%;"></div>
    <div class="floating-bg" style="top: 60%; right: 20%;"></div>
    <div class="floating-bg" style="bottom: 10%; left: 25%;"></div>
    
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Sign in to access your job portal</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" class="form-control" 
                       placeholder="Enter your username or email" required autofocus
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="Enter your password" required>
            </div>
            
            <button type="submit" name="login" class="btn-login">Sign In</button>
        </form>
        
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </div>
        
        <div class="login-footer">
            <p>&copy; <?php echo date('Y'); ?> Nobel Gaming Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>