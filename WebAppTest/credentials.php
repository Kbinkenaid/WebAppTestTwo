<?php
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        $message = "Credentials sent ➜ Server";
        $_SESSION['last_username'] = $username;
    } else {
        $message = "Please fill in all fields";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credentials ➜ Server</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .credentials-container {
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
        .message {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            background-color: #f8f9fa;
            border-left: 4px solid var(--secondary-color);
            font-weight: bold;
        }
        .arrow {
            color: var(--secondary-color);
            font-size: 1.2em;
            margin: 0 0.3em;
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
    </style>
</head>
<body>
    <header>
        <h1>Credentials <span class="arrow">➜</span> Server</h1>
        <nav>
            <a href="cybersecurity.html">Home</a>
            <a href="blog.php">Blog</a>
        </nav>
    </header>

    <main>
        <div class="credentials-container">
            <h2>Enter Your Credentials</h2>
            <?php if ($message): ?>
                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Enter your username">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter your password">
                </div>
                <button type="submit" class="submit-btn">Send to Server</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Cybersecurity Information Hub. All rights reserved.</p>
    </footer>
</body>
</html>
