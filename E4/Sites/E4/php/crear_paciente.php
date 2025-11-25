<?php
include_once 'utils.php';
session_start();
if (!isset($_SESSION['usuario'])){
    header('Location: index.php?error=No se ha iniciado sesion');
    exit();
}
$run_paciente = $_POST['RUN_paciente'] ?? '';
if (!validar_rut($run_paciente)) {
    header('Location: agendar_hora.php?error=RUN invalido de nuevo paciente');
    exit();
}
$nombres = $_POST['nombres'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$InstSalud = $_POST['InstSalud'] ?? '';
$IDtitular = $_POST['IDtitular'] ?? '';
$beneficiario = $_POST['beneficiario?'] ?? '';
$email = $_POST['email'] ?? '';
$rol = $_POST['rol'] ?? '';
$bdd = conectarBD();
try {
    $query_persona = 'CALL ingresar_persona(:run_paciente, :nombres, :apellidos, :direccion, 
    :email, :telefono, :InstSalud, :medico);
    CALL ingresar_beneficiario(:run_paciente, :beneficiario, :IDtitular);
    CALL ingresar_rol(:run_paciente, :rol);';
    $bdd->beginTransaction();
    $stmt = $bdd->prepare($query_persona);
    $stmt->bindParam(':run_paciente', $run_paciente);
    $stmt->bindParam(':nombres', $nombres);
    $stmt->bindParam(':apellidos', $apellidos);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':InstSalud', $InstSalud);
    $stmt->bindParam(':IDtitular', $IDtitular);
    $stmt->bindParam(':beneficiario', $beneficiario);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':rol', $rol);
    
    if (str_contains($rol, 'dico')){
        $medico = TRUE;
    }
    else {
        $medico = FALSE;
    }
    $stmt->bindParam(':medico', $medico);
    $stmt->execute();
    $bdd->commit();
    

    $query_id = 'SELECT persona."ID" FROM persona WHERE persona."RUN" ILIKE :run_paciente';
    $stmt = $bdd->prepare($query_id);
    $stmt->bindParam(':run_paciente', $run_paciente);
    $stmt->execute();
    $id_paciente = $stmt->fetch()['ID'];
    
    $_SESSION['RUN_paciente'] = $run_paciente;
    $_SESSION['ID_paciente'] = $id_paciente;
    header('Location: agendar_hora.php?succes=Paciente creado');
    exit();
}
catch (Exception $e) {
    $bdd->rollBack();
    header('Location: main_admin.php?error=Error al crear paciente');
    exit();
}
?>