<?php

// Verificar si se recibieron los datos esperados
if (isset($_POST['nombre_aula']) && isset($_POST['nombre_curso'])) {
    // Obtener los datos del POST
    $nombre_aula = $_POST['nombre_aula'];
    $nombre_curso = $_POST['nombre_curso'];

    // Incluir el archivo de conexión a la base de datos
    require_once('../../models/database.php');

    // Crear una instancia de la clase Database
    $database = new Database();

    // Obtener la conexión a la base de datos
    $conn = $database->getConnection();

    // Preparar la consulta SQL para la inserción de datos
    $sql = "INSERT INTO aules (nom_aula, nom_curs) VALUES (:nom_aula, :nom_curso)";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros con los valores recibidos del formulario
    $stmt->bindParam(':nom_aula', $nombre_aula);
    $stmt->bindParam(':nom_curso', $nombre_curso);

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
