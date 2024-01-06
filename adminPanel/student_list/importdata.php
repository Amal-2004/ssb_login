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
                    $REG_NO = $line[1];
                    $Class_ID = $line[2];
                    $Department = $line[3];
                    $Section = $line[4];
                    $Gender = $line[5];
                    $DOB = $line[6];
                    $Student_Name = $line[7];
                    $Location = $line[8];
                    $Phone = $line[9];
                    $Annual_Income = $line[10];

                    $sql = "SELECT S_NO FROM student_list WHERE REG_NO = '{$REG_NO}' ";
                    $res = $con->query($sql);

                    if ($res->num_rows > 0) {
                        $s = "UPDATE student_list SET S_NO='$S_NO', REG_NO='$REG_NO', Class_ID='$Class_ID', Department='$Department', Section='$Section', Gender='$Gender', DOB='$DOB', Student_Name='$Student_Name', Location='$Location', Phone='$Phone', Annual_Income='$Annual_Income' WHERE REG_NO='$REG_NO'";
                        $con->query($s);
                    } else {
                        $s = "INSERT INTO student_list (S_NO, REG_NO, Class_ID, Department, Section, Gender, DOB, Student_Name, Location, Phone, Annual_Income) VALUES ('$S_NO', '$REG_NO', '$Class_ID', '$Department', '$Section', '$Gender', '$DOB', '$Student_Name', '$Location', '$Phone', '$Annual_Income')";
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
