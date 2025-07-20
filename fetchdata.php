<?php
require_once "database.php";
header("Content-Type: application/json");

$dbo = new Database();

if (isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "getCourses") {
        $session = $_POST["session"] ?? "";

        if ($session) {
            $query = "SELECT id, code, title FROM course_details";
            $stmt = $dbo->getConnection()->prepare($query);
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($courses);
        } else {
            echo json_encode([]);
        }
        exit;
    }
}

echo json_encode(["error" => "Invalid request"]);
exit;
?>
