<?php
include("connection.php");

$sql = "CREATE TABLE ssbaide_users(
    S_NO INT(6) UNSIGNED,
    Staff_ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Staff_Name VARCHAR(30),
    Department VARCHAR(30),
    Email_ID VARCHAR(50),
    Password Varchar(50),
    Fullname varchar(50),
    Designation VARCHAR(50),
    isactive boolean,
    OTP INT(6),
    OTP_Time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
    )AUTO_INCREMENT=1001";
    


if ($con->query($sql)) {
    echo "Table  created successfully";
} else {
    echo "Error creating table: " . $con->error;
}

$con->close();
?>
