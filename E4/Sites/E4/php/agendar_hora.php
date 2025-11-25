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
        if (!isset($_GET['RUN_Paciente']) && !isset($_GET['fallo_paciente'])): ?>
            <h1>Buscar paciente por RUN</h1>
            <form action="buscar_paciente.php" method="POST">
                <label for="RUN_paciente">RUN: </label>
                <input type="text" id="RUN_paciente" name="RUN_paciente" required>
                <button type="submit">Buscar paciente</button>
            </form>
        <!-- Si buscar_paciente.php  dice que el paciente no existe, desplegamos el 
         formulario para ingresar los datos del paciente y crearlo -->
        <?php elseif (isset($_GET['fallo_paciente'])): ?>
        <h2>Paciente no encontrado. Ingreselo!</h2>
        <form action="buscar_paciente.php" method="POST">

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

            <label type="text" id="beneficiario?" name="beneficiario?">Es beneficiario? (1=si, 0=no): </label>
            <input type="text" id="beneficiario?" name="beneficiario?" required>

            <label type="text" id="firma" name="firma">Firma: </label>
            <input type="text" id="firma" name="firma" required>

            <label type="text" id="profesion" name="profesion">Profesion: </label>
            <input type="text" id="profesion" name="profesion" required>


            <button type="submit">Crear Paciente</button>
        </form>
        <?php elseif (isset($_GET['RUN_paciente'])): ?>
            <h2> Paciente encontrado! Seleccione doctor o especialidad </h2>
            <form action="agendar_hora_especialidad.php" method="POST">
                <label for="doctor">Doctor deseado: </label>
                <input type="text" id="doctor" name="doctor">

                <label for="especialidad">Especialidad deseada: </label>
                <input type="text" id="especialidad" name="especialidad">

                <button type="submit">Confirmar especialista</button>
            </form>
        <?php elseif (): ?>

        <?php endif; ?>

        <?php if ($error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>