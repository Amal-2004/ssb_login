<?php
include("connection.php");

$sql = "CREATE TABLE ssb_classlist (
    S_No INT(6)  ,
    Class_ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    Class_Name VARCHAR(30) NOT NULL,
    Department VARCHAR(30) NOT NULL,
    Advisor VARCHAR(50) NOT NULL
) AUTO_INCREMENT=1001";

if ($con->query($sql)) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $con->error;
}

$con->close();
?>
