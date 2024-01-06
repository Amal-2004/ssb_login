<?php
include "connection.php";

if (isset($_POST['submit'])) {
    $staff_name = $_POST['addName'];
    $department = $_POST['addDepartment'];
    $email_id = $_POST['addEmail_ID'];
    $password = md5($_POST['password']); 
    $fullname = $_POST['addFullname'];
    $designation = $_POST['addDesignation'];
    $isactive = isset($_POST['addIsactive']) ? 1 : 0;

    $sql = "INSERT INTO ssbaide_users (Staff_Name,  Department, Email_ID, Password, Fullname, Designation, isactive) 
            VALUES ('$staff_name', '$department', '$email_id', '$password', '$fullname', '$designation', '$isactive')";
    
    if ($con->query($sql)) {
        header('Location:staff.php');
        exit();
    } else {
        echo "Error: " . $con->error;
    }
}


//update
//update
if (isset($_POST['update'])) {
  
    $editstaffname = $_POST['editstaffname'];
    $editdepartment = $_POST['editdepartment'];
    $editemailid = $_POST['editemailid'];
    $editfullname = $_POST['editfullname'];
    $editdesignation = $_POST['editdesignation'];
    
    // Prepare the SQL statement to update the record
    $updateSql = "UPDATE ssbaide_users SET staff_Name='$editstaffname', Department='$editdepartment', Fullname='$editfullname', Designation='$editdesignation' WHERE Email_ID='$editemailid'";

    // Execute the update query
    if ($con->query($updateSql)) {
        // Redirect to the staff.php page after a successful update
        header('Location: staff.php');
        exit();
    } else {
        // Display an error message if the update fails
        echo "Error updating record: " . $con->error;
    }
}




// delete
if (isset($_POST['delete'])) {
    $deleteEmailId = $_POST['deleteEmailId'];

    $deleteSql = "DELETE FROM ssbaide_users WHERE Email_ID='$deleteEmailId'";

    if ($con->query($deleteSql)) {
        header('Location: staff.php');
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
    <title>Staff Table</title>
    <link rel="stylesheet" href="../adminPanel.css">
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
                   
                <button class="nav-link active " id="staffListBtn" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">Staff List</button>
                 
            </li>
                <li class="nav-item" role="presentation">
                <a href="../schedule/schedule.php">   
                <button class="nav-link" id="scheduleListBtn" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Schedule</button>
                </li>
                <a href="../student_list/student_list.php">   
                <li class="nav-item" role="presentation">
                 
                <button class="nav-link " id="studentListBtn" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="true">Student List</button>
 
            </li>
            </a>   
            </ul>
        </div>
        <br><br><br><br>
        <div id="content">



<form method="post" action="importdata.php" enctype="multipart/form-data">
<input type="file" class="btn btn-primary" style="width:250px; height:45px; position: relative; top: 25px;" name="file" />
<button type="submit" name="submit" class="btn btn-primary" style="margin: 10px; height: 45px; position: relative; top: 25px;"> Import </button>
 <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal" style="position: relative; top: 25px;left: 20px">+ </button>
</form>

<table id="tb"  class="table table-bordered text-center" style="position: relative;top: 50px;">
    <thead class="thead" id="head">
        <tr>
            <th>S.NO</th>
            <th>Staff Name</th>
            <th>Department</th>
            <th>Email ID</th>
            <th>Fullname</th>
            <th>Designation</th>
            <th>Active</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $result = $con->query("SELECT S_NO, Staff_Name, Department, Email_ID, Fullname, Designation, isactive FROM ssbaide_users");
        if($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $i++;
        ?>
            <tr>
                <td><?php echo $i?></td>
                <td><?php echo $row['Staff_Name']?></td>
                <td><?php echo $row['Department']?></td>
                <td><?php echo $row['Email_ID'] ?></td>
                <td><?php echo $row['Fullname'] ?></td>
                <td><?php echo $row['Designation'] ?></td>
               
                <td><?php echo $row['isactive'] ?></td>
                <td>
                <button type="button" class="btn btn-success" onclick="openEditModal(
        '<?php echo $row['Staff_Name']; ?>',
        '<?php echo $row['Department']; ?>',
        '<?php echo $row['Email_ID']; ?>',
        '<?php echo $row['Fullname']; ?>',
        '<?php echo $row['Designation']; ?>'
    )">Edit</button>
    <button type="button" class="btn btn-danger" onclick="openDeleteConfirmationModal()">Delete</button>
                </td>  
            </tr>
        <?php 
            }
        } else { ?>
            <tr><td colspan="9">No user Found...</td></tr>
        <?php } ?>
    </tbody>
</table>
    <div class="modal" tabindex="-1" role="dialog" id="addUserModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="POST" action="staff.php">
                    <div class="mb-3">
                        <label for="addName" class="form-label">Staff Name</label>
                        <input type="text" name="addName" class="form-control" id="addStaffname" required>
                    </div>
                    <div class="mb-3">
                        <label for="addClassID" class="form-label">Class ID</label>
                        <input type="text" name="addClassID" class="form-control" id="addClassID" required>
                    </div>

                    <div class="mb-3">
                        <label for="addDepartment" class="form-label">Department</label>
                        <input type="text" name="addDepartment" class="form-control" id="addDepartment" required>
                    </div>
                    <div class="mb-3">
                        <label for="addEmail_ID" class="form-label">Email ID</label>
                        <input type="email" name="addEmail_ID" class="form-control" id="addAdvisor" required>
                    </div>
                    <div class="mb-3">
                        <label for="addPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="addPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="addFullname" class="form-label">Full Name</label>
                        <input type="text" name="addFullname" class="form-control" id="addFullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="addDesignation" class="form-label">Designation</label>
                        <input type="text" name="addDesignation" class="form-control" id="addDesignation" required>
                    </div>
                    <div class="mb-3">
                        <label for="addIsactive" class="form-check-label">Is Active</label>
                        <input type="checkbox" name="addIsactive" class="form-check-input" id="addIsactive" value="1">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-success">Add User</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form id="editUserForm" method="POST" action="staff.php" enctype="multipart/form-data">

                <div class="modal-header">
                    <h4 class="modal-title">Edit Staff Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Use 'readonly' instead of 'required' for email since it's for editing -->
                    <div class="mb-3">
                        <label for="editstaffname" class="form-label">Staff Name</label>
                        <input type="text" class="form-control" id="editStaffName" name="editstaffname" required>
                    </div>
                    <div class="mb-3">
                        <label for="editdepartment" class="form-label">Department</label>
                        <input type="text" class="form-control" id="editDepartment" name="editdepartment" required>         
                    </div>

                    <div class="mb-3">
                    <label for="editemailid" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="editfullname" name="editfullname" required>
                    </div>

                    <div class="mb-3">
                    <label for="editemailid" class="form-label">Designation</label>
                    <input type="text" class="form-control" id="editdesignation" name="editdesignation" required>
                    </div>

                    <div class="mb-3">
                    <label for="editemailid" class="form-label">Email ID</label>
                    <input type="text" class="form-control" id="editemailid" name="editemailid" required>
                    </div>

                    </div>
                <div class="modal-footer">
                    <button type="submit" name="update" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- Delete Confirmation Modal -->
<div class="modal" tabindex="-1" role="dialog" id="deleteConfirmationModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form method="POST" action="staff.php">
    <div class="modal-header">
        <h4 class="modal-title">Delete Confirmation</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <p>Are you sure you want to delete this record?</p>
        <div class="mb-3">
            <label for="confirmDelete" class="form-label">Type the Email ID to confirm:</label>
            <input type="text" name="deleteEmailId" id="deleteEmailId" class="form-control" required >
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
    <script src="staff.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>