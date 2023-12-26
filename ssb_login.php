<?php
include('connection.php');

function base64UrlEncode($data)
{
    $urlSafeData = strtr(base64_encode($data), '+/', '-_');
    return rtrim($urlSafeData, '=');
}

function createJWT($payload, $key)
{
    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    $headerEncoded = base64UrlEncode(json_encode($header));
    $payloadEncoded = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $key, true);
    $signatureEncoded = base64UrlEncode($signature);

    return "$headerEncoded.$payloadEncoded.$signatureEncoded";
}

function verifyJWT($token, $key)
{
    $token_parts = explode('.', $token);

    if (count($token_parts) !== 3) {
        return false;
    }

    $header = json_decode(base64_decode(strtr($token_parts[0], '-_', '+/')), true);
    $payload = json_decode(base64_decode(strtr($token_parts[1], '-_', '+/')), true);
    $signature = base64UrlEncode(hash_hmac('sha256', "$token_parts[0].$token_parts[1]", $key, true));

    if ($signature !== $token_parts[2]) {
        return false;
    }

    if (isset($payload['expire']) && $payload['expire'] < time()) {
        return false;
    }

    return $payload;
}

$key = "passkey"; // Change this to a strong, secret key

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Change this to your preferred password hashing mechanism

    $query = "SELECT * FROM ssbaide_users WHERE Email_ID = '$email' AND Password = '$password'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $payload = [
            'S_NO' => $user['S_NO'],
            'Staff_ID' => $user['Staff_ID'],
            'Staff_Name' => $user['Staff_Name'],
            'Department' => $user['Department'],
            'email' => $user['Email_ID'],
            'Password' => $user['Password'],
            'fullname' => $user['Fullname'],
            'designation' => $user['Designation'],
            'expire' => time() + 3660,
            'Class_ID' => $user['Class_ID'], // Token expiration time (adjust as needed)
        ];

        $jwt = createJWT($payload, $key);

        setcookie('session_id', $jwt, time() + 3660, '/');

        header('Location:ClassRoom/myClass.php');
        exit;
    } else {
        //echo "Login failed. Please check your email and password.";
    }
} else {
    //echo "Please enter both email and password.";
}

// If you have an existing session, you can verify it like this:
    if (isset($_COOKIE['session_id'])) {
        
         //   $key = "passkey"; 
        $decoded_payload = verifyJWT($_COOKIE['session_id'], $key);
   //     echo 'Decoded payload is: <pre>';
     //   print_r($decoded_payload);
       // echo '</pre>';

        if ($decoded_payload) {
            // Session is valid, you can use $decoded_payload
            $user_id = $decoded_payload['S_NO'];
            $user_email = $decoded_payload['email'];
    
            // Print decoded information
            //echo "User ID: $user_id<br>";
            //echo "User Email: $user_email<br>";
       // header('Location:ClassRoom/myClass.php');
       // exit;
    } else {
        // Invalid session, take appropriate action (e.g., redirect to login)
        header('Location:ssb_login.php');
        exit;
    }
}
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no" >
    <link rel="stylesheet" type="text/css" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>SSBAIDE</title>
</head>  
<body>
    
<div class="cont">
    <div class="row px-3" style="width: 421; height: 478.31px; padding: 10px; ">
        <div class="col-lg-12 col-xl card flex-row mx-auto px-0" >
            <div class="img-left d-none d-md-flex"></div>
 
            <div class="card-body" >
                <h3 class="title text-center mt-4 " style="font-family: 'poppins'sans-serif;">Login to SSBAIDE</h3>
                <form class="form-box px-3" onsubmit="return validate();" method="POST" action="ssb_login.php">
            <div class="pos" style="position: relative; top: 25px;">
                    <div class="form-input" style= "width: 325px; ">
                        <input type="Email" name="email" class="form-control" id="email" placeholder="Email Address" >
                    </div>
                    <div class="form-input" >
                        <input type="password"  class="form-control" id="password" name="password" placeholder="Password"  >
                    </div>
                    <div class="for" >
                        <a href="OTP/email.php" id="for" style="text-decoration: none; position: relative; top: -10px; left: 13px;"> Forget Password?</a>
                    </div>
                <div class="mb-3">
                    <div class="custom-control custom-checkbox" style="position: relative; top: 13px;">
                        
                    <p class="text-danger text-center" id="error" ></p>
                    <input type="checkbox" class="custom-control-input " id="cb1" name="">
                        
                        <label class="custom-control-label" for="cb1">Remember Me</label>
                        
                    </div>
                </div>
            </div>
                <div class="mb-3 d-flex" style="position: relative; left: 67px; top: 35px;">
                    <input type="submit"  id="Login" class="btn btn-danger" style="width: 170px; position:relative; top: 20px; padding: 10px; font-family: 'poppins'sans-serif;" value="Login">
             
                </div>

                </form>

            </div>

        </div>

    </div>
</div>
<script>
           
        function validate(){
           
            let email_error=false;
            let password_error=false;
            
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Regular expression for password validation (minimum 8 characters, at least one uppercase letter, one lowercase letter, and one number)
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

            if (!emailRegex.test(email)) {
                email_error=true;
                
            }

            // Validate password
            if (password.length<6) {
                password_error=true;
            }
            if(email_error || password_error){
             
               document.getElementById('error').innerText='Invalid Email or Password';
               return false;
            }

            return true;
            
        }
        //let decodedCookie = decodeURIComponent(document.cookie);
        //console.log("Stored Cookie:", decodedCookie);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>