<?php

if (isset($_POST['codi_horari']) && isset($_POST['classe']) && isset($_POST['professor']) && isset($_POST['asignatura']) && isset($_POST['aula']) && isset($_POST['dia']) && isset($_POST['hora'])) {
    $codi_horari = $_POST['codi_horari'];
    $classe = $_POST['classe'];
    $professor = $_POST['professor'];
    $asignatura = $_POST['asignatura'];
    $aula = $_POST['aula'];
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];

    require_once ('../../models/database.php');

    $database = new Database();

    $conn = $database->getConnection();

    $sql = "INSERT INTO horaris (codi_horari, classe, professor, asignatura, aula, dia, hora) VALUES (:codi_horari, :classe, :professor, :asignatura, :aula, :dia, :hora)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':codi_horari', $codi_horari);
    $stmt->bindParam(':classe', $classe);
    $stmt->bindParam(':professor', $professor);
    $stmt->bindParam(':asignatura', $asignatura);
    $stmt->bindParam(':aula', $aula);
    $stmt->bindParam(':dia', $dia);
    $stmt->bindParam(':hora', $hora);

    try {
        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No s\'han rebut les dades esperades.']);
}

?>