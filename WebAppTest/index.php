<?php
session_start();
$error = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "vulnerable_db";

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // VULNERABLE TO SQL INJECTION: No prepared statements, direct concatenation
    // Simple SQL injection in username field
    $sql = "SELECT * FROM users WHERE username = '$username' ORDER BY id DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect to dashboard with user ID in URL (vulnerable to IDOR)
        header("Location: dashboard.php?id=" . $user['id']);
        exit();
    } else {
        $error = "Invalid username or password";
        
        // Log failed login attempts (for demonstration of brute force vulnerability)
        $ip = $_SERVER['REMOTE_ADDR'];
        $timestamp = date('Y-m-d H:i:s');
        $log = "Failed login attempt: Username: $username, IP: $ip, Time: $timestamp\n";
        file_put_contents('login_attempts.log', $log, FILE_APPEND);
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cybersecurity Information Hub - Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: var(--secondary-color);
            outline: none;
        }
        .error-message {
            color: var(--accent-color);
            margin-bottom: 1rem;
            font-weight: bold;
        }
        .submit-btn {
            background-color: var(--secondary-color);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 4px;
            width: 100%;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: var(--accent-color);
        }
        .register-link {
            text-align: center;
            margin-top: 1rem;
        }
        .register-link a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Cybersecurity Information Hub</h1>
        <nav>
            <a href="about.php">About</a>
            <a href="register.php">Register</a>
        </nav>
    </header>

    <main>
        <div class="login-container">
            <h2>Login</h2>
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="submit-btn">Login</button>
            </form>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Cybersecurity Information Hub. All rights reserved.</p>
    </footer>
</body>
</html>