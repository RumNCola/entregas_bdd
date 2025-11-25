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
    <title>ADMINISTRADOR: Menu de ATENCION MEDICA</title>
</head>
<body>
    <div>
        <?php 
        if (!isset($_SESSION['RUN_paciente'])): ?>
            <h1>Buscar paciente por RUN: </h1>
            <form action="buscar_paciente_atencion.php" method="POST">
                <label for="RUN_paciente">RUN: </label>
                <input type="text" id="RUN_paciente" name="RUN_paciente" required>
                <button type="submit">Buscar paciente</button>
            </form>

        <?php elseif (isset($_SESSION['datos_paciente'])): ?>
        <h2>Información del paciente y su atencion más reciente: </h2>
            <?php print_r($_SESSION['datos_paciente']); ?>
        <?php elseif (isset($_SESSION['bono'])): ?>
        <h2> Bono emitido: </h2>
            <?php print_r($_SESSION['bono']); ?>
        <?php endif; ?>
        <?php if ($error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>