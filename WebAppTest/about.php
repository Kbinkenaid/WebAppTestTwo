<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Groceries - About Us</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .about-section {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .about-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 2rem;
        }
        .team-member {
            text-align: center;
        }
        .team-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1rem;
            background-color: #f1f1f1;
        }
        .team-name {
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        .team-role {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Fresh Groceries</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Home</a>
                <a href="orders.php">My Orders</a>
                <a href="profile.php">My Profile</a>
                <a href="view_file.php">View Files</a>
                <a href="blog_new.php">Community Blog</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="index.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h2>About Fresh Groceries</h2>
        
        <section class="about-section">
            <h3>Our Story</h3>
            <div style="background-color: #f1f1f1; height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-bottom: 1.5rem;">
                <p style="font-size: 1.2rem; color: #666;">Store Image Placeholder</p>
            </div>
            <p>Fresh Groceries was founded in 2020 with a simple mission: to deliver the freshest, highest-quality groceries directly to your doorstep. What started as a small operation serving a few neighborhoods has grown into a trusted grocery delivery service across the entire region.</p>
            <p>We partner with local farmers and suppliers to ensure that our products are not only fresh but also support the local economy. Our commitment to quality, convenience, and customer satisfaction has made us the preferred choice for busy families, professionals, and anyone who values their time and health.</p>
        </section>
        
        <section class="about-section">
            <h3>Our Values</h3>
            <ul>
                <li><strong>Freshness:</strong> We source the freshest products and deliver them promptly to maintain quality.</li>
                <li><strong>Sustainability:</strong> We use eco-friendly packaging and support sustainable farming practices.</li>
                <li><strong>Community:</strong> We believe in supporting local farmers and businesses.</li>
                <li><strong>Convenience:</strong> We make grocery shopping effortless with our user-friendly platform and reliable delivery.</li>
                <li><strong>Transparency:</strong> We provide clear information about our products and pricing.</li>
            </ul>
        </section>
        
        <section class="about-section">
            <h3>Our Team</h3>
            <p>Meet the dedicated team behind Fresh Groceries who work tirelessly to bring you the best grocery shopping experience.</p>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="team-photo"></div>
                    <h4 class="team-name">Sarah Johnson</h4>
                    <p class="team-role">Founder & CEO</p>
                </div>
                <div class="team-member">
                    <div class="team-photo"></div>
                    <h4 class="team-name">Michael Chen</h4>
                    <p class="team-role">Operations Manager</p>
                </div>
                <div class="team-member">
                    <div class="team-photo"></div>
                    <h4 class="team-name">Aisha Patel</h4>
                    <p class="team-role">Head of Procurement</p>
                </div>
            </div>
        </section>
        
        <section class="about-section">
            <h3>Contact Us</h3>
            <p>We'd love to hear from you! If you have any questions, feedback, or concerns, please don't hesitate to reach out to us.</p>
            <p><strong>Email:</strong> support@freshgroceries.com</p>
            <p><strong>Phone:</strong> +971 4 123 4567</p>
            <p><strong>Address:</strong> 123 Fresh Street, Dubai, UAE</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Fresh Groceries. All rights reserved.</p>
    </footer>
</body>
</html>