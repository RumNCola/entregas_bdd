<?php
session_start();
require_once 'utils.php';
require_once 'funciones.php';

$run_paciente = $_POST['RUN_paciente'] ?? '';

// Primero a validar el rut.
if (!validar_run($run_paciente)) {
    header('Location: cancelar_atencion.php?error=RUN de paciente no tiene formato válido');
    exit();
}

$bdd = conectarBD();

// A ver la ficha del paciente
$query = 'SELECT * FROM Ficha WHERE Ficha."RUN" ILIKE :run_paciente';
$stmt = $bdd->prepare($query);
$stmt->bindParam(':run_paciente', $run_paciente, PDO::PARAM_STR);
$stmt -> execute();
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    header('Location: cancelar_atencion.php?error=No existen atenciones para ese RUN');
    exit();
}
//Ahora, se ha verificado la infomración del paciente y existe. Volvemos a cancelar_atencion.php, mostramos
//la infomracion de la persona y le desplegamos el formualario para que seleccione un médico.
$_SESSION['RUN_paciente'] = $run_paciente;
$_SESSION['ID_paciente'] = $paciente['ID'];
$_SESSION['atenciones'] =  $stmt->fetchAll(PDO:: FETCH_ASSOC);
header('Location: cancelar_atencion.php?succes=Paciente existe - Seleccione médico y/o especialidad');
exit();
?>