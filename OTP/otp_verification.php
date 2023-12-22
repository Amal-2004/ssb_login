
<?php
session_start();
include("connection.php");

if (!isset($_SESSION['otp'])) {
   
    header('Location: ssb_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $enteredOTP = $_POST['otp'];

   
    $email = $_SESSION['email'];
    $sql = "SELECT OTP FROM ssbaide_users WHERE Email_ID = '$email'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedOTP = $row['OTP'];

        if ($enteredOTP == $storedOTP) {
            header('Location: changing_password.php');
            exit;
        } else {
            echo "Invalid OTP. Please try again.";
        }
    } else {
        echo "Error retrieving OTP: " . $con->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OTP Authentication</title>
<link rel="stylesheet" href="generate_otp.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <center>
        <div class="content">
            <form action="otp_verification.php" method="POST">
              
                <p id="otp">Enter OTP</p>
                <input type="text" name="otp" id="OTP"  class="form-control" placeholder="OTP">
                <input type="submit" class="btn btn-primary" value="Verify">
            </form>
        </div>
    </center>
    <script>
        function validateOTP() {
            const otp = document.getElementById('OTP').value;
            if (otp === '') {
                alert('Fill the OTP field');
            }
        }
    </script>
</body>
</html>
