<?php

include("connection.php");


$sql = "CREATE TABLE student_list (
    S_NO INT AUTO_INCREMENT PRIMARY KEY,
    REG_NO VARCHAR(20) NOT NULL,
    Class_ID INT NOT NULL,
    Department VARCHAR(50) NOT NULL,
    Section VARCHAR(10) NOT NULL,
    Gender VARCHAR(10) NOT NULL,
    DOB DATE NOT NULL,
    Student_Name VARCHAR(100) NOT NULL,
    Location VARCHAR(100) NOT NULL,
    Phone VARCHAR(20) NOT NULL,
    Annual_Income DECIMAL(10, 2) NOT NULL
)";

if ($con->query($sql) === TRUE) {
    echo "Table student_list created successfully";
} else {
    echo "Error creating table: " . $con->error;
}


$con->close();

?>
