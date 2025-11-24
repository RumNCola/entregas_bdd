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
        <h1>Buscar paciente por RUN</h1>
        <form action="buscar_paciente.php" method="POST">
            <label for="RUN_paciente">RUN: </label>
            <input type="text" id="RUN_paciente" name="RUN_paciente" required>
            <button type="submit">Buscar paciente</button>
        <h3>Ingrese datos del médico</h3>
            <label for="" > </label>

        </form>
        <?php if ($error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
