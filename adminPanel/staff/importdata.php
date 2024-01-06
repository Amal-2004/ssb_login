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
                    $Staff_Name = $line[1];
                    $Department = $line[2];
                    $Email_ID = $line[3];
                    $Fullname = $line[4];
                    $Designation = $line[5];
                    $isactive = $line[6];
                  // Add this line for the Class_ID

                    $sql = "SELECT S_NO FROM ssbaide_users WHERE Email_ID = '{$Email_ID}' ";
                    $res = $con->query($sql);

                    if ($res->num_rows > 0) {
                        $s = "UPDATE ssbaide_users SET S_NO='$S_NO', Staff_Name='$Staff_Name', Department='$Department', Email_ID='$Email_ID',  Fullname='$Fullname', Designation='$Designation', isactive='$isactive'WHERE Email_ID='$Email_ID'";
                        $con->query($s);
                    } else {
                        $s = "INSERT INTO ssbaide_users (S_NO, Staff_Name, Department, Email_ID,  Fullname, Designation, isactive) VALUES ('$S_NO', '$Staff_Name', '$Department', '$Email_ID',  '$Fullname', '$Designation', '$isactive')";
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
