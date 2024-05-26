<?php

require_once("database.php");

class Horario extends Database {
    protected $db;

    public function obtenerProfesoresGuardia()
{
    try {
        $diaSemanaActual = date('N');

        $sql = "SELECT DISTINCT p.nom, p.cognoms
                FROM horaris h
                INNER JOIN professors p ON h.professor = p.codi_professor
                WHERE h.dia = ? AND h.asignatura = 'G'
                LIMIT 4";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $diaSemanaActual, PDO::PARAM_INT);
        $stmt->execute();
        $profesoresGuardia = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $profesoresGuardia;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return null;
    }
}


    public function obtenerDiaSemanaActual() {
        try {
            $diaSemana = date('N');
    
            $stmt = $this->db->prepare("SELECT * FROM dies WHERE id_dia = ?");
            $stmt->bindParam(1, $diaSemana, PDO::PARAM_INT);
            $stmt->execute();
            $diaActual = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
    
            return $diaActual;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Método para obtener todos los profesores disponibles
    public function obtenerProfesores() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM professors");
            $stmt->execute();
            $profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $profesores;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Método para obtener los profesores con clase en una hora específica
    public function obtenerProfesoresPorDia($diaSemana) {
        try {
            $stmt = $this->db->prepare("SELECT DISTINCT p.nom, p.cognoms
                                        FROM horaris h
                                        INNER JOIN professors p ON h.professor = p.codi_professor
                                        INNER JOIN dies d ON h.dia = d.id_dia
                                        WHERE d.dia = ?");
            $stmt->bindParam(1, $diaSemana, PDO::PARAM_INT);
            $stmt->execute();
            $profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $profesores;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function obtenerProfesoresAusentes($hora) {
        try {
            $diaSemanaActual = date('N');
            $stmt = $this->db->prepare("SELECT p.nom, p.cognoms
                                        FROM absencies a
                                        INNER JOIN professors p ON a.professor_id = p.codi_professor
                                        WHERE a.dia_id = ? AND a.hora = ?");
            $stmt->bindParam(1, $diaSemanaActual, PDO::PARAM_INT);
            $stmt->bindParam(2, $hora, PDO::PARAM_STR);
            $stmt->execute();
            $profesoresAusentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $profesoresAusentes;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Método para guardar un comentario para una hora específica
    // public static function guardarComentario($hora, $comentario) {
    //     try {
    //         $stmt = self::$db->prepare("UPDATE hores SET comentari = ? WHERE hora = ?");
    //         $stmt->bindParam(1, $comentario, PDO::PARAM_STR);
    //         $stmt->bindParam(2, $hora, PDO::PARAM_INT);
    //         $stmt->execute();
    //         return true;
    //     } catch (PDOException $e) {
    //         echo "Error: " . $e->getMessage();
    //         return false;
    //     }
    // }

    public function obtenirHores() {
        try {
            $stmt = $this->db->query("SELECT hora FROM hores");
            $horas = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $stmt->closeCursor();
            return $horas;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    public function guardarAbsencia($diaId, $hora, $professorId) {
        try {
            // Comprobar si ya existe una ausencia para el mismo día, hora y profesor
            $stmt_check = $this->db->prepare("SELECT COUNT(*) FROM absencies WHERE dia_id = ? AND hora = ? AND professor_id = ?");
            $stmt_check->bindParam(1, $diaId, PDO::PARAM_INT);
            $stmt_check->bindParam(2, $hora, PDO::PARAM_STR);
            $stmt_check->bindParam(3, $professorId, PDO::PARAM_INT);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();
    
            if ($count > 0) {
                // La ausencia ya existe, no la insertes
                return false;
            } else {
                // La ausencia no existe, procede con la inserción
                $stmt_insert = $this->db->prepare("INSERT INTO absencies (dia_id, hora, professor_id) VALUES (?, ?, ?)");
                $stmt_insert->bindParam(1, $diaId, PDO::PARAM_INT);
                $stmt_insert->bindParam(2, $hora, PDO::PARAM_STR);
                $stmt_insert->bindParam(3, $professorId, PDO::PARAM_INT);
                $stmt_insert->execute();
                return true;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function ausenciaExiste($diaId, $hora, $professorId) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM absencies WHERE dia_id = ? AND hora = ? AND professor_id = ?");
            $stmt->bindParam(1, $diaId, PDO::PARAM_INT);
            $stmt->bindParam(2, $hora, PDO::PARAM_STR);
            $stmt->bindParam(3, $professorId, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    

}






?>