<?php
require_once 'email_config.php';

$emailService = new EmailNotification();

$result = $emailService->sendEmail(
    "youremail@gmail.com",  // Change to YOUR email
    "Test Email from Lost & Found",
    "<h1>✅ Success!</h1><p>Gmail SMTP is working!</p>"
);

echo $result ? "✅ Email sent! Check your inbox." : "❌ Failed. Check error log.";
?>