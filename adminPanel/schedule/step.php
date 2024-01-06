<?php
include("connection.php");

$sql = "CREATE TABLE ssb_schedule (
    S_NO INT,
    Class_ID INT(6),
    Day_Order VARCHAR(30),
    Sub_Name VARCHAR(50),
    Hr VARCHAR(50),
    Staff_ID varchar(40), 
    Sub_Code varchar(50)
    )";


if ($con->query($sql)) {
    echo "Table  created successfully";
} else {
    echo "Error creating table: " . $con->error;
}

$con->close();
?>
