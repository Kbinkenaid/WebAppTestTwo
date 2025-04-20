<?php
$blogs = [
    [
        'title' => 'Understanding Zero-Day Exploits',
        'date' => '2024-02-04',
        'author' => 'Security Expert',
        'content' => 'A zero-day exploit is a cyber attack that occurs on the same day a weakness is discovered in software. At that point, it\'s exploited before a fix becomes available from its creator. Zero-day attacks are a severe threat to individuals and organizations alike, as there are no patches or updates available to prevent the exploitation of the vulnerability.

        Common signs of zero-day exploits include:
        • Unusual system behavior
        • Unexpected network traffic
        • System crashes
        • Strange file modifications

        To protect against zero-day exploits:
        1. Keep all software updated
        2. Use robust security solutions
        3. Implement network monitoring
        4. Follow security best practices'
    ],
    [
        'title' => 'The Rise of Ransomware Attacks',
        'date' => '2024-02-03',
        'author' => 'Cyber Analyst',
        'content' => 'Ransomware attacks continue to pose a significant threat to organizations worldwide. These attacks encrypt valuable data and demand payment for its release. Recent trends show an increase in sophisticated ransomware tactics, including double extortion schemes.

        Key prevention strategies:
        • Regular data backups
        • Employee security training
        • Email filtering systems
        • Network segmentation
        
        If attacked:
        1. Isolate infected systems
        2. Contact cybersecurity experts
        3. Report to authorities
        4. Restore from clean backups'
    ],
    [
        'title' => 'Multi-Factor Authentication: Your First Line of Defense',
        'date' => '2024-02-02',
        'author' => 'Security Researcher',
        'content' => 'Multi-Factor Authentication (MFA) has become an essential security measure in today\'s digital landscape. By requiring multiple forms of verification, MFA significantly reduces the risk of unauthorized access even if passwords are compromised.

        Types of MFA:
        • Something you know (password)
        • Something you have (phone)
        • Something you are (biometrics)
        
        Benefits:
        1. Enhanced security
        2. Reduced fraud risk
        3. Compliance requirements
        4. User accountability'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cybersecurity Blog</title>
    <link rel="stylesheet" href="styles.css">
    <style>
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
            white-space: pre-line;
        }
    </style>
</head>
<body>
    <header>
        <h1>Cybersecurity Blog</h1>
        <nav>
            <a href="cybersecurity.html">Home</a>
            <a href="credentials.php">Credentials ➜ Server</a>
        </nav>
    </header>

    <main>
        <?php foreach ($blogs as $blog): ?>
            <article class="blog-post">
                <h2 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h2>
                <div class="blog-meta">
                    Posted on <?php echo htmlspecialchars($blog['date']); ?> by <?php echo htmlspecialchars($blog['author']); ?>
                </div>
                <div class="blog-content">
                    <?php echo htmlspecialchars($blog['content']); ?>
                </div>
            </article>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>&copy; 2024 Cybersecurity Information Hub. All rights reserved.</p>
    </footer>
</body>
</html>
