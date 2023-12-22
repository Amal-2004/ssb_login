function openEditModal(classId, dayOrder, subName, hr,staffId,subCode) {
    document.getElementById('editClassId').value = classId;
    document.getElementById('editDayOrder').value = dayOrder;
    document.getElementById('editSubName').value = subName;
    document.getElementById('editHr').value = hr;
    document.getElementById('editStaffid').value = staffId;
    document.getElementById('editSubcode').value = subCode;

    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}

function openDeleteConfirmationModal() {
    var deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteConfirmationModal.show();
}

function deleteClass() {
    var deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteConfirmationModal.hide();
}

function updateClassDetails() {
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.hide();

    // Ensure the form doesn't submit by returning false
    return false;
}
