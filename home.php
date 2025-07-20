<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

require_once "database.php";
$dbo = new Database();
$conn = $dbo->getConnection();

$query = "SELECT id FROM faculty_details WHERE user_name = :username";
$stmt = $conn->prepare($query);
$stmt->execute([':username' => $_SESSION["username"]]);
$_SESSION["faculty_id"] = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Attendance System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <hr>

        <div class="mb-3">
            <label for="session" class="form-label">Select Session:</label>
            <select id="session" class="form-select">
                <option value="">-- Select Session --</option>
                <?php
                $query = "SELECT id, CONCAT(year, ' - ', term) AS session_name FROM session_details";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['id']}'>{$row['session_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="course" class="form-label">Select Course:</label>
            <select id="course" class="form-select" disabled>
                <option value="">-- Select Course --</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="attendanceDate" class="form-label">Select Date:</label>
            <input type="date" id="attendanceDate" class="form-control" max="<?php echo date('Y-m-d'); ?>">
        </div>

        <div id="studentListContainer" style="display: none;">
            <h4>Student List</h4>
            <div id="message" class="alert" style="display: none;"></div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody id="studentList"></tbody>
            </table>
            <button id="saveAttendance" class="btn btn-success mt-3">Save Attendance</button>
            <button id="printReport" class="btn btn-primary mt-3">Print Report</button>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $("#session").change(function() {
            let sessionId = $(this).val();
            let courseDropdown = $("#course");
            courseDropdown.prop("disabled", true).html('<option value="">-- Select Course --</option>');

            if (sessionId) {
                $.post("fetch_data.php", { action: "getCourses", session: sessionId }, function(data) {
                    data.forEach(course => {
                        courseDropdown.append(`<option value="${course.code}">${course.title} (${course.code})</option>`);
                    });
                    courseDropdown.prop("disabled", false);
                }, "json");
            }
            $("#studentListContainer").hide();
        });

        $("#course, #attendanceDate").change(function() {
    let selectedCourse = $("#course").val();
    let selectedDate = $("#attendanceDate").val();

    if (selectedCourse && selectedDate) {
        $.ajax({
            url: "fetch_students.php",
            type: "POST",
            data: { course: selectedCourse, date: selectedDate },
            dataType: "json",
            success: function(response) {
                console.log("Fetched students:", response);
                let studentList = $("#studentList");
                studentList.empty();

                if (response.error) {
                    studentList.append(`<tr><td colspan="3">${response.error}</td></tr>`);
                } else if (response.message) {
                    studentList.append(`<tr><td colspan="3">${response.message}</td></tr>`);
                } else if (response.length > 0) {
                    response.forEach(student => {
                        let isPresent = student.status === "PRESENT";
                        studentList.append(`
                            <tr>
                                <td>${student.roll_no}</td>
                                <td>${student.name}</td>
                                <td><input type="checkbox" class="attendanceCheckbox" data-student-id="${student.id}" ${isPresent ? "checked" : ""}></td>
                            </tr>
                        `);
                    });
                    $("#studentListContainer").show();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching students:", error, xhr.responseText);
            }
        });
    }
});

$("#saveAttendance").click(function() {
    let selectedCourse = $("#course").val();
    let selectedDate = $("#attendanceDate").val();
    let selectedSession = $("#session").val(); // Add this
    let attendance = [];

    $(".attendanceCheckbox").each(function() {
        attendance.push({
            student_id: $(this).data("student-id"),
            present: $(this).is(":checked")
        });
    });

    $.ajax({
        url: "save_attendance.php",
        type: "POST",
        data: {
            course: selectedCourse,
            session: selectedSession, // Add this
            date: selectedDate,
            attendance: JSON.stringify(attendance)
        },
        dataType: "json",
        success: function(response) {
            console.log("Save response:", response);
            let message = $("#message");
            if (response.success) {
                message.removeClass("alert-danger").addClass("alert-success").text(response.success).show();
            } else {
                message.removeClass("alert-success").addClass("alert-danger").text(response.error).show();
            }
            setTimeout(() => message.hide(), 3000);
        },
        error: function(xhr, status, error) {
            console.error("Error saving attendance:", error, xhr.responseText);
        }
    });
});

        $("#printReport").click(function() {
            window.print();
        });
    });
    </script>
</body>
</html>
