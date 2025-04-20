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
$user_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

// Get user information
$sql = "SELECT * FROM users WHERE id = $user_id"; // VULNERABLE: No prepared statement
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("User not found");
}

$user = $result->fetch_assoc();

// Process new blog post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'post') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    // Use the user ID from the URL, allowing users to post as other users
    $post_user_id = $user_id;
    
    // VULNERABLE: No input sanitization for XSS display, but escape for SQL
    
    // Escape special characters for SQL to prevent SQL errors (but still allow XSS)
    $title_sql = $conn->real_escape_string($title);
    $content_sql = $conn->real_escape_string($content);
    
    $sql = "INSERT INTO posts (user_id, title, content) VALUES ($post_user_id, '$title_sql', '$content_sql')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Blog post published successfully!";
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Process new comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'comment') {
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];
    
    // VULNERABLE: No input sanitization for XSS
    
    $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES ($post_id, $user_id, '$comment')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Comment added successfully!";
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Get all blog posts
$sql = "SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC";
$posts_result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Groceries - Community Blog</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .blog-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .blog-post {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .blog-title {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        .blog-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .blog-content {
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }
        .comment-section {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        .comment {
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .comment-meta {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        .comment-text {
            line-height: 1.6;
        }
        .new-post-form, .comment-form {
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus, .form-group textarea:focus {
            border-color: var(--secondary-color);
            outline: none;
        }
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
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
        <div class="blog-container">
            <h2>Community Blog</h2>
            <p>Share your thoughts, recipes, and grocery tips with our community!</p>
            
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
            
            <div class="new-post-form">
                <h3>Create a New Post</h3>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="action" value="post">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content:</label>
                        <textarea id="content" name="content" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Publish Post</button>
                </form>
            </div>
            
            <h3>Recent Posts</h3>
            
            <?php if ($posts_result && $posts_result->num_rows > 0): ?>
                <?php while ($post = $posts_result->fetch_assoc()): ?>
                    <article class="blog-post">
                        <h2 class="blog-title"><?php echo $post['title']; ?></h2>
                        <div class="blog-meta">
                            Posted by <?php echo $post['username']; ?> on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                        </div>
                        <div class="blog-content">
                            <?php echo $post['content']; ?> <!-- VULNERABLE: No escaping -->
                        </div>
                        
                        <div class="comment-section">
                            <h4>Comments</h4>
                            
                            <?php
                            // Get comments for this post
                            $post_id = $post['id'];
                            $comments_sql = "SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = $post_id ORDER BY c.created_at ASC";
                            $comments_result = $conn->query($comments_sql);
                            
                            if ($comments_result && $comments_result->num_rows > 0):
                                while ($comment = $comments_result->fetch_assoc()):
                            ?>
                                <div class="comment">
                                    <div class="comment-meta">
                                        <?php echo $comment['username']; ?> commented on <?php echo date('F j, Y', strtotime($comment['created_at'])); ?>
                                    </div>
                                    <div class="comment-text">
                                        <?php echo $comment['comment']; ?> <!-- VULNERABLE: No escaping -->
                                    </div>
                                </div>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <p>No comments yet.</p>
                            <?php endif; ?>
                            
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="comment-form">
                                <input type="hidden" name="action" value="comment">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <div class="form-group">
                                    <label for="comment-<?php echo $post['id']; ?>">Add a Comment:</label>
                                    <textarea id="comment-<?php echo $post['id']; ?>" name="comment" required></textarea>
                                </div>
                                <button type="submit" class="submit-btn">Post Comment</button>
                            </form>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No blog posts yet. Be the first to create one!</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Fresh Groceries. All rights reserved.</p>
    </footer>
</body>
</html>