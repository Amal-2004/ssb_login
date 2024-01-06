<?php
include "connection.php";
//insertion
if (isset($_POST['submit'])) {
    
    $class_name = $_POST['addName'];
    $department = $_POST['addDepartment'];
    $advisor = $_POST['addAdvisor'];

    
    $sql = "INSERT INTO ssb_classlist (Class_Name, Department, Advisor) VALUES ('$class_name', '$department', '$advisor')";
    
    if ($con->query($sql)) {
      
        header('Location: ../adminPanel.php');
        exit();
    } else {
       
        echo "Error: " . $con->error;
    }
}
//update
if (isset($_POST['update'])) {
    
    $editClassName = $_POST['editClassName'];
    $editDepartment = $_POST['editDepartment'];
    $editAdvisor = $_POST['editAdvisor'];

    $updateSql = "UPDATE ssb_classlist SET Class_Name='$editClassName', Department='$editDepartment', Advisor='$editAdvisor' WHERE Class_Name='$editClassName'";
    echo $updateSql;
    if ($con->query($updateSql)) {
        header('Location: ../adminPanel.php');
        exit();
    } else {
        echo "Error updating record: " . $con->error;
    }
}

// delete
if (isset($_POST['delete'])) {
    $deleteClassId = $_POST['deleteClassId'];

    $deleteSql = "DELETE FROM ssb_classlist WHERE Class_ID='$deleteClassId'";

    if ($con->query($deleteSql)) {
        header('Location: ../adminPanel.php');
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
    <link rel="stylesheet" href="class/class.css">
</head>
<body>  
<!--CSV -->
<form method="post" action="class/importdata.php" enctype="multipart/form-data">
<!--<input type="file" name="file" value="upload" id="fileInput" style="display: none;" onchange="open_file()"/>
<button id="cs" class="btn btn-primary" onclick="document.getElementById('fileInput').click();" style="position: relative; top: 25px;left: 10px" />UPLOAD CSV</button>
-->
<input type="file" class="btn btn-primary" style="width:250px; height:45px; position: relative; top: 25px;" name="file" />
<button type="submit" class="btn btn-primary" style="margin: 10px; height: 45px; position: relative; top: 25px;" id="sub"name="submit" > Import </button>

<button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal" style="height: 45px; position: relative; top: 25px;left: 10px">+ </button>

</form>

<table id="tb"  class="table table-bordered text-center" style="position: relative;top: 50px;">
    
    <thead class="thead" id="head">
            <tr>
                <th>S.NO</th>
                <th>Class ID</th>
                <th>Class Name</th>
                <th>Department</th>
                <th>Advisor</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
    
    $result = $con->query("SELECT Class_ID, Class_Name, Department, Advisor FROM ssb_classlist");
    if($result->num_rows>0)
    {
        $i=0;
        while($row =$result->fetch_assoc()){
            $i++;
        
    ?>
     <tr>
        <td><?php echo $i?></td>
        <td><?php echo $row['Class_ID']; ?></td>
        <td><?php echo $row['Class_Name']?></td>
        <td><?php echo $row['Department']?></td>
        <td><?php echo $row['Advisor'] ?></td>
        <td>
        <button type="button" class="btn btn-success" onclick="openEditModal('<?php echo $row['Class_Name']; ?>', '<?php echo $row['Department']; ?>', '<?php echo $row['Advisor']; ?>')" id="edit">Edit</button>
          <button type="button" class="btn btn-danger" onclick="openDeleteConfirmationModal('<?php echo $row['Class_ID']; ?>')">Delete</button>
          </td>
    </tr>

    <?php }}
    else{ ?>
    <tr><td colspan="5">No user Found...</td></tr> <?php } ?>
                
            
    </tbody>
</table>

<!--Add  -->
<div class="modal" tabindex="-1" role="dialog" id="addUserModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" method="post" action="class/class.php">
                    <input type="hidden" name="s_no" id="s_no">
                    <input type="hidden" name="class_id" id="class_id">
                        <div class="mb-3">
                            <label for="addName" class="form-label">Class Name</label>
                            <input type="text" name="addName" class="form-control" id="addName" required>
                        </div>
                        <div class="mb-3">
                            <label for="addDepartment" class="form-label">Department</label>
                            <input type="text" name="addDepartment" class="form-control" id="addDepartment" required>
                        </div>
                        <div class="mb-3">
                            <label for="addAdvisor" class="form-label">Advisor</label>
                            <input type="text" name="addAdvisor"  class="form-control" id="addAdvisor" required>
                        </div>
         
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-success" onclick="">Add</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
            </form>
        </div>
    </div>

<!-- Edit-->


<div class="modal" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form id="editUserForm" method="POST" action="class/class.php">
            <div class="modal-header">
                
                <h4 class="modal-title">Edit Class Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               
                    <div class="mb-3">
                        <label for="editClassName" class="form-label">Class Name</label>
                        <input type="text" name="editClassName" class="form-control" id="editClassName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDepartment" class="form-label">Department</label>
                        <input type="text" name="editDepartment" class="form-control" id="editDepartment" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAdvisor" class="form-label">Advisor</label>
                        <input type="text" name="editAdvisor" class="form-control" id="editAdvisor" required>
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

<!--Delete-->
<div class="modal" tabindex="-1" role="dialog" id="deleteConfirmationModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="class/class.php" id="deleteForm">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Confirmation</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this class?</p>
                    <div class="mb-3">
                        <label for="confirmDelete" class="form-label">Type the Class ID to confirm:</label>
                        <input type="text" name="deleteClassId" class="form-control" required>
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

<script src="class/class.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>