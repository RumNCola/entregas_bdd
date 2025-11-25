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
        <h1>Ingrese RUN paciente para acceder a su ficha: </h1>
        <form action="ficha_paciente.php" method="POST">
            <input type="text" id="RUN_paciente" name="RUN_paciente" required>
            <button type="submit">Mostrar Ficha</button>
        </form>
    </div>
</body>
</html>
