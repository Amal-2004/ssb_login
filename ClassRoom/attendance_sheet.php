<?php
include("connection.php");

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

$key = "passkey";

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

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
            'Class_ID' => $user['Class_ID'],
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Class Table</title>
    <link rel="stylesheet" href="attendance_sheet.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#" style="font-weight:bold;">&nbsp;&nbsp; SSB CLASS ROOM</a>
    </nav>
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 style="text-align: center;">Hour Information</h4>
                    <form id="addForm">
                        <p id="head">ICT tool used</p>
                        <input type="text" id="ICT" class="form-control" required>
                        <p id="head">Topic</p>
                        <input type="text" id="topic" class="form-control" required>
                        <p id="head">Type of Activity</p>
                        <input type="text" id="activity" class="form-control" required>
                        <div style="text-align: center; margin-top: 20px;">
                            <button type="button" class="btn btn-success" onclick="saveModalData()">Next</button>
                            <button type="button" class="btn btn-danger" onclick="cancel()" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <h2>III BCA</h2>
    <table class="table table-bordered text-center" id="tbl" style="display: none;">
        <thead class="thead">
            <tr>
                <th>Register Number</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="attendanceTableBody">
            <!-- Table content will be dynamically added -->
        </tbody>
    </table>
    <button id="save" class="btn btn-primary">Save</button>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="attendance_sheet.js"></script>

    <!-- Include fetch.php -->
    <script src="fetch_data.php"></script>

</body>

</html>
