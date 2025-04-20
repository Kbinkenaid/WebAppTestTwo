<?php
// Database connection parameters
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "vulnerable_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(50),
    role VARCHAR(20) DEFAULT 'user',
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Create posts table
$sql = "CREATE TABLE IF NOT EXISTS posts (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table posts created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Create comments table
$sql = "CREATE TABLE IF NOT EXISTS comments (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT(6) UNSIGNED,
    user_id INT(6) UNSIGNED,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table comments created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Create files table for LFI demonstration
$sql = "CREATE TABLE IF NOT EXISTS files (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    user_id INT(6) UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table files created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Clear existing data with proper order to respect foreign keys
$sql = "SET FOREIGN_KEY_CHECKS = 0";
$conn->query($sql);

$sql = "TRUNCATE TABLE comments";
$conn->query($sql);
$sql = "TRUNCATE TABLE posts";
$conn->query($sql);
$sql = "TRUNCATE TABLE files";
$conn->query($sql);
$sql = "TRUNCATE TABLE users";
$conn->query($sql);

$sql = "SET FOREIGN_KEY_CHECKS = 1";
$conn->query($sql);

// Insert user 1 (admin)
$sql = "INSERT INTO users (id, username, password, email, role)
        VALUES (1, 'khalifa', 'khalifarit', 'khalifa@example.com', 'admin')";
if ($conn->query($sql) === TRUE) {
    echo "Admin user (khalifa) created successfully<br>";
} else {
    echo "Error creating admin user: " . $conn->error . "<br>";
}

// Insert user 2
$sql = "INSERT INTO users (id, username, password, email, role)
        VALUES (2, 'sufyan', 'sufyanrit', 'sufyan@example.com', 'user')";
if ($conn->query($sql) === TRUE) {
    echo "User 2 (sufyan) created successfully<br>";
} else {
    echo "Error creating user 2: " . $conn->error . "<br>";
}

// Insert user 3
$sql = "INSERT INTO users (id, username, password, email, role)
        VALUES (3, 'mohammad', '1234', 'mohammad@example.com', 'user')";
if ($conn->query($sql) === TRUE) {
    echo "User 3 (mohammad) created successfully<br>";
} else {
    echo "Error creating user 3: " . $conn->error . "<br>";
}

// Insert user 4 (default for SQL injection)
$sql = "INSERT INTO users (id, username, password, email, role)
        VALUES (4, 'random random', 'randompass', 'random@example.com', 'user')";
if ($conn->query($sql) === TRUE) {
    echo "User 4 (random random) created successfully<br>";
} else {
    echo "Error creating user 4: " . $conn->error . "<br>";
}

// Insert sample blog posts
$sql = "INSERT INTO posts (user_id, title, content) 
        VALUES (1, 'Understanding Zero-Day Exploits', 'A zero-day exploit is a cyber attack that occurs on the same day a weakness is discovered in software. At that point, it\'s exploited before a fix becomes available from its creator.')";
if ($conn->query($sql) === TRUE) {
    echo "Sample post 1 created successfully<br>";
} else {
    echo "Error creating sample post 1: " . $conn->error . "<br>";
}

$sql = "INSERT INTO posts (user_id, title, content) 
        VALUES (1, 'The Rise of Ransomware Attacks', 'Ransomware attacks continue to pose a significant threat to organizations worldwide. These attacks encrypt valuable data and demand payment for its release.')";
if ($conn->query($sql) === TRUE) {
    echo "Sample post 2 created successfully<br>";
} else {
    echo "Error creating sample post 2: " . $conn->error . "<br>";
}

$sql = "INSERT INTO posts (user_id, title, content) 
        VALUES (2, 'Multi-Factor Authentication: Your First Line of Defense', 'Multi-Factor Authentication (MFA) has become an essential security measure in today\'s digital landscape. By requiring multiple forms of verification, MFA significantly reduces the risk of unauthorized access even if passwords are compromised.')";
if ($conn->query($sql) === TRUE) {
    echo "Sample post 3 created successfully<br>";
} else {
    echo "Error creating sample post 3: " . $conn->error . "<br>";
}

// Insert sample files for LFI demonstration
$sql = "INSERT INTO files (filename, description, user_id) 
        VALUES ('report1.txt', 'Security Report 1', 1)";
if ($conn->query($sql) === TRUE) {
    echo "Sample file 1 created successfully<br>";
} else {
    echo "Error creating sample file 1: " . $conn->error . "<br>";
}

$sql = "INSERT INTO files (filename, description, user_id) 
        VALUES ('report2.txt', 'Security Report 2', 1)";
if ($conn->query($sql) === TRUE) {
    echo "Sample file 2 created successfully<br>";
} else {
    echo "Error creating sample file 2: " . $conn->error . "<br>";
}

$sql = "INSERT INTO files (filename, description, user_id) 
        VALUES ('private_notes.txt', 'Private Notes', 2)";
if ($conn->query($sql) === TRUE) {
    echo "Sample file 3 created successfully<br>";
} else {
    echo "Error creating sample file 3: " . $conn->error . "<br>";
}

// Create physical files
$file1 = fopen("report1.txt", "w");
fwrite($file1, "This is a public security report that anyone can access.\nIt contains non-sensitive information about security practices.");
fclose($file1);

$file2 = fopen("report2.txt", "w");
fwrite($file2, "This is another public security report.\nIt discusses common cybersecurity threats and mitigation strategies.");
fclose($file2);

$file3 = fopen("private_notes.txt", "w");
fwrite($file3, "CONFIDENTIAL: These are private notes that should only be accessible to user ID 2.\nThey contain sensitive information about security vulnerabilities in the system.");
fclose($file3);

// Create a secret file that shouldn't be accessible
$secret = fopen("secret.txt", "w");
fwrite($secret, "HIGHLY CONFIDENTIAL: This file contains server credentials and should never be accessible to users.\nDatabase password: supersecretpassword123\nAPI Key: ak_live_123456789abcdef");
fclose($secret);

echo "<p>Database setup completed successfully!</p>";
echo "<p><a href='index.php'>Go to the homepage</a></p>";

$conn->close();
?>