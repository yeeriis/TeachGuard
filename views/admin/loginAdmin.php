<h2>Login</h2>
<form action="index.php?controller=Login&action=autenticarAdmin" method="post">
    <label for="nombre">Nombre de usuario:</label>
    <input type="text" id="nombre" name="nombre" required><br><br>
    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="contrasena" required><br><br>
    <input type="submit" value="Iniciar sesión">
</form>