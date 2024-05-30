<?php

require_once ("database.php");

class Horario extends Database
{
    protected $db;

    // Funció per a obtenir els professors de guardia
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

    // Funció per a obtenir el dia de la setmana actual
    public function obtenerDiaSemanaActual()
    {
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

    // Funció per a obtenir tots els professors que tenen clase aquest dia i hora
    public function obtenerProfesoresConClase($hora, $dia)
    {
        try {
            $stmt = $this->db->prepare("SELECT p.codi_professor, p.nom, p.cognoms, h.aula
                                        FROM horaris h
                                        INNER JOIN professors p ON h.professor = p.codi_professor
                                        WHERE h.hora = :hora AND h.dia = :dia");
            $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
            $stmt->bindParam(':dia', $dia, PDO::PARAM_INT);
            $stmt->execute();
            $profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $profesores;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Funció per a obtenir els professors per dia
    public function obtenerProfesoresPorDia($diaSemana)
    {
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

    // Funció per a obtenir els professors absents per dia i hora
    public function obtenerProfesoresAusentesPorDiaYHora($dia, $hora)
    {
        $query = "SELECT p.nom, p.cognoms, h.aula FROM absencies a
                  JOIN profesores p ON a.id_profesor = p.id
                  JOIN horaris h ON a.id_profesor = h.id_profesor
                  WHERE a.dia = :dia AND a.hora = :hora";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':dia', $dia, PDO::PARAM_INT);
        $stmt->bindParam(':hora', $hora, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Funció per a obtenir l'aula del professor absent
    public function obtenerProfesoresAusentes($hora)
    {
        try {
            $diaSemanaActual = date('N');
            $stmt = $this->db->prepare("SELECT p.codi_professor, p.nom, p.cognoms, h.aula
            FROM absencies a
            INNER JOIN professors p ON a.professor_id = p.codi_professor
            INNER JOIN horaris h ON a.professor_id = h.professor AND a.dia_id = h.dia AND a.hora = h.hora
            WHERE a.dia_id = ? AND a.hora = ?
            ");
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

    public function obtenerAulaProfesorAusente($id_profesor, $hora, $dia)
    {
        try {
            $query = "SELECT aula FROM horaris WHERE professor = :professor AND hora = :hora AND dia = :dia";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':professor', $id_profesor, PDO::PARAM_INT);
            $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
            $stmt->bindParam(':dia', $dia, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['aula'] : 'No assignat';
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    // Funció per a obtenir les hores
    public function obtenirHores()
    {
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

    // Funció per a guardar les absències
    public function guardarAbsencia($diaId, $hora, $professorId)
    {
        try {
            $stmt_insert = $this->db->prepare("INSERT INTO absencies (dia_id, hora, professor_id) VALUES (?, ?, ?)");
            $stmt_insert->bindParam(1, $diaId, PDO::PARAM_INT);
            $stmt_insert->bindParam(2, $hora, PDO::PARAM_STR);
            $stmt_insert->bindParam(3, $professorId, PDO::PARAM_STR);
            $stmt_insert->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Funció per a eliminar tots els registres de la taula absencies
    public function eliminarTodasAusencias()
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM absencies");
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}

?>