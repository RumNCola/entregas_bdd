<?php
session_start();
require_once 'utils.php';
require_once 'funciones.php';

$id_hora_cancelar = $_POST['id_hora_cancelar'] ?? '';
if ($id_hora_cancelar == '' || !is_numeric($id_hora_cancelar)){
    header('Location: cancelar_atencion.php?error=ID hora atencion no válido');
    exit();
}
$run_paciente = $_SESSION['RUN_paciente'];

$bdd = conectarBD();

try {
    //transacion
    $bdd->beginTransaction();
    $query = 'DELETE FROM atencion WHERE atencion."ID" = :id_hora_cancelar';
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':id_hora_cancelar', $id_hora_cancelar, PDO::PARAM_INT);
    $stmt->execute();
    $bdd->commit();
    header('Location: main_admin.php?success=Atencion cancelada!');
    exit();
}
catch (Exception $e) {
    $bdd->rollBack();
    header('Location: cancelar_atencion.php?error=Error al cancelar atencion ' . $e->getMessage());
    exit();
}

