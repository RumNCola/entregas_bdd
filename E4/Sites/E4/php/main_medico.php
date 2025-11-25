<?php
session_start();
if (!isset($_SESSION['usuario'])){
    header('Location: index.php?error=No se ha iniciado sesion');
    exit();
}
?> 
 <!-- Lo de arriba lo extraje de la entrega 3 del semestre pasado -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menu/Formulario Medico </title>
</head>
<body>
    <div>
        <h1>Ingrese RUN paciente para acceder a su ficha y su diagnostico: </h1>
        <form action="ficha_paciente.php" method="POST">
            <label for="RUN_paciente">RUN: </label>
            <input type="text" id="RUN_paciente" name="RUN_paciente" required>

            <label for="diagnostico">Diagnostico: </label>
            <input type="text" id="diagnostico" name="diagnostico" required>
            
            <button type="submit">Ingresar Diagnostico</button>
        </form>
        <?php if (isset($_SESSION['diagnosticado'])): ?>
            <h1> Ingrese medicamentos a ingresar </h1>
            <form action="ingresar_medicamentos_ordenes.php" method="POST">
                <
    </div>
</body>
</html>
