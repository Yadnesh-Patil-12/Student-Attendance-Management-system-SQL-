<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendenceapp/database/database.php";

class faculty_details {
    public function verifyUser($dbo, $un, $pw) {
        $rv = ["id" => -1, "status" => "ERROR"];

        // Query to check if the user exists
        $query = "SELECT * FROM faculty_details WHERE user_name = :un";
        $stmt = $dbo->getConnection()->prepare($query);


        try {
            $stmt->execute([":un" => $un]);

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Direct password comparison (since no hashing is used)
                if ($result['password'] == $pw) { 
                    $rv = ["status" => "Login Successful"];
                } else {
                    $rv = ["status" => "Incorrect Password"];
                }
                } else {
                    $rv = ["status" => "User Not Found"];
                }
                
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage()); // Logs error for debugging
            $rv = ["id" => -1, "status" => "Database Error"];
        }

        return $rv;
    }
}
?>
