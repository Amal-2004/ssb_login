
<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = md5($_POST['new_password']);
    $confirmPassword = md5($_POST['confirm_password']);

    if ($newPassword === $confirmPassword) {
       
        $email = $_SESSION['email'];
        $sql = "UPDATE ssbaide_users SET Password = '$newPassword' WHERE Email_ID = '$email'";
        $result = $con->query($sql);

        if ($result) {
           
            header('Location:../ssb_login.php');
            exit;
        } else {
            echo "Error updating password: " . $con->error;
        }
    } else {
        echo "Passwords do not match. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="changing_password.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <center>
        <div class="content">
            <form action="changing_password.php" method="POST">
            <img id="logo" src="ssbaide.png" alt="">
                
                <p id="head">Change your Password</p>
                
                <p id="password-head">Password</p>
                <input type="password" name="new_password" id="new_password" class="form-control" required>

                <p id="confirm-password-head">Confirm Password</p>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>

                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </center>
    
     <script>
        function validateForm() {
            const password = document.getElementById('password').value
            const confirmPassword = document.getElementById('confirm-password').value
            if (password === '' || confirmPassword === '') {
                alert('Please fill in all fields.')
                return false
            }
            if (password.length < 8||confirmPassword.length<8) {
                alert('Password must be at least 8 characters long.')
            }
            if (password !== confirmPassword) {
                alert('Password does not match Confirm Password.')
            }
            return true
        }
    </script>
</body>
</html>
