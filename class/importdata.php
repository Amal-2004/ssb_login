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
                    $Class_Name = $line[1];
                    $Department = $line[2];
                    $Advisor = $line[3];
                    
                    

                    $sql = "SELECT S_NO FROM ssb_classlist WHERE Advisor = '{$Advisor}' ";
                    $res = $con->query($sql);

                    if ($res->num_rows > 0) {
                        $s = "UPDATE ssb_classlist SET name='$S_NO', Class_Name='$Class_Name', Department='$Department', Advisor='$Advisor' WHERE Advisor='$Advisor'";
                        $con->query($s);
                    } else {
                        $s = "INSERT INTO ssb_classlist (S_NO, Class_Name,Department, Advisor) VALUES ('$S_NO', '$Class_Name', '$Department', '$Advisor')";
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
