<?php
require "models/usuario.php";


class PrincipalController
{
    // Funciones de mostrar
    public function mostrarPaginaPrincipal()
    {
        require_once "views/menu.php";
        require_once "views/tablaGuardias.php";
    }

}