<?php
require "models/usuario.php";
require "models/horario.php";


class PrincipalController
{
    public function mostrarPaginaPrincipal()
    {
        $horario = new Horario();
        $horas = $horario->obtenirHores();
        require_once "views/menu.php";
        require_once "views/tablaGuardias.php";
    }

}