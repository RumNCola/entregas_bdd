<?php
session_start();
require_once 'utils.php';
require_once 'funciones.php';

$run_paciente = $_POST['RUN_paciente'] ?? '';

// Primero a validar el rut.
if (!validar_run($run_paciente)) {
    header('Location: agendar_hora.php?error=RUN de paciente no tiene formato válido');
    exit();
}

$bdd = conectarBD();

// A ver si existe el paciente
$query = 'SELECT * FROM persona LEFT JOIN rol ON rol."IDPersona" = persona."ID" LEFT JOIN beneficiario
ON beneficiario."IDpersona" = persona."ID" WHERE persona."RUN" ILIKE :run_paciente';
$stmt = $bdd->prepare($query);
$stmt->bindParam(':run_paciente', $run_paciente, PDO::PARAM_STR);
$stmt -> execute();
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    $_SESSION['fallo_paciente'] = true;
    header('Location: agendar_hora.php?error=No existe paciente con RUN - Se desplega formuario para ingresar nuevo paciente');
    exit();
}
//Ahora, se revisa que el paciente tenga el rol paciente con el SP que cree en main.sql
try {
    $query_rol = 'CALL actualizar_rol_paciente(:run_paciente);';
    $bdd->beginTransaction();
    $stmt = $bdd->prepare($query_rol);
    $stmt->bindParam(':run_paciente', $run_paciente, PDO::PARAM_STR);
    $stmt->execute();
    $bdd->commit();
} 
catch (Exception $e) {
    $bdd->rollBack();
    exit();
}

//Ahora, se ha verificado la infomración del paciente y existe. Volvemos a agendar_hora.php, mostramos
//la infomracion de la persona y le desplegamos el formualario para que seleccione un médico.
$_SESSION['RUN_paciente'] = $run_paciente;
$_SESSION['ID_paciente'] = $paciente['ID'];
$_SESSION['datos_paciente'] = $paciente;
header('Location: agendar_hora.php?succes=Paciente existe - Seleccione médico y/o especialidad');
exit();
?>