<?php
// código extraido de la ayudantía 12!!!
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
    
    <div>
        <h1> Ingrese su nombre de usuario (ID) y contraseña (RUN sin el dv)</h1>
        <form action="validar_login.php" method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="contrasena">Contrasena:</label>
            <input type="text" id="contrasena" name="contrasena" required>
            
            <button>Iniciar Sesion</button>
        </form>

        <?php if ($error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>