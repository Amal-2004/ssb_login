<?php
include("connection.php");

function base64UrlEncode($data)
{
    $urlSafeData = strtr(base64_encode($data), '+/', '-_');
    return rtrim($urlSafeData, '=');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save']) && $_POST['save'] == 1) {
  

    $jwt = $_COOKIE['session_id'];

    
    $decoded = verifyJWT($jwt, 'passkey');

    
    if ($decoded && isset($decoded['Class_ID'])) {
        
        $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : null;

        
        if ($class_id >= 1 && $class_id <= 6) {
            $department = 'Computer';
        } elseif ($class_id >= 7 && $class_id <= 11) {
            $department = 'BCOM';
        } elseif ($class_id >= 12 && $class_id <= 14) {
            $department = 'BAEng';
        } elseif ($class_id >= 15 && $class_id <= 17) {
            $department = 'BBA';
        } elseif ($class_id == 18) {
            $department = 'Maths';
        } else {
           
            echo "Invalid Class ID.";
            exit;
        }

       
        $ICT = isset($_POST['ICT']) ? htmlspecialchars($_POST['ICT']) : '';
        $topic = isset($_POST['topic']) ? htmlspecialchars($_POST['topic']) : '';
        $activity = isset($_POST['activity']) ? htmlspecialchars($_POST['activity']) : '';

     
        $sql = "SELECT * FROM student_list WHERE Class_ID = $class_id";
        $result = $con->query($sql);

        if ($result === false) {
          
            echo "Error executing the database query.";
            exit;
        }

        if ($result->num_rows > 0) {
            $attendanceData = [];

            while ($row = $result->fetch_assoc()) {
                $regNo = $row['REG_NO'];
                $status = isset($_POST['status'][$regNo]) ? $_POST['status'][$regNo] : '';

                
                 if ($status !== 'present') {
                    $attendanceData[] = [
                    'regNo' => $regNo,
                    'status' => $status,
                    ];
                    }
            }
            $scheduleQuery = "SELECT Hr, Day_Order, Sub_Name FROM ssb_schedule WHERE Class_ID = $class_id";
            $scheduleResult = $con->query($scheduleQuery);

            $scheduleData = [];
            if ($scheduleResult->num_rows > 0) {
                while ($scheduleRow = $scheduleResult->fetch_assoc()) {
                    $hrname = $scheduleRow['Hr'];
                    $absentList = $attendanceData; 
                    $hrData = [
                        'Class_ID' => $class_id, 
                        'hrname' => $hrname,
                        'dayOrder' => $scheduleRow['Day_Order'],
                        'absentList' => $absentList,
                        'ICT' => $ICT,
                        'topic' => $topic,
                        'activity' => $activity,
                    ];
                    
                    $scheduleData[] = $hrData;
                }
            }

            $combinedJson = json_encode($scheduleData);

            if ($combinedJson === false) {

                echo "Error encoding data to JSON.";
                exit;
            }
            $checkRecordQuery = "SELECT * FROM json_data WHERE DATE = CURDATE()";
            $checkRecordResult = $con->query($checkRecordQuery);

            if ($checkRecordResult->num_rows > 0) {
                $existingRow = $checkRecordResult->fetch_assoc();
    $existingJson = json_decode($existingRow[$department], true);

    foreach ($scheduleData as $newData) {
        $existingJson[] = $newData;
    }

    $updatedJson = json_encode($existingJson);

  
    $updateQuery = "UPDATE json_data SET $department = '$updatedJson' WHERE DATE = CURDATE()";

                if ($con->query($updateQuery) === false) {

                    echo "Error updating data in the database: " . $con->error;
                    exit;
                }
            } else {
                $insertQuery = "INSERT INTO json_data (DATE, $department) VALUES (CURDATE(), '$combinedJson')";
                if ($con->query($insertQuery) === false) {

                    echo "Error inserting data into the database: " . $con->error;
                    exit;
                }
            }

            header('Location: success.php');
            exit;
        } else {
            echo "No students found for the given class ID.";
        }
    } else {
        echo "Invalid JWT or missing Class ID.";
    }
}
?>
