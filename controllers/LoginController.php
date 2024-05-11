<?php

require "models/usuario.php";

class LoginController
{

    // Funciones para el login de Admin
    public function mostrarLoginAdmin()
    {
        require "views/menu.php";
        require_once "views/admin/loginAdmin.php";
    }

    public function autenticarAdmin() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $contrasena = $_POST["contrasena"];
    
            $usuario = Usuario::obtenerUsuarioPorNombre($nombre);
    
            if ($usuario && $usuario["contrasena"] === $contrasena) {
                if ($usuario["rol"] === "administrador") {
                    $_SESSION["nombre"] = $nombre;
                    echo "Redirigint...";
                    echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Login&action=mostrarMainAdmin'>";
                    exit();
                } else {
                    echo "No tens permisos per accedir a aquesta àrea.";
                    echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Login&action=mostrarLoginAdmin'>";

                }
            } else {
                echo "Error de logueig";
                echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Login&action=mostrarLoginAdmin'>";
            }
            
        }
    }

    public function mostrarMainAdmin()
    {
        require_once "views/menuUsuario.php";
        require_once "views/admin/main.php";
    }

    // Funciones para el login del conserge
    
    public function mostrarLoginConserge()
    {
        require "views/menu.php";
        require_once "views/conserge/loginConserge.php";
    }

    public function autenticarConserge() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $contrasena = $_POST["contrasena"];
    
            $usuario = Usuario::obtenerUsuarioPorNombre($nombre);
    
            if ($usuario && $usuario["contrasena"] === $contrasena) {
                if ($usuario["rol"] === "conserge") {
                    $_SESSION["nombre"] = $nombre;
                    echo "Redirigint...";
                    echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Login&action=mostrarMainConserge'>";
                    exit();
                } else {
                    echo "No tens permisos per accedir a aquesta àrea.";
                    echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Login&action=mostrarLoginConserge'>";

                }
            } else {
                echo "Error de logueig";
                echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Login&action=mostrarLoginConserge'>";
            }
            
        }
    }

    public function mostrarMainConserge()
    {
        require_once "views/menuUsuario.php";
        require_once "views/conserge/main.php";
    }

    public function cerrarSesion() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
            echo "Cerrando sesión...";
        } else {
            echo "La sesión no está iniciada.";
        }
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='1;URL=index.php'>";
    }

    
}