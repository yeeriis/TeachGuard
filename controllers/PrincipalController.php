<?php
require "models/usuario.php";
require "models/horario.php";


class PrincipalController
{
    // Funciones de mostrar
    public function mostrarPaginaPrincipal()
    {
        $horario = new Horario();
        $horas = $horario->obtenirHores();
        // $todosProfesores = $horario->obtenerProfesores();
        require_once "views/menu.php";
        require_once "views/tablaGuardias.php";
    }

}