<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si se está realizando una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos de la solicitud POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si los datos son válidos
    if ($data === null) {
        echo json_encode(['success' => false, 'message' => 'JSON inválido']);
        exit;
    }

    // Incluir el archivo con la conexión a la base de datos y la clase Horario
    require_once('../../models/database.php');
    require_once('../../models/horario.php');

    // Crear una instancia de la clase Database
    $database = new Database();
    $db = $database->getConnection();

    // Crear una instancia de la clase Horario
    $horario = new Horario($db);

    // Guardar todas las ausencias en la base de datos
    $errores = [];
    foreach ($data as $item) {
        $diaId = $item['diaId'];
        $hora = $item['hora'];
        $professorId = $item['professorId'];
        if (!$horario->guardarAbsencia($diaId, $hora, $professorId)) {
            $errores[] = 'Error al guardar la ausencia para el profesor con ID ' . $professorId;
        }
    }

    if (empty($errores)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errores)]);
    }
} else {
    // Si no es una solicitud POST, devolvemos un mensaje de error
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}
?>
