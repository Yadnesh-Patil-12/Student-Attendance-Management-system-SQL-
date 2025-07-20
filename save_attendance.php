<?php
// Document Name: save_attendance.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "database.php";

header("Content-Type: application/json");

$facultyId = $_SESSION['faculty_id'] ?? null;
$courseCode = $_POST['course'] ?? null; // Renamed to reflect it's a code
$sessionId = $_POST['session'] ?? null;
$selectedDate = $_POST['date'] ?? null;
$attendance = json_decode($_POST['attendance'], true) ?? null;

// Debugging
file_put_contents('debug.txt', "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);
file_put_contents('debug.txt', "Parsed attendance: " . print_r($attendance, true) . "\n", FILE_APPEND);

if (!$facultyId) {
    echo json_encode(["error" => "Faculty ID not found in session. Please log in again."]);
    exit;
}
if (!$courseCode || !$sessionId || !$selectedDate || empty($attendance)) {
    echo json_encode(["error" => "Missing required data (course, session, date, or attendance)"]);
    exit;
}

try {
    $dbo = new Database();
    $conn = $dbo->getConnection();

    // Fetch course_id from course_code
    $courseQuery = "SELECT id FROM course_details WHERE code = :courseCode";
    $courseStmt = $conn->prepare($courseQuery);
    $courseStmt->execute([':courseCode' => $courseCode]);
    $courseId = $courseStmt->fetchColumn();
    if (!$courseId) {
        echo json_encode(["error" => "Invalid course code"]);
        exit;
    }

    $conn->beginTransaction();

    foreach ($attendance as $student) {
        $studentId = $student["student_id"];
        $status = $student["present"] ? "PRESENT" : "ABSENT";

        $checkQuery = "
            SELECT COUNT(*) FROM attendance_details 
            WHERE faculty_id = :facultyId 
            AND course_id = :courseId 
            AND session_id = :sessionId 
            AND student_id = :studentId 
            AND DATE(on_date) = DATE(:onDate)";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([
            ':facultyId' => $facultyId,
            ':courseId' => $courseId,
            ':sessionId' => $sessionId,
            ':studentId' => $studentId,
            ':onDate' => $selectedDate
        ]);

        if ($checkStmt->fetchColumn() > 0) {
            $updateQuery = "
                UPDATE attendance_details 
                SET status = :status 
                WHERE faculty_id = :facultyId 
                AND course_id = :courseId 
                AND session_id = :sessionId 
                AND student_id = :studentId 
                AND DATE(on_date) = DATE(:onDate)";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->execute([
                ':status' => $status,
                ':facultyId' => $facultyId,
                ':courseId' => $courseId,
                ':sessionId' => $sessionId,
                ':studentId' => $studentId,
                ':onDate' => $selectedDate
            ]);
            error_log("Updated attendance for student $studentId on $selectedDate: $status");
        } else {
            $insertQuery = "
                INSERT INTO attendance_details (faculty_id, course_id, session_id, student_id, on_date, status)
                VALUES (:facultyId, :courseId, :sessionId, :studentId, :onDate, :status)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->execute([
                ':facultyId' => $facultyId,
                ':courseId' => $courseId,
                ':sessionId' => $sessionId,
                ':studentId' => $studentId,
                ':onDate' => $selectedDate,
                ':status' => $status
            ]);
            error_log("Inserted attendance for student $studentId on $selectedDate: $status");
        }
    }

    $conn->commit();
    echo json_encode(["success" => "Attendance saved successfully"]);
} catch (PDOException $e) {
    $conn->rollBack();
    error_log("Transaction failed: " . $e->getMessage());
    echo json_encode(["error" => "Failed to save attendance: " . $e->getMessage()]);
}
?>
