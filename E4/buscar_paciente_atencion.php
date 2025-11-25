<?php
session_start();
require_once 'utils.php';
require_once 'funciones.php';

$run_paciente = $_POST['RUN_paciente'] ?? '';

// Primero a validar el rut.
if (!validar_run($run_paciente)) {
    header('Location: atencion_medica.php?error=RUN de paciente no tiene formato válido');
    exit();
}

$bdd = conectarBD();

// A ver la ficha del paciente
//notar que si no es paciente, no puede salir en ficha, por lo que omito ese chequeo. Además,
// la ficha entrega las atenciones ordenadas y muestra primero las mas recientes, asi que toma esa altiro,
// que es la que nos interesa.
$query = 'SELECT * FROM Ficha WHERE Ficha."RUN" ILIKE :run_paciente AND Ficha."Efectuada" = False LIMIT 1';
$stmt = $bdd->prepare($query);
$stmt->bindParam(':run_paciente', $run_paciente, PDO::PARAM_STR);
$stmt -> execute();
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

$id_atencion = $paciente['id_atencion'];
$id_doctor = $paciente['doc_id'];
$id_paciente = $paciente['id_paciente'];

// a marcar la atencion como efectuada, obtener toda la info del paciente a mostrar y el bono.
$query = 'UPDATE atencion SET atencion."Efectuada" = True WHERE atencion."ID" = :id_atencion';
$query_datos = 'SELECT * FROM Ficha LEFT JOIN persona ON Ficha."id_paciente" = persona."ID"
LEFT JOIN rol ON rol."IDPersona" = Ficha."id_paciente" LEFT JOIN beneficiario ON beneficiario."IDPersona"
= Ficha."id_paciente" WHERE Ficha."id_atencion" = :id_atencion';
$query_bono = 'SELECT * FROM emitir_bono(:id_atencion, :id_paciente, :id_doctor) AS bono;';

try{
    $bdd->beginTransaction();
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':id_atencion', $id_atencion);
    $stmt->execute();
    
    //las siguientes dos queries no hacen modificaciones por lo que tambien podrían estar fuera de
    // la transacción, la verdad es irrelevante si estan o no dentro.
    $stmt_datos = $bdd->prepare($query_datos);
    $stmt_datos->bindParam(':id_atencion', $id_atencion);
    $stmt_datos->execute();
    $_SESSION['datos_paciente'] = $stmt_datos->fetch(PDO::FETCH_ASSOC);

    $stmt_bono = $bdd->prepare($query_bono);
    $stmt_bono->bindParam(':id_atencion', $id_atencion);
    $stmt_bono->bindParam(':id_paciente', $id_paciente);
    $stmt_bono->bindParam(':id_doctor', $id_doctor);
    $stmt_bono->execute();
    $_SESSION['bono'] = $stmt_bono->fetch(PDO::FETCH_ASSOC);
    
    $bdd->commit();
    header('Location: atencion_medica.php?success=Atencion efectuada, bono emitido.');
    exit();
}
catch (Exception $e) {
    $bdd->rollBack();
    header('Locatino: atencion_medica.php?error=Error al registrar la atencion y emitir bono '. 
    $e->getMessage());
    exit();
}

if (!$paciente) {
    header('Location: atencion_medica.php?error=No existen atenciones para ese RUN');
    exit();
}
//Ahora, se ha verificado la infomración del paciente y existe. Volvemos a cancelar_atencion.php, mostramos
//la infomracion de la persona y le desplegamos el formualario para que seleccione un médico.
$_SESSION['RUN_paciente'] = $run_paciente;
$_SESSION['ID_paciente'] = $paciente['ID'];
$_SESSION['atencion'] =  $paciente; 
header('Location: main_admin.php?success=Bono y efectuada registrada en atencion!');
exit();
?>