<?php

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
}