<?php
require_once("database.php");
class Incidencia extends Database
{
    public function obtenerListado()
    {
        $consulta = $this->db->prepare("SELECT * FROM incidencias");
        $consulta->execute();
        $incidencia = $consulta->fetchAll();
        return $incidencia;
    }

    public function obtenerListadoContabilidad($usuario){
        $consulta = $this->db->prepare("SELECT * FROM incidencias WHERE nick='$usuario' ");
        $consulta->execute();
        $incidencia = $consulta->fetchAll();
        return $incidencia;
    }

    public function editar($id_incidencia, $estado)
{
    $consulta = $this->db->prepare("UPDATE incidencias SET estado = :estado WHERE id = :id");
    $consulta->bindParam(':estado', $estado);
    $consulta->bindParam(':id', $id_incidencia);
    $consulta->execute();
}


    public function anadirIncidencia($nick, $descripcion, $tipo) {
        $query = "INSERT INTO incidencias (nick, descripcion, tipo) VALUES (:nick, :descripcion, :tipo)";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':nick', $nick);
        $statement->bindParam(':descripcion', $descripcion);
        $statement->bindParam(':tipo', $tipo);
        return $statement->execute();
        
    }
    
}