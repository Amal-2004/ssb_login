
function openDeleteConfirmationModal() {
    var deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteConfirmationModal.show();
}
function deleteClass() {
    var deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteConfirmationModal.hide();
}
function openEditModal(staffname, department, emailid,fullname,designation) {
    document.getElementById('editStaffName').value = staffname;
    document.getElementById('editDepartment').value = department;
    document.getElementById('editfullname').value = fullname;
    document.getElementById('editdesignation').value = designation;
    document.getElementById('editemailid').value = emailid;

    var myModal = new bootstrap.Modal(document.getElementById('editModal'), { keyboard: false });
    myModal.show();
}

// JavaScript function to hide the edit modal
function updateClassDetails() {
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.hide();
}