<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Database
{
    private $servername = "localhost";
    private $db_name = "attendence_db";
    private $username = "root";
    private $password = "";
    public $conn = null;

    public function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->servername};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           //
           // echo "Connected successfully";
        } catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}

$dbo = new Database(); // Test connection
?>
