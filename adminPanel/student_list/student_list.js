/* function openAddStudentModal() {
    var myModal = new bootstrap.Modal(document.getElementById('addStudentModal'), { keyboard: false });
    myModal.show();
} */
function openDeleteConfirmationModal() {
    var deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteConfirmationModal.show();
}
function deleteClass() {
    var deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteConfirmationModal.hide();
}
function openEditStudentModal(regNo, classID, department, section, gender, dob, studentName, location, phone, annualIncome) {
    document.getElementById('editRegNo').value = regNo;
    document.getElementById('editClassID').value = classID;
    document.getElementById('editDepartment').value = department;
    document.getElementById('editSection').value = section;
    document.getElementById('editGender').value = gender;
    document.getElementById('editDOB').value = dob;
    document.getElementById('editStudentName').value = studentName;
    document.getElementById('editLocation').value = location;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editAnnualIncome').value = annualIncome;

    var myModal = new bootstrap.Modal(document.getElementById('editStudentModal'), { keyboard: false });
    myModal.show();
}

function updateStudentDetails() {
    var myModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    myModal.hide();
}