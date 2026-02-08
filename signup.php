<?php
session_start();
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields must be filled!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Username or email already exists!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
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
    <title>Sign Up - Mini Job Portal</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-y: auto; /* Fixed: Enable vertical scrolling */
        }
        
        body::before {
            content: '';
            position: fixed; /* Changed from absolute to fixed */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
            pointer-events: none;
            z-index: -1;
        }
        
        .signup-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            margin: 50px auto; /* Center with auto margins */
            border-top: 5px solid #4cc9f0;
            transition: transform 0.3s ease;
        }
        
        .signup-container:hover {
            transform: translateY(-5px);
        }
        
        .signup-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .signup-header h2 {
            color: #1e3c72;
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        
        .signup-header p {
            color: #666;
            font-size: 1rem;
        }
        
        .signup-header::after {
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
            margin-bottom: 20px;
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
            padding: 14px 20px;
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
        
        .password-strength {
            height: 4px;
            background: #e0e7ff;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            background: #ff6b6b;
            transition: all 0.3s ease;
        }
        
        .btn-signup {
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
            margin-top: 20px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-signup::before {
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
        
        .btn-signup:hover::before {
            left: 100%;
        }
        
        .btn-signup:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.4);
            background: linear-gradient(135deg, #3a0ca3 0%, #7209b7 100%);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            animation: fadeIn 0.5s ease;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #38b000 0%, #2d9100 100%);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5d5d 100%);
            color: white;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e7ff;
            color: #666;
            font-size: 0.95rem;
        }
        
        .login-link a {
            color: #4361ee;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #3a0ca3;
            text-decoration: underline;
        }
        
        .signup-footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Floating animation for background */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .floating-bg {
            position: fixed; /* Changed from absolute to fixed */
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(76, 201, 240, 0.1) 0%, rgba(67, 97, 238, 0.05) 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
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
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .signup-container {
                padding: 30px 25px;
                margin: 30px 20px;
            }
            
            .signup-header h2 {
                font-size: 1.8rem;
            }
            
            .form-control {
                padding: 12px 18px;
            }
            
            .btn-signup {
                padding: 14px;
            }
            
            body {
                padding: 10px;
            }
        }
        
        @media (max-height: 700px) {
            .signup-container {
                margin: 20px auto;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating background elements -->
    <div class="floating-bg" style="top: 20%; left: 15%;"></div>
    <div class="floating-bg" style="top: 60%; right: 20%;"></div>
    <div class="floating-bg" style="bottom: 20%; left: 10%;"></div>
    
    <div class="signup-container">
        <div class="signup-header">
            <h2>Create Account</h2>
            <p>Join our job portal community</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" 
                       placeholder="Choose a username" required 
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" 
                       placeholder="Enter your email" required 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="Create a password (min 6 characters)" required 
                       onkeyup="checkPasswordStrength()">
                <div class="password-strength">
                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                </div>
            </div>
            
            <button type="submit" name="signup" class="btn-signup">Create Account</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Sign In</a>
        </div>
        
        <div class="signup-footer">
            <p>&copy; <?php echo date('Y'); ?> Nobel Gaming Company. All rights reserved.</p>
        </div>
    </div>

    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            
            if (password.length >= 6) strength += 25;
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            
            strengthBar.style.width = strength + '%';
            
            // Color based on strength
            if (strength <= 25) {
                strengthBar.style.background = '#ff6b6b';
            } else if (strength <= 50) {
                strengthBar.style.background = '#ffa726';
            } else if (strength <= 75) {
                strengthBar.style.background = '#42a5f5';
            } else {
                strengthBar.style.background = '#4caf50';
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>