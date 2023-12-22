<?php

include "connection.php";
if (isset($_POST['submit'])) {
    
    $Class_ID = $_POST['classid'];
    $Day_Order = $_POST['dayorder'];
    $Sub_Name  = $_POST['subname'];
    $Hr = $_POST['hr'];
    $Staff_ID = $_POST['staffid']; // New column staff_id
    $Sub_Code = $_POST['subcode']; // New column sub_code
    
    $sql = "INSERT INTO ssb_schedule (Class_ID, Day_Order, Sub_Name, Hr, Staff_ID, Sub_Code) VALUES ('$Class_ID', '$Day_Order', '$Sub_Name', '$Hr', '$Staff_ID', '$Sub_Code')";
    
    if ($con->query($sql)) {
      
        header('Location: schedule.php');
        exit();
    } else {
       
        echo "Error: " . $con->error;
    }
}
//student list table,

//update
if (isset($_POST['update'])) {
    $editClass_ID = $_POST['editclassid'];
    $editDay_Order = $_POST['editdayorder'];
    $editSub_Name = $_POST['editsubname'];
    $editHr = $_POST['edithr'];
    $editStaff_Id = $_POST['editStaffid']; 
    $editSub_Code = $_POST['editsubcode']; 

    $sql = "UPDATE ssb_schedule SET 
            Day_Order='$editDay_Order', 
            Sub_Name='$editSub_Name', 
            Hr='$editHr',
            Staff_Id='$editStaff_Id', 
            SUB_Code='$editSub_Code' 
            WHERE Class_ID='$editClass_ID'";

    if ($con->query($sql)) {
        header('Location: schedule.php');
        exit();
    } else {
        echo "Error updating record: " . $con->error;
    }
}
// delete
if (isset($_POST['delete'])) {
    $deleteClass_Id = $_POST['deleteClassId'];
$deleteSql = "DELETE FROM ssb_schedule WHERE Class_ID ='$deleteClass_Id'";

    if ($con->query($deleteSql)) {
        header('Location: schedule.php');
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
    <title>Class Table</title>
    <link rel="stylesheet" href="schedule.css">
    
</head>
<body>
<form id="form1" method="post" action="importdata.php" enctype="multipart/form-data">
        <input type="file" id="c1" class="btn btn-primary" name="file" />
        <button type="submit" id="c2" name="submit" class="btn btn-primary" > Import </button>
        <button type="button" id="c3" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">+ </button>

      

<div id="class_id"><p>

<?php
include "connection.php";

$selectQuery = "SELECT Class_ID, Class_Name FROM ssb_classlist where Class_ID=1023";

$result = $con->query($selectQuery);



if ($result->num_rows > 0) {
 
    while ($row = $result->fetch_assoc()) {
        echo  $row["Class_ID"] ."-". $row["Class_Name"]. "<br>";
    }
} else {
    echo "No results";
}

?>
</p>
</div>
</form>
<table id="tbl" class="table table-bordered text-center">
    <thead class="thead" id="head">
        <tr>
            <th>S.NO</th>
            <th>Class ID</th>
            <th>Class Name</th>
            <th>DAY ORDER</th>
            <th>SUB_NAME</th>
            <th>HR</th>
            <th>Staff ID</th>
            <th>SUB Code</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $con->query("SELECT Class_ID, Day_Order, Sub_Name, Hr, Staff_ID, SUB_Code FROM ssb_schedule");
       
        if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $i++;
                $className = getClassFromID($row['Class_ID']);
        ?>
                <tr>
                    <td><?php echo $i ?></td>

                    <td><?php echo $row['Class_ID'] ?></td>
                    <td><?php echo $className ?></td>
                    <td><?php echo $row['Day_Order'] ?></td>

                    <td><?php echo $row['Sub_Name'] ?></td>
                    <td><?php echo $row['Hr'] ?></td>
                    <td><?php echo $row['Staff_ID'] ?></td>
                    <td><?php echo $row['SUB_Code'] ?></td>

                    <td>
                        <button type="button" class="btn btn-success" onclick="openEditModal('<?php echo $row['Class_ID']; ?>', '<?php echo $row['Day_Order']; ?>', '<?php echo $row['Sub_Name']; ?>','<?php echo $row['Hr']; ?>', '<?php echo $row['Staff_ID']; ?>', '<?php echo $row['SUB_Code']; ?>')" id="edit">Edit</button>
                        <button type="button" class="btn btn-danger" onclick="openDeleteConfirmationModal()">Delete</button>
                    </td>
                </tr>
        <?php
            }
        } else {
        ?>
            <tr>
                <td colspan="8">No user Found...</td>
            </tr>
        <?php } 
        function getClassFromID($classID) {
    switch ($classID) {
        case 1:
            return '1 BCA';
        case 2:
            return '2 BCA';
        case 3:
            return '3 BCA';
        case 4:
            return '1 BSCIT';
        case 5:
            return '2 BSCIT';
        case 6:
            return '3 BSCIT';
        case 7:
            return '1 BCOM';
        case 8:
            return '2 BCOM 1';
        case 9:
            return '2 BCOM 2';
        case 10:
            return '3 BCOM 1';
        case 11:
            return '2 BCOM 2';
        case 12:
            return '1 BA.ENG';
        case 13:
            return '2 BA.ENG';
        case 14:
            return '3 BA.ENG';
        case 15:
            return '1 BBA';
        case 16:
            return '2 BBA';
        case 16:
            return '3 BBA';
        case 16:
            return '2 Maths';
        default:
            return 'Unknown';
    }
}
?>
    </tbody>
</table>

<div class="modal" tabindex="-1" role="dialog" id="addUserModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="POST" action="schedule.php">
                    <div class="mb-3">
                        <label for="addclassId" class="form-label">Class ID</label>
                        <input type="text" name="classid" class="form-control" id="addclassId" required>
                    </div>
                    <div class="mb-3">
                        <label for="addDayorder" class="form-label">DAYORDER</label>
                        <input type="text" name="dayorder" class="form-control" id="addDayorder" required>
                    </div>
                    <div class="mb-3">
                        <label for="addSubname" class="form-label">SUB_NAME</label>
                        <input type="text" name="subname" class="form-control" id="addSubname" required>
                    </div>
                    <div class="mb-3">
                        <label for="addHr" class="form-label">HR</label>
                        <input type="text" name="hr" class="form-control" id="addHr" required>
                    </div>
                    <div class="mb-3">
                        <label for="addStaffId" class="form-label">Staff ID</label>
                        <input type="text" name="staffid" class="form-control" id="addStaffId" required>
                    </div>
                    <div class="mb-3">
                        <label for="addSubCode" class="form-label">Sub Code</label>
                        <input type="text" name="subcode" class="form-control" id="addSubCode" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-success" onclick="">Add</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>

    <!-- ... your HTML content ... -->
    <div class="modal" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form id="editUserForm" method="POST" action="schedule.php">
        <div class="modal-header">
                    <h4 class="modal-title">Edit Class Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editClassId" class="form-label">Class ID</label>
                        <input type="text" name="editclassid" class="form-control" id="editClassId" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDayOrder" class="form-label">DAYORDER</label>
                        <input type="text" name="editdayorder" class="form-control" id="editDayOrder" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSubName" class="form-label">SUB_NAME</label>
                        <input type="text" name="editsubname" class="form-control" id="editSubName" required>
                    </div>
                    
                   
                    <div class="mb-3">
                        <label for="editHr" class="form-label">HR</label>
                        <input type="text" name="edithr" class="form-control" id="editHr" required>
                    </div>
                    <div class="mb-3">
                        <label for="editStaffid" class="form-label">Staff_Id</label>
                        <input type="text" name="editStaffid" class="form-control" id="editStaffid" required>
                    </div>
                    <div class="mb-3">
                        <label for="editsubcode" class="form-label">SUB_Code</label>
                        <input type="text" name="editsubcode" class="form-control" id="editSubcode" required>
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
            <form method="POST" action="schedule.php">
        <div class="modal-header">
            <h4 class="modal-title">Delete Confirmation</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this record?</p>
            <div class="mb-3">
                <label for="confirmDelete" class="form-label">Type the Class ID to confirm:</label>
                <input type="text" name="deleteClassId" id="deleteClassId" class="form-control" required >
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="schedule.js"></script>
    
</body>
</html>