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
    <title>ADMINISTRADOR: Menu para Cancelar Atención Médica</title>
</head>
<body>
    <div>
        <?php 
        if (!isset($_SESSION['RUN_paciente'])): ?>
            <h1>Buscar paciente por RUN</h1>
            <form action="buscar_paciente_cancelar.php" method="POST">
                <label for="RUN_paciente">RUN: </label>
                <input type="text" id="RUN_paciente" name="RUN_paciente" required>
                <button type="submit">Buscar paciente</button>
            </form>
        <!-- Si buscar_paciente.php  dice que el paciente no existe, desplegamos el 
         formulario para ingresar los datos del paciente y crearlo -->
        <?php elseif (isset($_SESSION['RUN_paciente'])) :?>
            <!-- (isset($_GET['RUN_paciente'])) al final me quede con else, guardo esto aqui por si me arrepiento. -->
            <h2> Paciente encontrado! Seleccione un ID de atencion para cancelar</h2> 
            <?php print_r($_SESSION['atenciones']); ?>
            <form action="eliminar_hora.php" method="POST">
                <label for="id_hora_cancelar">ID hora atencion a cancelar: </label>
                <input type="text" id="id_hora_cancelar" name="id_hora_cancelar" required>
                <button type="submit">Cancelar atención</button>
            </form>
        <?php endif; ?>
        <?php if ($error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>