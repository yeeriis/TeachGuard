<!DOCTYPE html>
<html lang="en">

<?php
session_start();
?>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Links per als Toast (missatges emergents) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- FI de Links per als Toast (missatges emergents) -->

    <script src="script-aules.js"></script>
    <script src="script-professors.js"></script>
    <script src="script-horaris.js"></script>
    <script src="script-guardies.js"></script>
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>TeachGuard</title>
</head>

<body>
    <?php

    require_once "autoload.php";
    require 'vendor/autoload.php';


    $_SESSION['seccion'] = "nada";

    if (isset($_GET['controller'])) {
        $nombreController = $_GET['controller'] . "Controller";
    } else {
        $nombreController = "PrincipalController";
    }
    if (class_exists($nombreController)) {
        $controlador = new $nombreController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = "mostrarPaginaPrincipal";

        }
        $controlador->$action();

    } else {
        echo "No existeix el controlador";
    }

    // Funcions per a tractar els arxius del Drag&Drop
    
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';

    switch ($action) {
        case 'uploadTxtFile':
            $controller = new AdminController();
            $controller->uploadTxtFile($conn);
            break;
    }
    ?>

</body>

</html>