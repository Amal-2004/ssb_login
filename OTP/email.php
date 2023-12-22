
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email verification</title>
    <link rel="stylesheet" href="email.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <center>
        <div class="content">
        <form action="forgot_password_process.php" method="POST">
    <img id="logo" src="ssbaide.png" alt="">
    <p id="head">Verify your Email ID</p>
    <p id="email">Enter your Email</p>
    <input type="text" id="e-mail" name="email" class="form-control" placeholder="E-Mail">
    <input type="submit" class="btn btn-primary" value="Next">
</form>

</div>
    </center>
    <script>
        function validateEmail() {
            const mail = document.getElementById('e-mail').value;
            if (mail.trim() === '') {
                alert('Fill the Email field');
            }
        }
    </script>
</body>
</html>

