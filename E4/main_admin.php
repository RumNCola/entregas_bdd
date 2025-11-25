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
    <title>Menu Principal ADMINISTRADOR</title>
</head>
<body>
    <div>
        <h1>Seleccione una de las tres opciones (submenus) </h1>
        <form action="agendar_hora.php" method="POST">
            <button type="submit">Agendar hora medica</button>
        </form>
        <form action="atencion_medica.php" method="POST">
            <button type="submit">Atencion médica</button>
        </form>
        <form action="cancelar_atencion.php" method="POST">
            <button type="submit">Cancelar atención médica</button>
        </form>
    </div>
</body>
</html>