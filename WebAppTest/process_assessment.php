<?php
header('Content-Type: text/html; charset=utf-8');

// Get the form data
$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? '';
$security_level = $_GET['security_level'] ?? '';
$concerns = $_GET['concerns'] ?? '';

// Basic input validation
if (empty($name) || empty($email) || empty($security_level) || empty($concerns)) {
    die('All fields are required');
}

// Sanitize inputs
$name = htmlspecialchars($name);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$security_level = htmlspecialchars($security_level);
$concerns = htmlspecialchars($concerns);

// Display the submitted data
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Submitted</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Assessment Submitted</h1>
        <nav>
            <a href="index.html">Back to Home</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Thank you for completing the security assessment!</h2>
            <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px;">
                <h3>Submitted Information:</h3>
                <p><strong>Name:</strong> <?php echo $name; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Security Level:</strong> <?php echo $security_level; ?></p>
                <p><strong>Primary Concern:</strong> <?php echo $concerns; ?></p>
                
                <div style="margin-top: 20px;">
                    <h3>Recommendations:</h3>
                    <?php if ($security_level === 'basic'): ?>
                        <p>Consider implementing these additional security measures:</p>
                        <ul>
                            <li>Install a reputable antivirus software</li>
                            <li>Enable two-factor authentication on your accounts</li>
                            <li>Use a password manager</li>
                        </ul>
                    <?php elseif ($security_level === 'medium'): ?>
                        <p>To enhance your security further:</p>
                        <ul>
                            <li>Enable two-factor authentication on all accounts</li>
                            <li>Consider using a VPN service</li>
                            <li>Regularly backup your data</li>
                        </ul>
                    <?php else: ?>
                        <p>Great job on maintaining strong security practices! Remember to:</p>
                        <ul>
                            <li>Regularly update all security software</li>
                            <li>Perform regular security audits</li>
                            <li>Stay informed about new security threats</li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Cybersecurity Information Hub. All rights reserved.</p>
    </footer>
</body>
</html>
