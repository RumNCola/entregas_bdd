<?php
session_start();
if (!isset($_SESSION['usuario'])){
    header('Location: index.php?error=No se ha iniciado sesion');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ADMINISTRADOR: Menu para agendar hora</title>
</head>
<body>
    <div>
        <h1>Ingrese fecha y hora para agendar</h1>
        <form action="" method="POST">
            <label for="fecha">Fecha:</label>
            <input type="text" id="fecha" name="fecha" required>

            <label for="hora">Hora:</label>
            <input type="text" id="hora" name="hora" required>
        </form>
        <h2>Ingrese RUN paciente</h2>
        <form action="" method="POST">
            <label for="RUN_paciente">RUN: </label>
            <input type="text" id="RUN_paciente" name="RUN_paciente" required>
        </form>

        <?php if ($error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
