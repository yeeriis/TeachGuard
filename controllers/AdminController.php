<?php

require "models/horario.php";

class AdminController
{
    // Funció per a comprovar si el rol de l'usuari es administrador
    private function autenticarAdmin()
    {
        if (!isset($_SESSION["nombre"]) || (isset($_SESSION["rol"]) && $_SESSION["rol"] !== "administrador")) {
            echo "No tienes permisos para acceder a esta área.";
            echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Login&action=mostrarLoginAdmin'>";
            exit();
        }
    }

    // INICI Funcions per a mostrar les diferents vistes

    public function mostrarFormAules()
    {
        $this->autenticarAdmin();
        require "views/menuUsuario.php";
        require_once "views/admin/aules.php";
    }

    public function mostrarFormHoraris()
    {
        $this->autenticarAdmin();
        require "views/menuUsuario.php";
        require_once "views/admin/horaris.php";
    }

    public function mostrarFormProfessors()
    {
        $this->autenticarAdmin();
        require "views/menuUsuario.php";
        require_once "views/admin/professors.php";
    }

    public function mostrarGestioGuardies()
    {
        $this->autenticarAdmin();
        $horario = new Horario();
        $horas = $horario->obtenirHores();
        $todosProfesores = $horario->obtenerProfesores();
        require "views/menuUsuario.php";
        require_once "views/admin/gestioGuardies.php";
    }

    // FI Funcions per a mostrar les diferents vistes

    // Funció per a generar PDFs de la taula de guardies

    public function generarPDF()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = json_decode(file_get_contents('php://input'), true);
            $html = $data['html'];

            // Incluye el autoload de Composer
            require 'vendor/autoload.php';

            // Crea un nuevo documento PDF
            $pdf = new TCPDF();
            $pdf->AddPage();

            // Configura el contenido HTML
            $pdf->writeHTML($html, true, false, true, false, '');

            // Genera el PDF y fuerza la descarga
            $pdfOutput = $pdf->Output('gestioGuardies.pdf', 'S');

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="gestioGuardies.pdf"');
            echo $pdfOutput;
        }
    }

}