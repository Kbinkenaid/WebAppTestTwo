<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Process add to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    
    // VULNERABLE: No validation of item_id
    
    // Add item to cart
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]++;
    } else {
        $_SESSION['cart'][$item_id] = 1;
    }
    
    // Redirect back to dashboard
    header("Location: dashboard.php?added=1");
    exit();
} else {
    // Invalid request
    header("Location: dashboard.php");
    exit();
}
?>