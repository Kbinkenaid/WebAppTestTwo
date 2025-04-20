<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering to catch any errors
ob_start();

session_start();

// Debug information
echo "<!-- Debug: Session started -->\n";

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
// This allows any authenticated user to view any other user's dashboard by changing the ID in the URL
$user_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

// Debug information
echo "<!-- Debug: User ID: $user_id -->\n";

// Get user information
$sql = "SELECT * FROM users WHERE id = $user_id"; // VULNERABLE: No prepared statement
echo "<!-- Debug: SQL Query: $sql -->\n";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows == 0) {
    die("User not found");
}

$user = $result->fetch_assoc();
echo "<!-- Debug: User found: " . $user['username'] . " -->\n";

// Define grocery items
$groceries = [
    [
        'id' => 1,
        'name' => 'Fresh Milk',
        'price' => 7.50,
        'image' => 'milk.jpg',
        'description' => 'Fresh cow milk, 1 liter'
    ],
    [
        'id' => 2,
        'name' => 'Eggs',
        'price' => 12.75,
        'image' => 'eggs.jpg',
        'description' => 'Farm fresh eggs, pack of 12'
    ],
    [
        'id' => 3,
        'name' => 'Bread',
        'price' => 5.25,
        'image' => 'bread.jpg',
        'description' => 'Freshly baked white bread'
    ],
    [
        'id' => 4,
        'name' => 'Bottled Water',
        'price' => 3.00,
        'image' => 'water.jpg',
        'description' => 'Mineral water, 1.5 liter'
    ],
    [
        'id' => 5,
        'name' => 'Chicken',
        'price' => 22.50,
        'image' => 'chicken.jpg',
        'description' => 'Fresh chicken, 1kg'
    ],
    [
        'id' => 6,
        'name' => 'Rice',
        'price' => 18.75,
        'image' => 'rice.jpg',
        'description' => 'Basmati rice, 2kg'
    ],
    [
        'id' => 7,
        'name' => 'Apples',
        'price' => 9.50,
        'image' => 'apples.jpg',
        'description' => 'Red apples, 1kg'
    ],
    [
        'id' => 8,
        'name' => 'Bananas',
        'price' => 6.25,
        'image' => 'bananas.jpg',
        'description' => 'Fresh bananas, 1kg'
    ],
    [
        'id' => 9,
        'name' => 'Tomatoes',
        'price' => 8.00,
        'image' => 'tomatoes.jpg',
        'description' => 'Fresh tomatoes, 1kg'
    ],
    [
        'id' => 10,
        'name' => 'Potatoes',
        'price' => 7.25,
        'image' => 'potatoes.jpg',
        'description' => 'Fresh potatoes, 1kg'
    ],
    [
        'id' => 11,
        'name' => 'Orange Juice',
        'price' => 10.50,
        'image' => 'juice.jpg',
        'description' => 'Fresh orange juice, 1 liter'
    ],
    [
        'id' => 12,
        'name' => 'Cheese',
        'price' => 15.75,
        'image' => 'cheese.jpg',
        'description' => 'Cheddar cheese, 500g'
    ]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Groceries - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .welcome-banner {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 8px;
            text-align: center;
        }
        .grocery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        .grocery-item {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .grocery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .grocery-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 1rem;
            background-color: #f1f1f1;
        }
        .grocery-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        .grocery-price {
            font-size: 1.1rem;
            color: var(--accent-color);
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .grocery-description {
            color: #666;
            margin-bottom: 1rem;
        }
        .add-to-cart {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .add-to-cart:hover {
            background-color: var(--accent-color);
        }
        .user-info {
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .nav-tabs {
            display: flex;
            margin-bottom: 2rem;
            border-bottom: 2px solid #ddd;
        }
        .nav-tab {
            padding: 1rem 2rem;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            font-weight: bold;
            color: var(--primary-color);
        }
        .nav-tab.active {
            border-bottom: 2px solid var(--secondary-color);
            color: var(--secondary-color);
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
        <div class="welcome-banner">
            <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
            <p>Browse our fresh groceries and place your order today.</p>
        </div>

        <div class="user-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Account Type:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        </div>

        <div class="nav-tabs">
            <button class="nav-tab active">All Items</button>
            <button class="nav-tab">Dairy & Eggs</button>
            <button class="nav-tab">Fruits & Vegetables</button>
            <button class="nav-tab">Beverages</button>
        </div>

        <div class="grocery-grid">
            <?php foreach ($groceries as $item): ?>
                <div class="grocery-item">
                    <div class="grocery-image" style="background-image: url('images/<?php echo $item['image']; ?>');"></div>
                    <h3 class="grocery-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p class="grocery-price">AED <?php echo number_format($item['price'], 2); ?></p>
                    <p class="grocery-description"><?php echo htmlspecialchars($item['description']); ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" class="add-to-cart">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Fresh Groceries. All rights reserved.</p>
    </footer>

<?php
// End output buffering and display any errors
$output = ob_get_clean();
if (error_get_last()) {
    echo "<div style='color:red; background-color:white; padding:10px; margin:10px; border:1px solid red;'>";
    echo "<h2>PHP Error:</h2>";
    echo "<pre>";
    print_r(error_get_last());
    echo "</pre>";
    echo "</div>";
}
echo $output;
?>
</body>
</html>