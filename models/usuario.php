<?php
require_once("database.php");
class Usuario extends Database {
    private $id;
    private $nombre;
    private $rol;
    private $contrasena;

    public static function obtenerUsuarioPorNombre($nombre) {
        $db = new Database();
        $conexion = $db->getConnection();

        try {
            $stmt = $conexion->prepare("SELECT nombre, rol, contrasena FROM usuarios WHERE nombre = ?");
            $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt->closeCursor();
            return $usuario;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
}