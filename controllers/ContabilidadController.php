<?php
require "models/incidencia.php";


class ContabilidadController
{
    public function mostrarDatosContabilidad() 
    {
        $usuario = $_SESSION['usuario'];

        // require_once "views/menu.php";
        $incidencia = new Incidencia();
        $incidencias = $incidencia->obtenerListadoContabilidad($usuario);
        require_once "views/contabilidad/tablaContabilidad.php";
    }

    public function editarIncidencia()
    {
        // require_once "views/menu.php";
        require_once "views/informatica/editarIncidencia.php";
    }

    public function actualizarEstado(){
        // require_once "views/menu.php";
        $incidencia = new Incidencia();
        $id_incidencia = $_POST['id'];
        $estado = $_POST['estado'];
        $incidencia->editar($id_incidencia, $estado);
        header("Location: index.php?controller=Incidencia&action=mostrarDatosInformatica");
            
    }
}