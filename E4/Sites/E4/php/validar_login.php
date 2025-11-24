<?php

function es_medico(array $tupla): bool {
    // Funcion que recibe una tupla y retorna true si la profesion es médico (no incluye tens ni kine)
    if (str_contains($tupla['profesion'], 'taff')) { //notar que no puse la primera letra porque no se si es caps sensitive
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function es_admin(array $tupla): bool {
    // funcion que recibe una tupla y retorna true si es administrador
    if (str_contains($tupla['profesion'], 'dministrativo')) { //lo mismo que en la func. anterior por caps sensitive
        return TRUE;
    }
    else{
        return FALSE;
    }
}

session_start();
require_once 'utils.php';

$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

// En esta parte, agregué para que se chequee los dominios de integridad (no mandamos la query
// si se ingresan tipos de datos que no corresponden)
if ($usuario == '' || !is_numeric($usuario)) {
    header('Location: index.php?error=Ingrese un usuario valido');
    exit();
}
elseif ($contrasena == '' || !is_numeric($contrasena)) {
    header('Location: index.php?error=Ingrese una contraseña valido');
    exit();
}

$bdd = conectarBD();

// Primero, hay que revisar si el usuario existe
$query_1 = "SELECT * FROM persona WHERE persona.ID = :usuario";
$stmt = $bdd->prepare($query_1);
$stmt -> bindParam(':usuario', $usuario); //De acuerdo a la ayud12, esto previene injections. (pa la P1)
$stmt -> execute();

$existe_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$existe_usuario){
    header('Location: index.php?error=El usuario ingresado no existe');
    exit();
}

// Ahora, revisamos si la contraseña es válida.
$query = "SELECT persona.ID AS ID, rol.Rol AS Rol
          FROM persona LEFT JOIN profesion ON persona.ID = profesion.ID 
          WHERE persona.ID = :usuario AND persona.RUN ILIKE ':contrasena__'";

$stmt = $bdd->prepare($query);
$stmt ->bindParam(':usuario', $usuario); //prevencion de injections 2
$stmt->bindParam(':contrasena', $contrasena); //prevencion de injections 3
$stmt ->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($resultado){
    if(es_admin($resultado)){
        $_SESSION['usuario'] = $usuario;
        header('Location: main.php');
        exit();
    }
    elseif(es_medico($resultado)){
       $_SESSION['usuario'] = $usuario;
        header('Location: main.php');
        exit(); 
    }
    else{
       header('Location: index.php?error=Error de autenticacion - no es médico ni admin.');
       exit();
    }
}
else{
    header('Location: index.php?error=Contrasena incorrecta! Intente nuevamente');
    exit();
}
?>
