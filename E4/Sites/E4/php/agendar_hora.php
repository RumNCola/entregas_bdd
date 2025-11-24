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
        </form>
        <!-- Si buscar_paciente.php  dice que el paciente no existe, desplegamos el 
         formulario para ingresar los datos del paciente y crearlo -->
        <h2>Paciente no existe? Ingreselo!</h2>
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


        <?php if ($error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
