<?php
// Código extraido de la ayudantía 12
function conectarBD() {
    $host = 'stonebraker.ing.uc.cl';
    $dbname = 'jara.fernando.e4';
    $usuario = 'jara.fernando.e4';
    $clave = '2420286J';

    try{
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $usuario, $clave);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        echo "Hubo un fallo en la conexión: " . $e->getMessage();
        exit();
    }
};

//nota personal/para corrección: me equivoque y le puse validar ruT con T en vez de N. Me di cuenta muy tarde
// asi que lo dejé asi nomas.
function validar_rut(string $rut): bool {
    //funcion que recibe un rut y retorna true si es válido
    //Dejé bien comentada esta función para que se entienda. TUve que volver a leerla varias veces
    //primero revisa que tenga el guion. Reviso que tenga dv y cuerpo 
    // revisa que el dv sea un único caracter
    //revisa que el cuerpo sea de 8 o 7 dígitos
    //revisa que el cuerpo sea numérico
    //revisa que el dv sea numerico o k
    //si paso todas estas revisiones, el rut es válido
    if (str_contains($rut, '-')) {
        $partes = explode('-', $rut);
        
        if (count($partes) != 2) {
            return FALSE;
        }
        
        if (str_length($partes[1]) != 1) {
            return FALSE;
        }
        
        if (str_length($partes[0]) > 8 || str_length($partes[0]) < 7) {
            return FALSE;
        }
        
        if (!is_numeric($partes[0])){
            return FALSE;
        }
        
        if (!is_numeric($partes[1]) && ($partes[1] != 'k' && $partes[1] != 'K')) {
            return FALSE;
        }
        return TRUE;
    }
    else {
        return FALSE;
    }
};
?>