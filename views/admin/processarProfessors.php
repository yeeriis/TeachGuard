<?php

// Verificar si se recibieron los datos esperados
if (isset($_POST['codi_professor']) && isset($_POST['nom']) && isset($_POST['cognoms']) && isset($_POST['carrec'])) {
    // Obtener los datos del POST
    $codi_professor = $_POST['codi_professor'];
    $nom = $_POST['nom'];
    $cognoms = $_POST['cognoms'];
    $carrec = $_POST['carrec'];

    // Incluir el archivo de conexión a la base de datos
    require_once('../../models/database.php');

    // Crear una instancia de la clase Database
    $database = new Database();

    // Obtener la conexión a la base de datos
    $conn = $database->getConnection();

    // Preparar la consulta SQL para la inserción de datos
    $sql = "INSERT INTO professors (codi_professor, nom, cognoms, carrec) VALUES (:codi_professor, :nom, :cognoms, :carrec)";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros con los valores recibidos del formulario
    $stmt->bindParam(':codi_professor', $codi_professor);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':cognoms', $cognoms);
    $stmt->bindParam(':carrec', $carrec);

    // Intentar ejecutar la consulta
    try {
        // Ejecutar la consulta
        $stmt->execute();

        // Devolver una respuesta JSON indicando éxito
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // En caso de error, devolver una respuesta JSON indicando el error
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Si no se recibieron los datos esperados, devolver un mensaje de error en formato JSON
    echo json_encode(['success' => false, 'error' => 'No se recibieron los datos esperados.']);
}

?>
