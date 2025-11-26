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
        <?php 
        if (!isset($_SESSION['RUN_paciente']) && !isset($_SESSION['fallo_paciente'])): ?>
            <h1>Buscar paciente por RUN</h1>
            <form action="buscar_paciente.php" method="POST">
                <label for="RUN_paciente">RUN: </label>
                <input type="text" id="RUN_paciente" name="RUN_paciente" required>
                <button type="submit">Buscar paciente</button>
            </form>
        <!-- Si buscar_paciente.php  dice que el paciente no existe, desplegamos el 
         formulario para ingresar los datos del paciente y crearlo -->
        <?php elseif (isset($_SESSION['fallo_paciente'])): ?>
        <h2>Paciente no encontrado. Ingreselo!</h2>
        <form action="crear_paciente.php" method="POST">

            <label for="RUN_paciente">RUN: </label>
            <input type="text" id="RUN_paciente" name="RUN_paciente" required>

            <label for="nombres">Nombres: </label>
            <input type="text" id="nombres" name="nombres" required>

            <label for="apellidos">Apellidos: </label>
            <input type="text" id="apellidos" name="apellidos" required>

            <label for="direccion">Direccion: </label>
            <input type="text" id="direccion" name="direccion" required>

            <label for="telefono">Telefono: </label>
            <input type="text" id="telefono" name="telefono" required>

            <label for="InstSalud">Institucion de Salud: </label>
            <input type="text" id="InstSalud" name="InstSalud" required>

            <label type="text" id="IDtitular" name="IDtitular">ID Titular: </label>
            <input type="text" id="IDtitular" name="IDtitular" required>

            <label type="text" id="beneficiario?" name="beneficiario?">Es beneficiario? (TRUE o FALSE): </label>
            <input type="text" id="beneficiario?" name="beneficiario?" required>

            <label type="text" id="rol" name="rol">Rol: </label>
            <input type="text" id="rol" name="rol" required>


            <button type="submit">Crear Paciente</button>
        </form>
        <?php elseif (isset($_SESSION['RUN_paciente'])) :?>
        <h2> Paciente existe, Su información: </h2> 
            <?php print_r($_SESSION['datos_paciente']); ?>
        <?php else: ?> 
            <!-- (isset($_GET['RUN_paciente'])) al final me quede con else, guardo esto aqui por si me arrepiento. -->
            <h2> Paciente encontrado! Seleccione doctor o especialidad </h2> 
            <form action="agendar_hora_especialidad.php" method="POST">
                <label for="doctor">Doctor deseado: </label>
                <input type="text" id="doctor" name="doctor">

                <label for="especialidad">Especialidad deseada: </label>
                <input type="text" id="especialidad" name="especialidad">

                <button type="submit">Confirmar especialista</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>