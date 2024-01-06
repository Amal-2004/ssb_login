$(document).ready(function () {
    var defaultContent = "class/class.php";
    function loadContent(contentUrl) {
        $("#content").empty();
        $("#content").load(contentUrl);
    }
    $("#classList").click(function () {
        loadContent("class/class.php");
     
    })
    $("#staffList").click(function () {
        loadContent("staff/staff.php");
    })
    $("#scheduleList").click(function () {
        loadContent("schedule/schedule.php");
    })
    $("#studentList").click(function () {
        loadContent("student_list/student_list.php");
    });
    // Load the default content (Class List) on page load
    loadContent(defaultContent);
});
