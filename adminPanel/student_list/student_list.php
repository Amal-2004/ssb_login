<?php
include "connection.php";


if (isset($_POST['submit'])) {
    $regNo = $_POST['regNo'];
    $classID = $_POST['classID'];
    $department = $_POST['department'];
    $section = $_POST['section'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $studentName = $_POST['studentName'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];
    $annualIncome = $_POST['annualIncome'];

    $sql = "INSERT INTO student_list (REG_NO, Class_ID, Department, Section, Gender, DOB, Student_Name, Location, Phone, Annual_Income) 
            VALUES ('$regNo', '$classID', '$department', '$section', '$gender', '$dob', '$studentName', '$location', '$phone', '$annualIncome')";

    if ($con->query($sql)) {
        header('Location: student_list.php'); // Redirect to the page where you display student data
        exit();
    } else {
        echo "Error: " . $con->error;
    }
}

//update

if (isset($_POST['update'])) {
    $editRegNo = $_POST['editRegNo'];
    $editClassID = $_POST['editClassID'];
    $editDepartment = $_POST['editDepartment'];
    $editSection = $_POST['editSection'];
    $editGender = $_POST['editGender'];
    $editDOB = $_POST['editDOB'];
    $editStudentName = $_POST['editStudentName'];
    $editLocation = $_POST['editLocation'];
    $editPhone = $_POST['editPhone'];
    $editAnnualIncome = $_POST['editAnnualIncome'];

    $sql = "UPDATE student_list SET
            REG_NO = '$editRegNo',
            Class_ID = '$editClassID',
            Department = '$editDepartment',
            Section = '$editSection',
            Gender = '$editGender',
            DOB = '$editDOB',
            Student_Name = '$editStudentName',
            Location = '$editLocation',
            Phone = '$editPhone',
            Annual_Income = '$editAnnualIncome'
            WHERE Class_ID = '$editClassID'";

    if ($con->query($sql)) {
        header('Location: student_list.php'); // Redirect to the page where you display student data
        exit();
    } else {
        echo "Error: " . $con->error;
    }
}

// delete

if (isset($_POST['delete'])) {
    $deleteClassID = $_POST['deleteClassID'];

    $deleteSql = "DELETE FROM student_list WHERE Class_ID='$deleteClassID'";

    if ($con->query($deleteSql)) {
        header('Location: student_list.php'); // Redirect to the page where you display student data
        exit();
    } else {
        echo "Error deleting record: " . $con->error;
    }
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Student List</title>
    <link rel="stylesheet" href="student_list.css">
</head>
<body>    

<div id="card">
        <nav class="navbar navbar-dark bg-primary fixed-top">
            <a class="navbar-brand" href="#" style="font-weight:bold;">&nbsp;&nbsp; SSB CLASS ROOM</a>
            <a class="navbar-brand" href="#" style="font-weight:bold;">Admin panel&nbsp;&nbsp; &nbsp;&nbsp; </a>
        </nav> <div id="btn">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                
            <li class="nav-item" role="presentation">
          <a href="../adminPanel.php">
            <button class="nav-link " id="classList" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="false">Class List</button>
                </li>
                <li class="nav-item" role="presentation">
                <a href="../staff/staff.php">   
                <button class="nav-link" id="staffListBtn" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Staff List</button>
                </a>    
            </li>
                <li class="nav-item" role="presentation">
                <a href="../schedule/schedule.php">   
                <button class="nav-link" id="scheduleListBtn" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Schedule</button>
                </a>    
            </li>
              
                <li class="nav-item" role="presentation">
                 
                <button class="nav-link active" id="studentListBtn" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="true">Student List</button>
 
            </li>
            
            </ul>
        </div>
        <br><br><br><br>
        <div id="content">


<form method="post" action="importdata.php" enctype="multipart/form-data">


<input type="file" class="btn btn-primary" style="width:250px; height:45px; position: relative; top: 25px;" name="file" />
<button type="submit" name="submit" class="btn btn-primary" style="margin: 10px; height: 45px; position: relative; top: 25px;"> Import </button>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal" style="height: 45px; position: relative; top: 25px;left: 10px">+ </button>
  
  </form>
  <table id="tb" class="table table-bordered text-center" style="position: relative; top: 50px;">
    <thead class="thead" id="head">
        <tr>
            <th>S.No</th>
            <th>Registration Number</th>
            <th>Class ID</th>
            <th>Department</th>
            <th>Section</th>
            <th>Gender</th>
            <th>DOB</th>
            <th>Student Name</th>
            <th>Location</th>
            <th>Phone</th>
            <th>Annual Income</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $con->query("SELECT S_NO, REG_NO, Class_ID, Department, Section, Gender, DOB, Student_Name, Location, Phone, Annual_Income FROM student_list");
        if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $i++;
        ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row['REG_NO'] ?></td>
                    <td><?php echo $row['Class_ID'] ?></td>
                    <td><?php echo $row['Department'] ?></td>
                    <td><?php echo $row['Section'] ?></td>
                    <td><?php echo $row['Gender'] ?></td>
                    <td><?php echo $row['DOB'] ?></td>
                    <td><?php echo $row['Student_Name'] ?></td>
                    <td><?php echo $row['Location'] ?></td>
                    <td><?php echo $row['Phone'] ?></td>
                    <td><?php echo $row['Annual_Income'] ?></td>
                    <td>
    <button type="button" class="btn btn-success" onclick="openEditStudentModal(
        '<?php echo htmlspecialchars($row['REG_NO']); ?>',
        '<?php echo htmlspecialchars($row['Class_ID']); ?>',
        '<?php echo htmlspecialchars($row['Department']); ?>',
        '<?php echo htmlspecialchars($row['Section']); ?>',
        '<?php echo htmlspecialchars($row['Gender']); ?>',
        '<?php echo htmlspecialchars($row['DOB']); ?>',
        '<?php echo htmlspecialchars($row['Student_Name']); ?>',
        '<?php echo htmlspecialchars($row['Location']); ?>',
        '<?php echo htmlspecialchars($row['Phone']); ?>',
        '<?php echo htmlspecialchars($row['Annual_Income']); ?>'
    )">Edit</button>
    <button type="button" class="btn btn-danger" onclick="openDeleteConfirmationModal('<?php echo htmlspecialchars($row['REG_NO']); ?>')">Delete</button>
</td>

                </tr>
        <?php }
        } else { ?>
            <tr>
                <td colspan="12">No records found...</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div class="modal" tabindex="-1" role="dialog" id="addUserModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Student</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStudentForm" method="POST" action="student_list.php">
                    <div class="mb-3">
                        <label for="regNo" class="form-label">Registration Number</label>
                        <input type="text" name="regNo" class="form-control" id="regNo" required>
                    </div>
                    <div class="mb-3">
                        <label for="classID" class="form-label">Class ID</label>
                        <input type="text" name="classID" class="form-control" id="classID" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" id="department" required>
                    </div>
                    <div class="mb-3">
                        <label for="section" class="form-label">Section</label>
                        <input type="text" name="section" class="form-control" id="section" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <input type="text" name="gender" class="form-control" id="gender" required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" id="dob" required>
                    </div>
                    <div class="mb-3">
                        <label for="studentName" class="form-label">Student Name</label>
                        <input type="text" name="studentName" class="form-control" id="studentName" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" id="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" id="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="annualIncome" class="form-label">Annual Income</label>
                        <input type="text" name="annualIncome" class="form-control" id="annualIncome" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" class="btn btn-success">Add Student</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit Modal -->
<div class="modal" tabindex="-1" role="dialog" id="editStudentModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editStudentForm" method="POST" action="student_list.php" enctype="multipart/form-data">

                <div class="modal-header">
                    <h4 class="modal-title">Edit Student Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editRegNo" class="form-label">Registration Number</label>
                        <input type="text" class="form-control" id="editRegNo" name="editRegNo" required>
                    </div>
                    <div class="mb-3">
                        <label for="editClassID" class="form-label">Class ID</label>
                        <input type="text" class="form-control" id="editClassID" name="editClassID" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDepartment" class="form-label">Department</label>
                        <input type="text" class="form-control" id="editDepartment" name="editDepartment" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSection" class="form-label">Section</label>
                        <input type="text" class="form-control" id="editSection" name="editSection" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGender" class="form-label">Gender</label>
                        <input type="text" class="form-control" id="editGender" name="editGender" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDOB" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="editDOB" name="editDOB" required>
                    </div>
                    <div class="mb-3">
                        <label for="editStudentName" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="editStudentName" name="editStudentName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="editLocation" name="editLocation" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="editPhone" name="editPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAnnualIncome" class="form-label">Annual Income</label>
                        <input type="text" class="form-control" id="editAnnualIncome" name="editAnnualIncome" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update" class="btn btn-success">Update Student</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="updateStudentDetails()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteConfirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="student_list.php">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Confirmation</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this record?</p>
                    <div class="mb-3">
                        <label for="confirmDelete" class="form-label">Type the Class ID to confirm:</label>
                        <input type="text" name="deleteClassID" id="deleteClassID" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>

    <script src="student_list.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>