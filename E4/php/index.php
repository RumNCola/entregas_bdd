<?php
session_start();
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="UTF-8">
    <title> Inicie sesión </title>
</head>
<body>
    <div class="container">
        <h1> Ingrese su nombre de usuario (ID) y contraseña </h1>
        <form action="revisar_login.php" method="POST" class="formulario">
            <label for="Usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="contrasena">Contrasena:</label>
            <input type="text" id="contrasena" name="contrasena" required>
            <button type="submit">Iniciar Sesion</button>
        </form>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>