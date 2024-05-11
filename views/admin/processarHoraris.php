<?php

// Verificar si se recibieron los datos esperados
if (isset($_POST['codi_horari']) && isset($_POST['classe']) && isset($_POST['professor']) && isset($_POST['asignatura']) && isset($_POST['aula']) && isset($_POST['dia']) && isset($_POST['hora'])) {
    // Obtener los datos del POST
    $codi_horari = $_POST['codi_horari'];
    $classe = $_POST['classe'];
    $professor = $_POST['professor'];
    $asignatura = $_POST['asignatura'];
    $aula = $_POST['aula'];
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];

    // Incluir el archivo de conexión a la base de datos
    require_once('../../models/database.php');

    // Crear una instancia de la clase Database
    $database = new Database();

    // Obtener la conexión a la base de datos
    $conn = $database->getConnection();

    // Preparar la consulta SQL para la inserción de datos
    $sql = "INSERT INTO horaris (codi_horari, classe, professor, asignatura, aula, dia, hora) VALUES (:codi_horari, :classe, :professor, :asignatura, :aula, :dia, :hora)";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros con los valores recibidos del formulario
    $stmt->bindParam(':codi_horari', $codi_horari);
    $stmt->bindParam(':classe', $classe);
    $stmt->bindParam(':professor', $professor);
    $stmt->bindParam(':asignatura', $asignatura);
    $stmt->bindParam(':aula', $aula);
    $stmt->bindParam(':dia', $dia);
    $stmt->bindParam(':hora', $hora);

    // Ejecutar la consulta
    try {
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
