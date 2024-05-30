<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../../models/database.php');
    require_once('../../models/horario.php');

    $database = new Database();
    $db = $database->getConnection();

    $horario = new Horario();

    try {
        if ($horario->eliminarTodasAusencias()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar les ausències']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Mètode de solicitud no vàlid.']);
}
?>
