<?php
session_start();
require_once 'utils.php';
require_once 'funciones.php';

$run_paciente = $_POST['RUN_paciente'] ?? '';
$diagnostico = $_POST['diagnostico'] ?? '';

if (!validar_run($run_paciente)) {
    header('Location: main_medico.php?error=RUN invalido');
    exit();
}
if ($diagnostico == '') {
    header('Location: main_medico.php?error=Diagnostico vacio');
    exit();
}

$bdd = conectarBD();
//ver la ficha del paciente:
$query = 'SELECT * FROM Ficha WHERE Ficha."RUN" ILIKE :run_paciente AND Ficha.doc_id = :id_doctor
AND Ficha."Efectuada" = TRUE ORDER BY Ficha."Fecha" DESC LIMIT 1';
$stmt = $bdd->prepare($query);
$stmt->bindParam(':run_paciente', $run_paciente);
$stmt->bindParam(':id_doctor', $_SESSION['usuario']);
$stmt->execute();
$ficha = $stmt->fetch();
if (!$ficha) {
    header('Location: main_medico.php?error=No existe ficha para el paciente');
    exit();
}

//poner el diagnost.
$query_diag = 'UPDATE atencion SET "Diagnostico" = :diagnostico WHERE atencion."IDPaciente" = :id_paciente
AND atencion."IDMedico" = :id_doctor AND atencion."fecha" = :fecha';
try {
    $bdd->beginTransaction();
    $stmt = $bdd->prepare($query_diag);
    $stmt->bindParam(':diagnostico', $diagnostico);
    $stmt->bindParam(':id_paciente', $ficha['id_paciente']);
    $stmt->bindParam(':id_doctor', $_SESSION['usuario']);
    $stmt->bindParam(':fecha', $ficha['fecha']);
    $stmt->execute();
    $bdd->commit();
    $_SESSION['diagnosticado'] = TRUE;
    header('Location: main_medico.php?success=Diagnosticado');
    exit();
} 
catch (Exception $e) {
    $bdd->rollBack();
    exit();
}