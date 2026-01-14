<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class EmailNotification {
    private $smtp_host = "smtp.gmail.com";
    private $smtp_port = 587;
    private $smtp_username = "kentomaruroujikan@gmail.com";
    private $smtp_password = "qhtvfvqfhjejuzbw";
    private $from_name = "Lost & Found System";
    
    public function sendEmail($to_email, $subject, $message) {
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = $this->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtp_username;
            $mail->Password = $this->smtp_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->smtp_port;
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            $mail->setFrom($this->smtp_username, $this->from_name);
            $mail->addAddress($to_email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            
            $mail->send();
            error_log("✅ Email sent successfully to: " . $to_email);
            return true;
        } catch (Exception $e) {
            error_log("❌ Email error: {$mail->ErrorInfo}");
            return false;
        }
    }
    
    public function notifyItemReturned($item_name, $to_email, $owner_name = "Owner") {
        $subject = "Good News! Your Lost Item Has Been Returned";
        
        $message = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9;'>
                <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center;'>
                    <h1>🎉 Great News!</h1>
                </div>
                <div style='padding: 30px;'>
                    <p>Hello,</p>
                    <p>Your lost item has been marked as <strong>RETURNED</strong>!</p>
                    <div style='background: white; padding: 20px; margin: 20px 0; border-left: 4px solid #28a745;'>
                        <h3>📦 Item: {$item_name}</h3>
                        <p><strong>Status:</strong> <span style='color: #28a745;'>Returned</span></p>
                    </div>
                    <p>Please visit the Lost & Found office to collect your item.</p>
                    <p>Thank you!</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->sendEmail($to_email, $subject, $message);
    }
    
    public function notifyMatchFound($item_name, $to_email, $match_type, $match_details) {
        $subject = "Match Found for Your " . ucfirst($match_type) . " Item!";
        
        $message = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9;'>
                <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center;'>
                    <h1>🔍 Match Found!</h1>
                </div>
                <div style='padding: 30px;'>
                    <p>Hello,</p>
                    <p>We found a potential match for your {$match_type} item!</p>
                    <div style='background: white; padding: 20px; margin: 20px 0; border-left: 4px solid #ffc107;'>
                        <h3>📦 Your Item: {$item_name}</h3>
                    </div>
                    <div style='background: #e7f3ff; padding: 15px; margin: 15px 0;'>
                        <h4>Matching Item:</h4>
                        <p><strong>Name:</strong> {$match_details['name']}</p>
                        <p><strong>Description:</strong> {$match_details['description']}</p>
                        <p><strong>Location:</strong> {$match_details['location']}</p>
                        <p><strong>Date:</strong> {$match_details['date']}</p>
                        <p><strong>Contact:</strong> {$match_details['contact_email']}</p>
                    </div>
                    <p><strong>Next Steps:</strong></p>
                    <ul>
                        <li>Contact the person above</li>
                        <li>Visit Lost & Found office to verify</li>
                    </ul>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->sendEmail($to_email, $subject, $message);
    }
}
?>