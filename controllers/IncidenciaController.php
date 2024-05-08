<?php
require "models/incidencia.php";


class IncidenciaController
{
    public function mostrarDatosInformatica()
    {
        // require_once "views/menu.php";
        $incidencia = new Incidencia();
        $incidencias = $incidencia->obtenerListado();
        require_once "views/informatica/tablaIncidencias.php";
    }

    public function editarIncidencia()
    {
        // require_once "views/menu.php";
        $incidencia = new Incidencia();
        $nick = $_SESSION['usuario'];
        require_once "views/informatica/editarIncidencia.php";
    }

    public function actualizarEstado()
    {
        // require_once "views/menu.php";
        $incidencia = new Incidencia();
        $id_incidencia = $_POST['id'];
        $estado = $_POST['estado'];
        $incidencia->editar($id_incidencia, $estado);
        echo "Redirigiendo a su panel";
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Incidencia&action=mostrarDatosInformatica'>";
    }


    public function crearIncidencia()
    {
        // require_once "views/menu.php";
        $incidencia = new Incidencia();
        $nick = $_SESSION['usuario'];
        require_once "views/contabilidad/formIncidencia.php";
    }

    public function nuevaIncidencia()
    {
        // require_once "views/menu.php";

        $incidencia = new Incidencia();
        $nick = $_SESSION['usuario'];
        $descripcion = $_POST['descripcion'];
        $tipo = $_POST['tipo'];


        $incidencia->anadirIncidencia($nick, $descripcion, $tipo);
        echo "Redirigiendo a su panel";
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Contabilidad&action=mostrarDatosContabilidad'>";
    }

    // function usuariIncorrecte()
    // {
    //     echo "Error! Has de iniciar sesión para ver esto.";
    //     echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php'>";
    // }
}