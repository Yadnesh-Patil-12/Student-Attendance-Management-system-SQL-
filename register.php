<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "database.php";
header("Content-Type: application/json");

if (!isset($_POST["fullName"]) || !isset($_POST["username"]) || !isset($_POST["password"])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$fullName = trim($_POST["fullName"]);
$username = trim($_POST["username"]);
$password = $_POST["password"];

try {
    $dbo = new Database();
    $conn = $dbo->getConnection();

    // Check if username already exists
    $checkQuery = "SELECT COUNT(*) FROM faculty_details WHERE user_name = :username";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->execute([':username' => $username]);
    if ($checkStmt->fetchColumn() > 0) {
        echo json_encode(["error" => "Username already taken"]);
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new faculty
    $insertQuery = "
        INSERT INTO faculty_details (name, user_name, password)
        VALUES (:name, :username, :password)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->execute([
        ':name' => $fullName,
        ':username' => $username,
        ':password' => $hashedPassword
    ]);

    echo json_encode(["success" => "Account created successfully! Please login."]);
    error_log("New faculty registered: $username");

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
