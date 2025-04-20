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

// VULNERABLE TO IDOR: Using user_id from GET parameter without proper authorization
// This allows any authenticated user to view any other user's orders
$user_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

// Get user information
$sql = "SELECT * FROM users WHERE id = $user_id"; // VULNERABLE: No prepared statement
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("User not found");
}

$user = $result->fetch_assoc();

// Get user's orders
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
$orders_result = $conn->query($sql);

// Sample order data (for demonstration)
$sample_orders = [
    [
        'id' => 1001,
        'date' => '2024-03-25 14:30:22',
        'total' => 125.50,
        'status' => 'Delivered',
        'items' => [
            ['name' => 'Fresh Milk', 'quantity' => 2, 'price' => 7.50],
            ['name' => 'Eggs', 'quantity' => 1, 'price' => 12.75],
            ['name' => 'Bread', 'quantity' => 1, 'price' => 5.25],
            ['name' => 'Chicken', 'quantity' => 2, 'price' => 22.50],
            ['name' => 'Rice', 'quantity' => 1, 'price' => 18.75],
            ['name' => 'Apples', 'quantity' => 2, 'price' => 9.50],
            ['name' => 'Orange Juice', 'quantity' => 1, 'price' => 10.50]
        ]
    ],
    [
        'id' => 1002,
        'date' => '2024-03-20 09:15:43',
        'total' => 78.25,
        'status' => 'Delivered',
        'items' => [
            ['name' => 'Bottled Water', 'quantity' => 3, 'price' => 3.00],
            ['name' => 'Bananas', 'quantity' => 2, 'price' => 6.25],
            ['name' => 'Tomatoes', 'quantity' => 1, 'price' => 8.00],
            ['name' => 'Potatoes', 'quantity' => 1, 'price' => 7.25],
            ['name' => 'Cheese', 'quantity' => 2, 'price' => 15.75]
        ]
    ],
    [
        'id' => 1003,
        'date' => '2024-03-15 16:45:10',
        'total' => 95.75,
        'status' => 'Delivered',
        'items' => [
            ['name' => 'Fresh Milk', 'quantity' => 1, 'price' => 7.50],
            ['name' => 'Chicken', 'quantity' => 1, 'price' => 22.50],
            ['name' => 'Rice', 'quantity' => 2, 'price' => 18.75],
            ['name' => 'Apples', 'quantity' => 1, 'price' => 9.50],
            ['name' => 'Orange Juice', 'quantity' => 1, 'price' => 10.50],
            ['name' => 'Cheese', 'quantity' => 1, 'price' => 15.75]
        ]
    ]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Groceries - My Orders</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .orders-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .order {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
            margin-bottom: 1rem;
        }
        .order-id {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        .order-date {
            color: #666;
            font-size: 0.9rem;
        }
        .order-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .order-status.delivered {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .order-status.processing {
            background-color: #fff8e1;
            color: #f57f17;
        }
        .order-status.cancelled {
            background-color: #ffebee;
            color: #c62828;
        }
        .order-items {
            margin-bottom: 1rem;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .item-details {
            display: flex;
            align-items: center;
        }
        .item-quantity {
            background-color: #f1f1f1;
            color: #666;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            margin-right: 1rem;
            font-size: 0.9rem;
        }
        .item-name {
            font-weight: bold;
        }
        .item-price {
            color: var(--accent-color);
            font-weight: bold;
        }
        .order-total {
            text-align: right;
            padding-top: 1rem;
            border-top: 1px solid #eee;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .order-total span {
            color: var(--accent-color);
        }
        .no-orders {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .user-viewing {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--secondary-color);
        }
    </style>
</head>
<body>
    <header>
        <h1>Fresh Groceries</h1>
        <nav>
            <a href="dashboard.php?id=<?php echo $user_id; ?>">Home</a>
            <a href="orders.php?id=<?php echo $user_id; ?>">My Orders</a>
            <a href="profile.php?id=<?php echo $user_id; ?>">My Profile</a>
            <a href="view_file.php?id=<?php echo $user_id; ?>">View Files</a>
            <a href="blog_new.php?id=<?php echo $user_id; ?>">Community Blog</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="orders-container">
            <h2>Order History</h2>
            
            <?php if ($user_id != $_SESSION['user_id']): ?>
                <div class="user-viewing">
                    <p><strong>Note:</strong> You are viewing orders for user: <?php echo htmlspecialchars($user['username']); ?></p>
                    <a href="orders.php?id=<?php echo $_SESSION['user_id']; ?>">Return to my orders</a>
                </div>
            <?php endif; ?>
            
            <?php if ($orders_result && $orders_result->num_rows > 0): ?>
                <!-- Display actual orders from database if available -->
                <?php while ($order = $orders_result->fetch_assoc()): ?>
                    <div class="order">
                        <div class="order-header">
                            <div class="order-id">Order #<?php echo $order['id']; ?></div>
                            <div class="order-date"><?php echo date('F j, Y g:i A', strtotime($order['order_date'])); ?></div>
                            <div class="order-status <?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></div>
                        </div>
                        
                        <!-- Order items would be fetched from a separate table in a real application -->
                        <div class="order-total">
                            Total: <span>AED <?php echo number_format($order['total'], 2); ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Display sample orders for demonstration -->
                <?php foreach ($sample_orders as $order): ?>
                    <div class="order">
                        <div class="order-header">
                            <div class="order-id">Order #<?php echo $order['id']; ?></div>
                            <div class="order-date"><?php echo date('F j, Y g:i A', strtotime($order['date'])); ?></div>
                            <div class="order-status <?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></div>
                        </div>
                        
                        <div class="order-items">
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="order-item">
                                    <div class="item-details">
                                        <div class="item-quantity">x<?php echo $item['quantity']; ?></div>
                                        <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    </div>
                                    <div class="item-price">AED <?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-total">
                            Total: <span>AED <?php echo number_format($order['total'], 2); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if ((!$orders_result || $orders_result->num_rows == 0) && empty($sample_orders)): ?>
                <div class="no-orders">
                    <h3>No orders found</h3>
                    <p>You haven't placed any orders yet.</p>
                    <a href="dashboard.php?id=<?php echo $user_id; ?>" class="button">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Fresh Groceries. All rights reserved.</p>
    </footer>
</body>
</html>