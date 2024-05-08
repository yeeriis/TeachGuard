<!DOCTYPE html>
<html lang="en">

<?php
    session_start();
?>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>TeachGuard</title>
</head>
<body>
   
    <script src="script.js"></script>
    <?php
   
    require_once "autoload.php";

    $_SESSION['seccion'] = "nada";
   
    if (isset($_GET['controller'])) {
        $nombreController = $_GET['controller'] . "Controller";
    } else {
        //Controlador per dedecte
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
        echo "No existe el controlador";
    }
    ?>
</body>


</html>
