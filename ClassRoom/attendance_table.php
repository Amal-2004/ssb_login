<?php

include("connection.php");

// Get combined data from the request
$combinedData = json_decode(file_get_contents("php://input"), true);

// Extract attendance data and modal data
$attendanceData = $combinedData['attendanceData'];
$modalData = $combinedData['modalData'];

// Extract register_number and status and store in attendance_data
$combinedAttendanceData = [];
foreach ($attendanceData as $registerNumber => $status) {
    $combinedAttendanceData[] = ['register_number' => $registerNumber, 'status' => $status];
}

$attendanceDataJSON = json_encode($combinedAttendanceData);

// Extract other data
if (!empty($modalData) && is_array($modalData) && count($modalData) > 0) {
    $firstModalItem = reset($modalData); // Get the first item from the modalData array

    $ICT = isset($firstModalItem['ICT']) ? $firstModalItem['ICT'] : '';
    $topic = isset($firstModalItem['topic']) ? $firstModalItem['topic'] : '';
    $activity = isset($firstModalItem['activity']) ? $firstModalItem['activity'] : '';
} else {
    // Handle the case where modal data is empty or not an array
    $error = ['error' => 'Modal data is empty or not an array.'];
    echo json_encode($error);
    exit;
}

// Use prepared statements to prevent SQL injection
$stmtCreateTable = $con->prepare("CREATE TABLE IF NOT EXISTS combined_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attendance_data JSON,
    ICT VARCHAR(255),
    topic VARCHAR(255),
    activity VARCHAR(255)
)");

$stmtCreateTable->execute();
$stmtCreateTable->close();

$stmtInsertData = $con->prepare("INSERT INTO combined_table (attendance_data, ICT, topic, activity) VALUES (?, ?, ?, ?)");
$stmtInsertData->bind_param("ssss", $attendanceDataJSON, $ICT, $topic, $activity);

if ($stmtInsertData->execute() !== TRUE) {
    // Handle the case where there's an error in executing the SQL statement
    $error = ['error' => 'Error inserting data: ' . $stmtInsertData->error];
    echo json_encode($error);
    $stmtInsertData->close();
    exit;
}

$stmtInsertData->close();

// Report success
$message = ['message' => 'Data inserted successfully'];
echo json_encode($message);

$con->close();

?>
