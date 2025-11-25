<?php
function validar_run(string $rut): bool {
    //funcion que recibe un run y retorna true si es válido
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
        
        if (strlen($partes[1]) != 1) {
            return FALSE;
        }
        
        if (strlen($partes[0]) > 8 || strlen($partes[0]) < 7) {
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
