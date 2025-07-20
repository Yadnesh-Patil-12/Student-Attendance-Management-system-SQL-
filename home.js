$(document).ready(function () {
    $("#session").change(function () {
        let session = $(this).val();
        let courseDropdown = $("#course");

        courseDropdown.empty().append('<option value="">-- Select Course --</option>');

        if (session) {
            $.post("fetch_data.php", { action: "getCourses", session: session }, function (data) {
                data.forEach(course => {
                    courseDropdown.append(`<option value="${course.course_id}">${course.course_name}</option>`);
                });
                courseDropdown.prop("disabled", false);
            }, "json");
        } else {
            courseDropdown.prop("disabled", true);
        }

        $("#studentListContainer").hide();
    });
    $("#attendanceDate").change(function () {
        console.log("Date changed:", $(this).val()); // Debugging log
        $("#course").trigger("change"); // Triggers course selection change
    });
    $(document).ready(function() {
        // Fetch students when a course is selected
        $("#course").change(function() {
            let selectedCourse = $(this).val();
            let selectedDate = $("#attendanceDate").val();
            
            console.log("Selected course:", selectedCourse);  // Check if the course is selected
            console.log("Selected date:", selectedDate);      // Check if the date is selected
            
            if (selectedCourse && selectedDate) {
                $.ajax({
                    url: "fetch_students.php",
                    type: "POST",
                    data: { course: selectedCourse, date: selectedDate }, // Ensure course and date are being sent
                    dataType: "json",
                    success: function(response) {
                        console.log("Fetched students:", response); // Debugging output
                        let studentList = $("#studentList");
                        studentList.empty();
    
                        if (response.length > 0) {
                            response.forEach(student => {
                                studentList.append(`
                                    <tr>
                                        <td>${student.roll_no}</td>
                                        <td>${student.name}</td>
                                        <td><input type="checkbox" class="attendanceCheckbox" data-roll="${student.roll_no}"></td>
                                    </tr>
                                `);
                            });
                            $("#studentListContainer").show();
                        } else {
                            studentList.append(`<tr><td colspan="3">No students found.</td></tr>`);
                            $("#studentListContainer").show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching students:", error);
                        console.error("Response text:", xhr.responseText); // Log the full response for debugging
                    }
                });
            }
        });
    });
    

    $("#printReport").click(function () {
        window.print();
    });
});
