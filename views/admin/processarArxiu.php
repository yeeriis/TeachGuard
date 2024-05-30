<?php

if (isset($_POST['nombre_aula']) && isset($_POST['nombre_curso'])) {
    $nombre_aula = $_POST['nombre_aula'];
    $nombre_curso = $_POST['nombre_curso'];

    require_once ('../../models/database.php');

    $database = new Database();

    $conn = $database->getConnection();

    $sql = "INSERT INTO aules (nom_aula, nom_curs) VALUES (:nom_aula, :nom_curso)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':nom_aula', $nombre_aula);
    $stmt->bindParam(':nom_curso', $nombre_curso);

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