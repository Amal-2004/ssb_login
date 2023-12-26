$(document).ready(function() {
    // Get the Class_ID from the session or cookie
    var classID = getCookie('Class_ID'); // Replace 'Class_ID' with the actual cookie name

    $('#myModal').modal('show');
    var modalDataArray = [];

    function fetchDataFromBackend(classID) {
        $.ajax({
            url: 'fetch_data.php',
            method: 'GET',
            dataType: 'json',
            data: { classID: classID },
            success: function(response) {
                if (response && response.length > 0) {
                    response.forEach(function(register) {
                        $('#attendanceTableBody').append(`
                            <tr>
                                <td>${register.REG_NO}</td>
                                <td>
                                    <label>
                                        <input type="radio" name="status_${register.REG_NO}" class="form-check-input" value="Present" checked> Present
                                    </label>
                                    <label>
                                        <input type="radio" name="status_${register.REG_NO}" class="form-check-input" value="Absent"> Absent
                                    </label>
                                    <label>
                                        <input type="radio" name="status_${register.REG_NO}" class="form-check-input" value="Leave"> Leave
                                    </label>
                                    <label>
                                        <input type="radio" name="status_${register.REG_NO}" class="form-check-input" value="OD"> OD
                                    </label>
                                    <label>
                                        <input type="radio" name="status_${register.REG_NO}" class="form-check-input" value="TL"> TL
                                    </label>
                                    <!-- Other input labels for different statuses -->
                                </td>
                            </tr>
                        `);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    fetchDataFromBackend(classID);

    window.saveModalData = function() {
        var ICTValue = $('#ICT').val().trim();
        var topicValue = $('#topic').val().trim();
        var activityValue = $('#activity').val().trim();

        if (ICTValue === '' || topicValue === '' || activityValue === '') {
            alert('Please fill in all the fields.');
            return;
        }

        var modalData = {
            ICT: ICTValue,
            topic: topicValue,
            activity: activityValue
        };

        saveModalDataFunction(modalData);
    };

    function saveModalDataFunction(modalData) {
        modalDataArray.push({
            ICT: modalData.ICT,
            topic: modalData.topic,
            activity: modalData.activity
            // Add other properties as needed
        });

        $('#ICT').val('');
        $('#topic').val('');
        $('#activity').val('');
        $('#myModal').modal('hide');
        $('#tbl').show();
    }

    window.cancel = function() {
        window.location.href = 'myClass.php';
    };

    $('#save').on('click', function() {
        var attendanceData = {};

        $('#attendanceTableBody tr').each(function() {
            var registerNumber = $(this).find('td:first-child').text();
            var status = $(this).find('input[type="radio"]:checked').val();
            attendanceData[registerNumber] = status;
        });

        var combinedData = {
            attendanceData: attendanceData,
            modalData: modalDataArray
        };

        $.ajax({
            url: 'attendance_table.php',
            method: 'POST',
            data: JSON.stringify(combinedData),
            contentType: 'application/json',
            success: function(response) {
                console.log('Data saved successfully', combinedData);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Function to get cookie value by name
    function getCookie(cookieName) {
        var name = cookieName + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var cookieArray = decodedCookie.split(';');
        for (var i = 0; i < cookieArray.length; i++) {
            var cookie = cookieArray[i].trim();
            if (cookie.indexOf(name) === 0) {
                return cookie.substring(name.length, cookie.length);
            }
        }
        return "";
    }
});
