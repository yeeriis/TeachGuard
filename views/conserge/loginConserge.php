<div class="loginDiv">
    <h2 class="titolLogin">Inici de Sessió del Conserge</h2>
    <form action="index.php?controller=Login&action=autenticarConserge" method="post" class="formContainer">
        <div class="inputContainer">
            <label for="nombre" id="labelNombre">Usuari:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="inputContainer">
            <label for="contrasena" id="labelContrasena">Contrasenya:</label>
            <input type="password" id="contrasena" name="contrasena" required><br><br>
        </div>
        <input type="submit" class="submitBoton" value="Accedir">
    </form>
</div>