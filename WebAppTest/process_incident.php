<?php
header('Content-Type: text/html; charset=utf-8');

// Get the form data
$reporter_name = $_POST['reporter_name'] ?? '';
$reporter_email = $_POST['reporter_email'] ?? '';
$incident_type = $_POST['incident_type'] ?? '';
$urgency = $_POST['urgency'] ?? '';
$incident_date = $_POST['incident_date'] ?? '';
$description = $_POST['description'] ?? '';
$affected_systems = $_POST['affected_systems'] ?? '';

// Basic input validation
if (empty($reporter_name) || empty($reporter_email) || empty($incident_type) || 
    empty($urgency) || empty($incident_date) || empty($description)) {
    die('Required fields must be filled out');
}

// Sanitize inputs
$reporter_name = htmlspecialchars($reporter_name);
$reporter_email = filter_var($reporter_email, FILTER_SANITIZE_EMAIL);
$incident_type = htmlspecialchars($incident_type);
$urgency = htmlspecialchars($urgency);
$incident_date = htmlspecialchars($incident_date);
$description = htmlspecialchars($description);
$affected_systems = htmlspecialchars($affected_systems);

// Display the submitted data
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report Submitted</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Incident Report Submitted</h1>
        <nav>
            <a href="index.html">Back to Home</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Thank you for reporting the security incident!</h2>
            <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px;">
                <h3>Report Details:</h3>
                <p><strong>Reporter Name:</strong> <?php echo $reporter_name; ?></p>
                <p><strong>Reporter Email:</strong> <?php echo $reporter_email; ?></p>
                <p><strong>Incident Type:</strong> <?php echo $incident_type; ?></p>
                <p><strong>Urgency Level:</strong> <span style="color: <?php echo $urgency === 'critical' ? '#e74c3c' : 'inherit'; ?>"><?php echo $urgency; ?></span></p>
                <p><strong>Date of Incident:</strong> <?php echo $incident_date; ?></p>
                <p><strong>Description:</strong></p>
                <pre style="white-space: pre-wrap; background: #f8f9fa; padding: 10px; border-radius: 4px;"><?php echo $description; ?></pre>
                
                <?php if (!empty($affected_systems)): ?>
                <p><strong>Affected Systems/Assets:</strong></p>
                <pre style="white-space: pre-wrap; background: #f8f9fa; padding: 10px; border-radius: 4px;"><?php echo $affected_systems; ?></pre>
                <?php endif; ?>

                <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
                    <h3>Next Steps:</h3>
                    <?php if ($urgency === 'critical'): ?>
                        <p style="color: #e74c3c;">Your report has been marked as CRITICAL. Our security team will be notified immediately.</p>
                        <ul>
                            <li>Expected response time: Within 1 hour</li>
                            <li>A security specialist will contact you at the provided email</li>
                            <li>Please monitor your email for urgent communications</li>
                        </ul>
                    <?php elseif ($urgency === 'high'): ?>
                        <p>Your report has been marked as HIGH priority. Our team will review it promptly.</p>
                        <ul>
                            <li>Expected response time: Within 4 hours</li>
                            <li>A team member will contact you for additional information if needed</li>
                        </ul>
                    <?php else: ?>
                        <p>Your report has been received and will be reviewed by our security team.</p>
                        <ul>
                            <li>Expected response time: Within 24-48 hours</li>
                            <li>You will receive a confirmation email with next steps</li>
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
