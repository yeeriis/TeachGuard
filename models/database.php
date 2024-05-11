<?php
class Database {
    protected $db;

    public function __construct() {
        $servername = "localhost";
        $dbname = "teachguard";
        $username = "root";
        $password = "";

        try {
            $this->db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            echo "Connection failed: " . $error->getMessage();
        }
    }

    public function getConnection() {
        return $this->db;
    }
}
?>
