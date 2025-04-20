#!/bin/bash

echo "Starting setup for Vulnerable Web Application..."

# Create necessary directories
mkdir -p images

# Create empty log file for brute force demonstration
touch login_attempts.log
chmod 777 login_attempts.log

# Create orders table in database
php -r '
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vulnerable_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    total DECIMAL(10,2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT \"pending\",
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table orders created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$conn->close();
'

# Run database setup
echo "Setting up database..."
php setup_database.php

# Create placeholder images for grocery items
echo "Creating placeholder images..."
for item in milk eggs bread water chicken rice apples bananas tomatoes potatoes juice cheese
do
    touch "images/${item}.jpg"
    chmod 777 "images/${item}.jpg"
done

echo "Setup completed successfully!"
echo "You can now access the vulnerable web application at: http://localhost/WebAppTest/"
echo "The attacker website is available at: http://localhost/WebAppTest/attackersite/"
echo ""
echo "Default credentials:"
echo "Admin: username=admin, password=admin123"
echo "User: username=user, password=password123"
echo ""
echo "Vulnerabilities implemented:"
echo "1. SQL Injection: Login page (index.php)"
echo "2. Persistent XSS: Blog page (blog_new.php)"
echo "3. Local File Inclusion: File viewer (view_file.php)"
echo "4. Brute Force Attack: Login page (index.php)"
echo "5. Insecure Direct Object References: Profile page (profile.php)"