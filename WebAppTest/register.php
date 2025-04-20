<?php
session_start();
$error = '';
$success = '';

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

// Process registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $username = $_POST['username'];
    $password = $_POST['password']; // Intentionally not hashed for vulnerability
    $email = $_POST['email'];
    
    // VULNERABLE: No input validation or sanitization
    
    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $error = "Username already exists";
    } else {
        // VULNERABLE: SQL Injection possible here
        $sql = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', 'user')";
        
        if ($conn->query($sql) === TRUE) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Groceries - Register</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .register-container {
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
        .success-message {
            color: #2ecc71;
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
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
        .login-link a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Fresh Groceries</h1>
        <nav>
            <a href="index.php">Login</a>
            <a href="about.php">About</a>
        </nav>
    </header>

    <main>
        <div class="register-container">
            <h2>Create an Account</h2>
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="submit-btn">Register</button>
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="index.php">Login here</a></p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Fresh Groceries. All rights reserved.</p>
    </footer>
</body>
</html>