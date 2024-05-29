<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0); // Desactivar la visualización de errores en producción

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data === null) {
        echo json_encode(['success' => false, 'message' => 'JSON inválido']);
        exit;
    }

    require_once('../../models/database.php');
    require_once('../../models/horario.php');

    $database = new Database();
    $db = $database->getConnection();

    $horario = new Horario();

    $errores = [];
    foreach ($data as $item) {
        try {
            $diaId = $item['diaId'];
            $hora = $item['hora'];
            $professorId = $item['professorId'];

            if (!$horario->guardarAbsencia($diaId, $hora, $professorId)) {
                throw new Exception('Error al guardar la ausencia para el profesor con ID ' . $professorId);
            }
        } catch (Exception $e) {
            $errores[] = $e->getMessage();
        }
    }

    if (empty($errores)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errores)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}
?>
