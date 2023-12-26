<?php

// Include your database connection logic
include("connection.php");

if (isset($_COOKIE['session_id'])) {
    // Decode and verify the JWT token to get the payload
    $key = "passkey"; // Use the same key you used for creating JWT
    $token = $_COOKIE['session_id'];
    
    function base64UrlDecode($data)
    {
        $urlUnsafeData = strtr($data, '-_', '+/');
        $paddedData = str_pad($urlUnsafeData, strlen($data) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($paddedData);
    }

    $token_parts = explode('.', $token);

    if (count($token_parts) === 3) {
        $payload = json_decode(base64UrlDecode($token_parts[1]), true);
        
        if (isset($payload['Class_ID'])) {
            $classID = $payload['Class_ID'];

            // Assuming you have a table named 'student_list'
            $sql = "SELECT * FROM student_list WHERE Class_ID = '$classID'";
            $result = $con->query($sql);

            $studentData = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $studentData[] = $row;
                }
            }

            // Return the data as JSON
            header('Content-Type: application/json');
            echo json_encode($studentData);
        } else {
            // Handle the case when Class_ID is not present in the JWT payload
            echo "Class ID is not present in the JWT payload.";
        }
    } else {
        // Handle the case when the JWT token is not properly formatted
        echo "Invalid JWT token format.";
    }
} else {
    // Handle the case when session_id cookie is not set
    echo "Session ID cookie is not set.";
}

// Close the database connection
$con->close();

?>
