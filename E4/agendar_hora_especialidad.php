<?php
include_once 'utils.php';
session_start();
if (!isset($_SESSION['usuario'])){
    header('Location: index.php?error=No se ha iniciado sesion');
    exit();
}

$bdd = conectarBD();

$nombre_medico = $_POST['nombre_medico'] ?? '';
$especialidad = $_POST['especialidad'] ?? '';

//si no hay nombre ni especialidad, volvemos a agendar_hora.php
if ($nombre_medico == '' && $especialidad == ''){
    header('Location: agendar_hora.php?error=Debe ingresar nombre médico o especialidad');
    exit();
}

if ($especialidad != ''){
    // a revisar que la especialidad sea válida Notar que saca el primer doctor nomas.
    $query_esp = 'SELECT persona."ID" FROM persona LEFT JOIN profesion ON profesion."ID" = persona."ID"
    WHERE profesion."especialidad" ILIKE :especialidad AND persona."medico" = TRUE LIMIT 1';
    $stmt = $bdd->prepare($query_esp);
    $stmt->bindParam(':especialidad', "%{$especialidad}%", PDO::PARAM_STR);
    $stmt ->execute();
    $esp = $stmt->fetch();

    if (!$esp){
        header('Location: agendar_hora.php?error=Especialidad no válida');
        exit();
    }
    else{
        $_SESSION['ID_medico'] = $esp['ID'];
    }

}
elseif($nombre_medico != ''){
    // a revisar que exista un doctor con ese nombre.
    $nombre = explode(' ', $nombre_medico);
    if (count($nombre) != 2){
        header('Location: agendar_hora.php?error=Debe ingresar nombre y apellido del médico');
        exit();
    }
    $query_doc = 'SELECT persona."ID" FROM persona WHERE (persona."Nombres" ILIKE :nombre 
    AND persona."Apellidos" ILIKE :apellido) AND persona."medico" = TRUE LIMIT 1';
    $stmt = $bdd->prepare($query_doc);
    $stmt->bindParam(':nombre', "%{$nombre[0]}%", PDO::PARAM_STR);
    $stmt->bindParam(':apellido', "%{$nombre[1]}%", PDO::PARAM_STR);
    $stmt ->execute();
    $doc_id = $stmt->fetch();   
    if (!$doc_id){
        header('Location: agendar_hora.php?error=Médico no encontrado (nombre/apellido)');
        exit();
    }
    else{
        $_SESSION['ID_medico'] = $doc_id['ID'];
    }
}

//si hay médico, creamos la hora.
if (isset($_SESSION['ID_medico'])){
    $disponibilidad_query = 'SELECT * FROM disponibilidad_doctor(:id_medico) ORDER BY "Fecha" ASC, "Hora" ASC LIMIT 1';
    $stmt = $bdd->prepare($disponibilidad_query);
    $stmt->bindParam(':id_medico', $_SESSION['ID_medico'], PDO::PARAM_INT);
    $stmt -> execute();
    $agenda = $stmt->fetch();
    if (!$agenda){
        header('Location: agendar_hora.php?error=El medico no tiene hora');
        exit();
    }
    //crear la hora
    try {
        $bdd->beginTransaction();
        $crear_hora_query = 'INSERT INTO atencion("fecha", "IDPaciente", "IDMedico", "Diagnostico", "Efectuada", "hora")
        VALUES (:fecha, :id_paciente, :id_medico, NULL, FALSE, :hora)';
        $stmt = $bdd->prepare($crear_hora_query);
        $stmt->bindParam(':fecha', $agenda['Fecha']);
        $stmt->bindParam(':hora', $agenda['Hora']);
        $stmt->bindParam(':id_paciente', $_SESSION['ID_paciente'], PDO::PARAM_INT);
        $stmt->bindParam(':id_medico', $_SESSION['ID_medico'], PDO::PARAM_INT);
        $stmt -> execute();
        $bdd->commit();
        header('Location: main_admin.php?succes=Hora agendada');
        exit();
    }
    catch (Exception $e) {
        $bdd->rollBack();
        header('Location: main_admin.php?error=Fallo al agendar hora');
        exit();
    }
}
?>