<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "vulnerable_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
$success = '';

// VULNERABLE TO IDOR: Using user_id from GET parameter without proper authorization
// This allows any authenticated user to view or edit any other user's profile
$profile_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

// Get user information
$sql = "SELECT * FROM users WHERE id = $profile_id"; // VULNERABLE: No prepared statement
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("User not found");
}

$user = $result->fetch_assoc();

// Process profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // VULNERABLE: No input validation or sanitization
    // VULNERABLE: No authorization check to ensure user can only update their own profile
    
    $sql = "UPDATE users SET email = '$email'";
    
    // Only update password if provided
    if (!empty($password)) {
        $sql .= ", password = '$password'";
    }
    
    $sql .= " WHERE id = $profile_id";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Profile updated successfully!";
        
        // Refresh user data
        $result = $conn->query("SELECT * FROM users WHERE id = $profile_id");
        $user = $result->fetch_assoc();
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}

// Get user's orders (for demonstration)
$sql = "SELECT * FROM orders WHERE user_id = $profile_id ORDER BY order_date DESC LIMIT 5";
$orders_result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Groceries - User Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .profile-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }
        .profile-sidebar {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .profile-content {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #f1f1f1;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--primary-color);
        }
        .profile-name {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        .profile-role {
            text-align: center;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }
        .profile-nav {
            list-style: none;
            padding: 0;
        }
        .profile-nav li {
            margin-bottom: 0.5rem;
            padding: 0;
        }
        .profile-nav a {
            display: block;
            padding: 0.75rem;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .profile-nav a:hover, .profile-nav a.active {
            background-color: #f1f1f1;
            color: var(--secondary-color);
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
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: var(--accent-color);
        }
        .recent-orders {
            margin-top: 2rem;
        }
        .order-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-id {
            font-weight: bold;
            color: var(--primary-color);
        }
        .order-date {
            color: #666;
            font-size: 0.9rem;
        }
        .order-total {
            color: var(--accent-color);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Fresh Groceries</h1>
        <nav>
            <a href="dashboard.php?id=<?php echo $profile_id; ?>">Home</a>
            <a href="orders.php?id=<?php echo $profile_id; ?>">My Orders</a>
            <a href="profile.php?id=<?php echo $profile_id; ?>">My Profile</a>
            <a href="view_file.php?id=<?php echo $profile_id; ?>">View Files</a>
            <a href="blog_new.php?id=<?php echo $profile_id; ?>">Community Blog</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>User Profile</h2>
        
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
        
        <div class="profile-container">
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
                <h3 class="profile-name"><?php echo htmlspecialchars($user['username']); ?></h3>
                <p class="profile-role"><?php echo htmlspecialchars($user['role']); ?></p>
                
                <ul class="profile-nav">
                    <li><a href="#" class="active">Profile Information</a></li>
                    <li><a href="orders.php?user_id=<?php echo $user['id']; ?>">Order History</a></li>
                    <li><a href="payment_info.php?user_id=<?php echo $user['id']; ?>">Payment Information</a></li>
                    <li><a href="addresses.php?user_id=<?php echo $user['id']; ?>">Delivery Addresses</a></li>
                </ul>
            </div>
            
            <div class="profile-content">
                <h3>Profile Information</h3>
                <p>Update your personal information below.</p>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $profile_id); ?>">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        <small>Username cannot be changed</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                    </div>
                    
                    <button type="submit" class="submit-btn">Update Profile</button>
                </form>
                
                <div class="recent-orders">
                    <h3>Recent Orders</h3>
                    
                    <?php if ($orders_result && $orders_result->num_rows > 0): ?>
                        <?php while ($order = $orders_result->fetch_assoc()): ?>
                            <div class="order-item">
                                <p class="order-id">Order #<?php echo $order['id']; ?></p>
                                <p class="order-date"><?php echo $order['order_date']; ?></p>
                                <p class="order-total">AED <?php echo number_format($order['total'], 2); ?></p>
                                <a href="order_details.php?id=<?php echo $order['id']; ?>">View Details</a>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No recent orders found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Fresh Groceries. All rights reserved.</p>
    </footer>
</body>
</html>