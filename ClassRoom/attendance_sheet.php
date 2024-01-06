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

// Check if the class_id is set in the cookie
if (isset($_COOKIE['session_id'])) {
    // Assuming the session_id cookie contains the JWT
    $jwt = $_COOKIE['session_id'];

    // Verify the JWT
    $decoded = verifyJWT($jwt, $key);

    // Check if the JWT is valid and has the necessary information
    if ($decoded && isset($decoded['Class_ID'])) {
        //$class_id = $decoded['Class_ID'];
        $class_id=$_POST['class_id'];
        // Fetch students for the given class_id
        $sql = "SELECT * FROM student_list WHERE Class_ID = $class_id";
        $result = $con->query($sql);

        if (!$result) {
            // Handle the case where the SQL query fails
            echo "Error executing SQL query: " . $con->error;
            exit;
        }
    } else {
        // Handle the case where the JWT is invalid or does not contain the required information
        echo "Invalid JWT or missing Class ID.";
        exit;
    }
} else {
    echo "Class ID not set in the cookie.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Attendance Sheet</title>
    <link rel="stylesheet" href="attendance_sheet.css">
</head>

<body>
<nav class="navbar navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#" style="font-weight:bold;">&nbsp;&nbsp; SSB CLASS ROOM</a>
    </nav>
    
    <?php if ($result && $result->num_rows > 0) : ?>
        <div class="container mt-5">
            <h4 class="text-center mb-4">Attendance Sheet</h4>
           <?php //echo $_POST['class_id']; ?>
            <form id="attendanceForm" method="post" action="insert_json.php" autocomplete="off">
                <input type="hidden" name="save" value="1">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <input type="hidden" name="ICT" value="<?php echo htmlspecialchars($_POST['ICT']); ?>">
                <input type="hidden" name="topic" value="<?php echo htmlspecialchars($_POST['topic']); ?>">
                <input type="hidden" name="activity" value="<?php echo htmlspecialchars($_POST['activity']); ?>">

                <table class='table table-bordered text-center'>
                    <thead class='thead'>
                        <tr>
                            <th>Register Number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id='attendanceTableBody'>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['REG_NO'] . "</td>";
                            echo "<td>";
                            echo "<label><input type='radio' name='status[" . $row['REG_NO'] . "]' value='present'> Present</label>";
                            echo "<label><input type='radio' name='status[" . $row['REG_NO'] . "]' value='absent'> Absent</label>";
                            echo "<label><input type='radio' name='status[" . $row['REG_NO'] . "]' value='leave'> Leave</label>";
                            echo "<label><input type='radio' name='status[" . $row['REG_NO'] . "]' value='od'> OD</label>";

                            echo "<label><input type='radio' name='status[" . $row['REG_NO'] . "]' value='tl'> TL</label>";
                            echo "<label><input type='radio' name='status[" . $row['REG_NO'] . "]' value='fenaulty'> Fenaulty</label>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    <?php else : ?>
        <div class="container mt-5">
            <p class="text-center">No students found for the given class.</p>
        </div>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cancel() {
            window.location.href = 'myClass.php';
        }
    </script>
</body>

</html>
