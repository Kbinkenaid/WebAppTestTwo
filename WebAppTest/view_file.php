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
$user_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

// Get user information
$sql = "SELECT * FROM users WHERE id = $user_id"; // VULNERABLE: No prepared statement
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("User not found");
}

$user = $result->fetch_assoc();

// Get all files
$sql = "SELECT f.*, u.username FROM files f JOIN users u ON f.user_id = u.id ORDER BY f.created_at DESC";
$files_result = $conn->query($sql);

// Get file content if requested
$file_content = '';
$file_name = '';

// VULNERABLE TO LFI: Directly including user input without proper validation
if (isset($_GET['file'])) {
    $file_name = $_GET['file'];
    
    // This is vulnerable to LFI as it allows reading any file on the system
    // An attacker could use path traversal like "../../etc/passwd" to read sensitive files
    $file_content = file_get_contents($file_name);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Groceries - View Files</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .files-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }
        .files-list {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .file-content {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .file-item {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
        }
        .file-item:last-child {
            border-bottom: none;
        }
        .file-item:hover {
            background-color: #f8f9fa;
        }
        .file-item a {
            color: var(--primary-color);
            text-decoration: none;
            display: block;
        }
        .file-item a:hover {
            color: var(--secondary-color);
        }
        .file-meta {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }
        .content-display {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            white-space: pre-wrap;
            font-family: monospace;
            max-height: 500px;
            overflow-y: auto;
        }
        .no-file-selected {
            color: #666;
            font-style: italic;
            text-align: center;
            padding: 2rem;
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
        <h2>File Viewer</h2>
        <p>View security reports and documentation files.</p>
        
        <div class="files-container">
            <div class="files-list">
                <h3>Available Files</h3>
                
                <?php if ($files_result && $files_result->num_rows > 0): ?>
                    <?php while ($file = $files_result->fetch_assoc()): ?>
                        <div class="file-item">
                            <a href="view_file.php?file=<?php echo $file['filename']; ?>">
                                <?php echo htmlspecialchars($file['filename']); ?>
                            </a>
                            <div class="file-meta">
                                Uploaded by <?php echo htmlspecialchars($file['username']); ?>
                                <br>
                                <?php echo htmlspecialchars($file['description']); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No files available.</p>
                <?php endif; ?>
                
                <!-- Additional links for demonstration -->
                <div class="file-item">
                    <a href="view_file.php?file=report1.txt">Security Report 1</a>
                    <div class="file-meta">
                        Public security report
                    </div>
                </div>
                <div class="file-item">
                    <a href="view_file.php?file=report2.txt">Security Report 2</a>
                    <div class="file-meta">
                        Public security report
                    </div>
                </div>
                <div class="file-item">
                    <a href="view_file.php?file=private_notes.txt">Private Notes</a>
                    <div class="file-meta">
                        Private notes (should be restricted)
                    </div>
                </div>
            </div>
            
            <div class="file-content">
                <h3><?php echo $file_name ? htmlspecialchars($file_name) : 'File Content'; ?></h3>
                
                <?php if ($file_content): ?>
                    <div class="content-display">
                        <?php echo htmlspecialchars($file_content); ?>
                    </div>
                <?php else: ?>
                    <div class="no-file-selected">
                        <p>Select a file from the list to view its content.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Fresh Groceries. All rights reserved.</p>
    </footer>
</body>
</html>