<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "database.php";

header("Content-Type: application/json");

if (!isset($_POST["course"]) || !isset($_POST["date"])) {
    echo json_encode(["error" => "Course or date not provided"]);
    error_log("⚠️ Missing course or date in POST request");
    exit;
}

$courseCode = $_POST["course"];
$selectedDate = $_POST["date"];
$facultyId = $_SESSION["faculty_id"] ?? 1;

try {
    $dbo = new Database();
    $conn = $dbo->getConnection();

    // Fetch unique students and their attendance status
    $query = "
        SELECT DISTINCT sd.id, sd.roll_no, sd.name, ad.status
        FROM student_details sd
        JOIN course_registration cr ON sd.id = cr.student_id
        JOIN course_details cd ON cr.course_id = cd.id
        LEFT JOIN attendance_details ad ON sd.id = ad.student_id 
            AND ad.course_id = cd.id 
            AND DATE(ad.on_date) = DATE(:selectedDate) 
            AND ad.faculty_id = :facultyId
        WHERE cd.code = :courseCode
        LIMIT 25";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':selectedDate', $selectedDate, PDO::PARAM_STR);
    $stmt->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
    $stmt->bindParam(':facultyId', $facultyId, PDO::PARAM_INT);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($students)) {
        error_log("No students found for course: $courseCode, date: $selectedDate");
        echo json_encode(["message" => "No students found"]);
    } else {
        echo json_encode($students);
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
