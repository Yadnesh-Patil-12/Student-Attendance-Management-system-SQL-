<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();  // Start session for user authentication
header("Content-Type: application/json");

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendenceapp/database/database.php";
require_once $path . "/attendenceapp/database/facultyDetails.php";

error_log("Received request: " . json_encode($_REQUEST));

if (!isset($_REQUEST["action"])) {
    echo json_encode(["id" => -1, "status" => "No action provided"]);
    exit;
}

$action = $_REQUEST["action"];
if ($action == "verifyUser") {
    if (!isset($_POST["username"]) || !isset($_POST["password"])) {
        echo json_encode(["id" => -1, "status" => "Missing username or password"]);
        exit;
    }

    $un = $_POST["username"];
    $pw = $_POST["password"];

    error_log("Username: " . $un . " | Password: " . $pw);

    $dbo = new Database();
    $fdo = new faculty_details();
    $rv = $fdo->verifyUser($dbo, $un, $pw);

    error_log("Response from verifyUser: " . json_encode($rv));

    if ($rv["status"] === "Login Successful") {
        $_SESSION["username"] = $un;
        $query = "SELECT id FROM faculty_details WHERE user_name = :un";
        $stmt = $dbo->getConnection()->prepare($query);
        $stmt->execute([':un' => $un]);
        $_SESSION["faculty_id"] = $stmt->fetchColumn();
        echo json_encode(["status" => "Login Successful"]);
    } else {
        echo json_encode($rv);
    }
    exit;
}

echo json_encode(["id" => -1, "status" => "Invalid action"]);
exit;
?>
