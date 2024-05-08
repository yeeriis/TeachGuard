<?php
class Database {
    protected $db;

    public function __construct() {
        $servername = "localhost";
        $dbname = "teachguard";
        $username = "root";
        $password = "";

        try {
            // Crear conexión PDO
            $this->db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Establecer el modo de error PDO para excepciones
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
