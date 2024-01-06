<?php
include "connection.php";

if (isset($_POST['submit'])) {
    $allowedExtensions = array('csv');
    $filename = $_FILES['file']['name'];
    $fileExt = pathinfo($filename, PATHINFO_EXTENSION);

    if (!empty($_FILES['file']['name']) && in_array(strtolower($fileExt), $allowedExtensions)) {

        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            if ($csvFile) {
                fgetcsv($csvFile); 
                while (($line = fgetcsv($csvFile)) !== FALSE) {
                    
                    $S_NO = $line[0];
                    $Class_ID = $line[1];
                    $Day_Order = $line[2];
                    $Sub_Name = $line[3];
                    $Hr = $line[4];
                    $Staff_ID = $line[5];
                    $Sub_Code = $line[6]; 

                    $sql = "SELECT S_NO FROM ssb_schedule WHERE Hr = '{$Hr}' ";
                    $res = $con->query($sql);

                    if ($res->num_rows > 0) {
                        $s = "UPDATE ssb_schedule SET S_NO='$S_NO', Class_ID='$Class_ID', Day_Order='$Day_Order', Sub_Name='$Sub_Name', Staff_ID='$Staff_ID', Sub_Code='$Sub_Code' WHERE Hr='$Hr'";
                        $con->query($s);
                    } else {
                        $s = "INSERT INTO ssb_schedule (S_NO, Class_ID, Day_Order, Sub_Name, Hr, Staff_ID, Sub_Code) VALUES ('$S_NO', '$Class_ID', '$Day_Order', '$Sub_Name', '$Hr', '$Staff_ID', '$Sub_Code')";
                        $con->query($s);
                    }
                }
                fclose($csvFile); 
                $q = '?status=success';
            } else {
                $q = "?status=invalid_file";
            }
        }
    }
}
?>
