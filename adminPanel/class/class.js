function addUser() {
    var name = document.getElementById('addName').value;
    var department = document.getElementById('addDepartment').value;
    var advisor = document.getElementById('addAdvisor').value;
    var tbody = document.querySelector('tbody');
    var newRow = document.createElement('tr');
    newRow.innerHTML = `
        <th scope="row">${tbody.children.length + 1}</th>
        <td>${name}</td>
        <td>${department}</td>
        <td>${advisor}</td>
        <td>
            <button type="button" class="btn btn-success" onclick="openEditModal('${name}', '${department}', '${advisor}')" id="edit">Edit</button>
            <button type="button" class="btn btn-danger" id="delete">Delete</button>
        </td>
    `;
    tbody.appendChild(newRow);
    document.getElementById('addUserForm').reset();
}

function openEditModal(className, department, advisor) {
    document.getElementById('editClassName').value = className;
    document.getElementById('editDepartment').value = department;
    document.getElementById('editAdvisor').value = advisor;
    var myModal = new bootstrap.Modal(document.getElementById('editModal'), { keyboard: false });
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
}
