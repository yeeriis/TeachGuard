<?php

require "models/usuario.php";

class LoginController
{

    // Funció per mostrar la vista del loging de l'admin
    public function mostrarLoginAdmin()
    {
        require "views/menu.php";
        require_once "views/admin/loginAdmin.php";
    }

    // Funció per a autenticar l'administrador
    public function autenticarAdmin() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $contrasena = $_POST["contrasena"];
    
            $usuario = Usuario::obtenerUsuarioPorNombre($nombre);
    
            if ($usuario && $usuario["contrasena"] === $contrasena) {
                $_SESSION["nombre"] = $nombre;
                $_SESSION["rol"] = $usuario["rol"];
                echo "Redirigint...";
                echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Login&action=mostrarMainAdmin'>";
                exit();
            } else {
                echo "Error d'inici de sessió";
                echo "<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=index.php?controller=Login&action=mostrarLoginAdmin'>";
            }
        }
    }
    
    // Funció per a mostrar la plana principal de l'administrador.
    public function mostrarMainAdmin()
    {
        $this->autenticarAdmin();
        require_once "views/menuUsuario.php";
        require_once "views/admin/main.php";
    }

    // Funció per a mostrar el login del conserge
    
    public function mostrarLoginConserge()
    {
        require "views/menu.php";
        require_once "views/conserge/loginConserge.php";
    }

    // Funció per a autenticar al conserge

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

    // Funció per a mostrar la pagina principal del conserge
    public function mostrarMainConserge()
    {
        require_once "views/menuUsuario.php";
        require_once "views/conserge/main.php";
    }

    // Funció per a tancar la sessió

    public function cerrarSesion() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
            echo "Tancant Sessió...";
        } else {
            echo "La sessió no està iniciada.";
        }
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='1;URL=index.php'>";
    }   
}