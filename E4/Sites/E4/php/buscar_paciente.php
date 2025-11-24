<!-- Codigo iniciado tras buscar paciente en agendar_hora.php -->
<?php
session_start();
require_once 'utils.php';

$run_paciente = $_POST['RUN_paciente'] ?? '';

// Primero a validar el rut.
if (!validar_rut($run_paciente)) {
    header('Location: agendar_hora.php?error=RUN de paciente no tiene formato válido');
    exit();
}




?>