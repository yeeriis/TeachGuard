<?php

if (isset($_POST['codi_professor']) && isset($_POST['nom']) && isset($_POST['cognoms']) && isset($_POST['carrec'])) {
    $codi_professor = $_POST['codi_professor'];
    $nom = $_POST['nom'];
    $cognoms = $_POST['cognoms'];
    $carrec = $_POST['carrec'];

    require_once ('../../models/database.php');

    $database = new Database();

    $conn = $database->getConnection();

    $sql = "INSERT INTO professors (codi_professor, nom, cognoms, carrec) VALUES (:codi_professor, :nom, :cognoms, :carrec)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':codi_professor', $codi_professor);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':cognoms', $cognoms);
    $stmt->bindParam(':carrec', $carrec);

    try {
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No se han rebut les dades esperades.']);
}

?>