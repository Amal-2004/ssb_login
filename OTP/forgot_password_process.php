<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $email = $_POST['email'];

  
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
    } else {
        
        $checkEmailQuery = "SELECT * FROM ssbaide_users WHERE Email_ID = '$email'";
        $result = $con->query($checkEmailQuery);

        if ($result->num_rows > 0) {
           
            $otp = rand(100000, 999999);
            $sql = "UPDATE ssbaide_users SET OTP = '$otp' WHERE Email_ID = '$email'";
            $updateResult = $con->query($sql);

            if ($updateResult) {
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['otp'] = $otp;

                require 'D:\xamp loc\htdocs\Ssbaide\OTP\PHPMailer-master/src/PHPMailer.php';
                require 'D:\xamp loc\htdocs\Ssbaide\OTP\PHPMailer-master/src/SMTP.php';
                require 'D:\xamp loc\htdocs\Ssbaide\OTP\PHPMailer-master/src/Exception.php';

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP host
                $mail->Port = 587; // Replace with your SMTP port
                $mail->SMTPSecure = 'tls'; // Replace with your encryption type
                $mail->SMTPAuth = true;
                $mail->Username = 'amalsutherson2004@gmail.com'; // Replace with your SMTP username
                $mail->Password = 'nuwm rqvc tebr sqbz'; // Replace with your SMTP password

                $mail->setFrom('amalsutherson2004@gmail.com', 'Amal Sutherson');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset OTP';
                $mail->Body = "Your OTP is: $otp";

                try {
                    $mail->send();
                    header('Location: otp_verification.php');
                    exit;
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
               
                header('Location: error_page.php');
                exit;
            }
        } else {
           
            echo "Email not found";
        }
    }
}

$con->close();
?>
