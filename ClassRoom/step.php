<?php
include "../connection.php";
$sql = "
CREATE TABLE IF NOT EXISTS json_data (
    ID INT(11) AUTO_INCREMENT PRIMARY KEY,
    Class_ID INT(11),
    DATE DATE,
    Computer LONGTEXT,
    BCOM LONGTEXT,
    BAEng LONGTEXT,
    BBA LONGTEXT,
    Maths LONGTEXT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}


$conn->close();
?>
