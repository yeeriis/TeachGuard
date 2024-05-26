<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data === null) {
        echo json_encode(['success' => false, 'message' => 'JSON invàlid']);
        exit;
    }

    require_once('../../models/database.php');
    require_once('../../models/horario.php');

    $database = new Database();
    $db = $database->getConnection();

    $horario = new Horario($db);

    $errores = [];
    $duplicados = [];
    foreach ($data as $item) {
        $diaId = $item['diaId'];
        $hora = $item['hora'];
        $professorId = $item['professorId'];
        if (!$horario->guardarAbsencia($diaId, $hora, $professorId)) {
            // Comprobar si la ausencia ya existe
            if ($horario->ausenciaExiste($diaId, $hora, $professorId)) {
                $duplicados[] = 'L\'absència ja existeix per al professor amb ID ' . $professorId;
            } else {
                $errores[] = 'Error al desar la absència per al professor amb ID ' . $professorId;
            }
        }
    }

    if (empty($errores) && empty($duplicados)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', array_merge($errores, $duplicados))]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Mètode de solicitud no vàlid']);
}
?>
