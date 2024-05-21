<?php

require "models/horario.php";

class AdminController
{
   public function mostrarFormAules()
    {
        require "views/menuUsuario.php";
        require_once "views/admin/aules.php";
    }

    public function mostrarFormHoraris()
    {
        require "views/menuUsuario.php";
        require_once "views/admin/horaris.php";
    }

    public function mostrarFormProfessors()
    {
        require "views/menuUsuario.php";
        require_once "views/admin/professors.php";
    }

    public function mostrarGestioGuardies()
    {
        $horario = new Horario();
        $horas = $horario->obtenirHores();
        $todosProfesores = $horario->obtenerProfesores();
        require "views/menuUsuario.php";
        require_once "views/admin/gestioGuardies.php";
    }
}