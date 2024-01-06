<?php
session_start();
include("connection.php");
// Check if the user is authenticated


// Mark the session as authenticated to allow access to this page
$_SESSION['authenticated'] = true;function base64UrlEncode($data)
{
    $urlSafeData = strtr(base64_encode($data), '+/', '-_');
    return rtrim($urlSafeData, '=');
}

function createJWT($attendanceData, $modelData, $key)
{
    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    $headerEncoded = base64UrlEncode(json_encode($header));

    $combinedPayload = [
        'attendance' => $attendanceData,
        'model' => $modelData,
    ];

    $payloadEncoded = base64UrlEncode(json_encode($combinedPayload));

    $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $key, true);
    $signatureEncoded = base64UrlEncode($signature);

    return "$headerEncoded.$payloadEncoded.$signatureEncoded";
}

$key = "passkey";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM ssbaide_users WHERE Email_ID = '$email' AND Password = '$password'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $class_id = $user['Class_ID'];
        setcookie('class_id', $class_id, time() + 3660, '/');

        $scheduleQuery = "SELECT Class_ID, Day_Order, Sub_Name, Hr, Staff_ID, SUB_Code FROM ssb_schedule WHERE Staff_ID = '{$user['Staff_ID']}'";
        $scheduleResult = $con->query($scheduleQuery);

        $scheduleData = [];
        if ($scheduleResult->num_rows > 0) {
            while ($row = $scheduleResult->fetch_assoc()) {
                $scheduleData[] = $row;
            }
        }
        $hrData = [];
        foreach ($scheduleData as $item) {
            $hrData[] = $item['Hr'];
        }
        setcookie('hr_data', json_encode($hrData), time() + 3660, '/');

        $payload = [
            'Class_ID' => $user['Class_ID'],
            'Staff_ID' => $user['Staff_ID'],
            'schedule' => $scheduleData,
        ];
        // Additional data from the model.php form
        $modelData = [
            'ICT' => $_POST['ICT'],
            'topic' => $_POST['topic'],
            'activity' => $_POST['activity'],
            // Add other model-related data
        ];
        var_dump($modelData);

        // Combine both sets of data into a single payload
        $combinedPayload = array_merge($payload, ['model' => $modelData]);

        $jwt = createJWT($combinedPayload, $key);

        setcookie('session_id', $jwt, time() + 3660, '/');
        header('Location: attendance_sheet.php');
        exit;
    } else {
        // Handle login failure
        // echo "Login failed. Please check your email and password.";
    }
} else {
    // Handle the case where the login form is not submitted
    // echo "Please enter both email and password.";
}
// Your HTML form for collecting additional data in model.php
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
    <div class="modal-body" id="modalBody">
        <h4 style="text-align: center;">Hour Information</h4>
        <form method="post" action="attendance_sheet.php">
<!-- Inside your <form> tag in model.php -->
<input type="hidden" name="class_id" value="<?php echo $_GET['class_id']; ?>">

    
    <label for="ICT">ICT tool used:</label>
    <input type="text" id="ICT" name="ICT" class="form-control" required />

    <label for="topic">Topic:</label>
    <input type="text" id="topic" name="topic" class="form-control" required />

    <label for="activity">Type of Activity:</label>
    <input type="text" id="activity" name="activity" class="form-control" required />

    <!-- Other form fields -->

    <div style="text-align: center; margin-top: 20px;">
        <button type="submit" name="submit" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-danger" onclick="cancel()">Cancel</button>
    </div>
</form>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show the modal initially
            $('#modalBody').show();
        })

        function cancel() {
            // Redirect to 'myClass.php'
            window.location.href = 'myClass.php';
        }
    </script>
</body>
</html>
