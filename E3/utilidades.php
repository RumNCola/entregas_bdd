<?php
include "parametros.php";
include "reparaciones.php";

// Funcion copiada de la ayudantía
function leer_archivo(string $nombreArchivo): array{
    //
    // funcion que recibe un nombre de archivo en forma string, lee su contenido y lo retorna como
    // un array
    // 
    $arreglo    = array();
    $archivo    = fopen($nombreArchivo, "r");
    $header     = fgets($archivo); // sacar encabezado
    while (!feof($archivo)) {
        $linea = fgets($archivo);
        
        $vacio = true;
        //para no leer las tuplas vacias
        for($i=0; $i < strlen($linea); $i++){
            if($linea[$i] != '' && $linea[$i] != ';'){
                $vacio = false;
            }
        }
        if(!$vacio){
        if ($linea !== false && $linea != "") {
            if($linea != $vacio){
            $arreglo[] = explode(";", $linea);
            }
        }}
    }
    fclose($archivo);
    return $arreglo;
}

function leer_encabezado(string $csv): array{
    // 
    // Funcion que lee un csv de nombre  y retorna su encabezado en forma de array
    // 
    $archivo    = fopen($csv, "r");
    $arreglo    = array();
    $header     = fgets($archivo);
    $arreglo    = explode(";", $header);
    fclose($archivo);
    return $arreglo;
}

// funcion copiada de la ayudantía
function eliminar_duplicados(array $array, string $csv): array{
    // 
    // función que recibe un array y el nombre csv tipo ("Persona.csv") y retorna este pero
    //  elminando los elementos duplicados
    // 
    $nombre_csv     = explode('.', $csv)[0];
    $resultado      = array();
    $cuenta         = 0;
    foreach ($array as $fila) {
        if (!in_array($fila, $resultado)) {
            $resultado[]  = $fila;
        }
        else {
            $cuenta += 1;
        }
    }
    echo("\nSe eliminaron " . $cuenta . " duplicados/filas vacías en el archivo " . $csv . "\n");
    return $resultado;
}

function escribir_log(string $csv_nombre): void{
    // 
    // funcion que escribe en el log entregado en el archivo de 
    // csv_nombre, que viene en formato "Persona.csv".
    //
    global $carpeta_errores;
    global $carpeta_logs;

    $array    = leer_archivo($carpeta_errores . explode('.', $csv_nombre)[0] . 'ERR.csv');
    $ruta_log       = $carpeta_logs . explode(".", $csv_nombre)[0] . "LOG.txt";
    $archivo_log    = fopen($ruta_log, "w");
    foreach($array as $fila){
        fwrite($archivo_log, 'Se declara irreparable: '. implode(";", $fila) . "\n");
        #Printeo para depurar e identificar el error
        echo 'Fila con error escrita en el log: ' . implode(';', $fila) . '\n';
    }
    fclose($archivo_log);
    return;
}

function escribir_ok_err(array $array, string $csv, string $tipo_archivo="OK"): void{
    // 
    // funcion que recibe un array de datos y los escribe en el archivo csv (OK o ERR)
    // csv_limpios/{$csv}{$tipo_archivo}.csv si $tipo_archivo es OK o en csv_errores/{$csv}
    // {$tipo_archivo}.csv si $tipo_archivo es ERR
    //
    global $carpeta_limpios; 
    global $carpeta_errores;
    global $carpeta_original;

    $csv_nombre                = explode(".", $csv)[0];
    
    // Definimos la ruta del archivo a escribir segun tipo_archivo
    if($tipo_archivo == "OK"){
        $ruta_archivo_nuevo    = $carpeta_limpios . $csv_nombre . $tipo_archivo . ".csv";
    }
    else{
        $ruta_archivo_nuevo    = $carpeta_errores . $csv_nombre . $tipo_archivo . ".csv";
    }
    
    // Si el archivo de oks no existe, lo creamos y agregamos el header (atributos)
    if (!file_exists($ruta_archivo_nuevo)){
        $archivo        = fopen($ruta_archivo_nuevo, "w");
        $atributos      = leer_encabezado($carpeta_original . $csv);
        fwrite($archivo, implode(';', $atributos));
    }
    else {
        $archivo        = fopen($ruta_archivo_nuevo, "a");
    }

    // Escribimos los datos correctos de $array y cerramos el archivo.
    foreach ($array as $fila) {
        fwrite($archivo, implode(';', $fila));
    }
    fclose($archivo);

    // Agregué este print pa depurar.
    
    return;
}

function revisar_correo(string $correo): bool{
    // 
    // Función que recibe un string y revisa si cumple el formato de correo. retorna un
    // booleano según el resultado
    // 
    $resultado = true;
    if($correo == ''){
        return $resultado;
    }
    if (!str_contains($correo, '@')){
        $resultado = false;
        return $resultado;
    }
    $detalle = explode('@', $correo)[0];
    if ($detalle = ''){
        $resultado = false;
        return $resultado;
    }
    $direccion = explode('@', $correo)[1];
    if (!str_contains($direccion, '.')){
        $resultado = false;
        return $resultado;
    }
    return $resultado;
}

function revisar_rut(string $rut): bool{
    // 
    // función que recibe un string $rut y retorna un booleano si cumple el formato rut
    // ESTE ES PARA ISAPRES
    $resultado = True;
    //revisar largo (me ahorro los comentarios apra revisar_run porque son analogos)
    if (strlen($rut) != 12){
        $resultado = false;
        return $resultado;
    }
    // revisar que contenga -
    if (!str_contains($rut, '-')){
        $resultado = false;
        return $resultado;
    }
    $dv = explode('-', $rut)[1];
    // revisar dv (digito verificador)
    if  (!is_numeric($dv) && ($dv != 'K' && $dv != 'k')){
        $resultado = false;
        return $resultado;
    }

    $rut = explode('.', explode('-', $rut)[0])[0]; // el resto de numeros menos dv, puntos y guión
    if(!is_numeric($rut)){
        $resultado = false;
        return $resultado;
    }
    elseif($rut[0] < 6){
        $resultado = false;
        return $resultado;
    }
    return $resultado;
}


function revisar_run(string $run): bool{
    // 
    // función que recibe un string $run y retorna un booleano si cumple el formato run
    // ESTE ES PARA PERSONAS
    $resultado = True;
    if (strlen($run) != 8){
        $resultado = false;
        return $resultado;
    }

    if (!str_contains($run, '-')){
        $resultado = false;
        return $resultado;
    }

    $dv  = explode('-', $run)[1];
    if  (!is_numeric($dv) && ($dv != 'K' && $dv != 'k')){
        $resultado = false;
        return $resultado;
    }

    $run = explode('-', $run)[0];
    if (!is_numeric($run[0]) || $run[0] == 0){
        $resultado = false;
        return $resultado;
    }
    else{
        for($i=1; $i < strlen($run); $i++){
            if(!is_numeric($run[$i])){
                $resultado = false;
                return $resultado;
            }
        }
    }
    return $resultado;
}

function revisar_enlace(string $enlace): bool{
    // 
    // función que recibe un string $rut y retorna un booleano si cumple el formato rut
    //
    $resultado = True;
    if($enlace == ''){
        return $resultado;
    }
    if (!str_contains($enlace, '//')){
        $resultado = False;
        return $resultado;
    }
    $prefijo = explode('//', $enlace)[0];

    if( $prefijo != 'http' && $prefijo != 'https' ){
        $resultado = False;
        return $resultado;
    }
    return $resultado;
}

function revisar_formatos(array $tuplas, string $csv): array{
    //
    // Recibe las tuplas en forma array del csv $csv y revisa las que cumplen los formatos.
    // (Correos, ruts, etc).
    //
    global $carpeta_errores;

    $cuenta = 0;
    $resultado = array();
    $resultadoERR = array();
    foreach($tuplas as $i => $fila){
        $estado = true;
        if (str_contains($csv, 'Persona')){;
            if(!revisar_run($fila[1])){
                $estado = false;
                echo "\n" . 'En persona, fallo el run ' . $fila[1];
            }
            if(!revisar_correo($fila[5])){
                echo "\n". 'En persona, fallo correo' . $fila[5];
                echo $fila[5];
                echo '';
                $tuplas[$i][5] = '';
                echo "\nreparacion de correo realizada\n";
            }
        }
        elseif (str_contains($csv, 'Atencion')){
            $estado = revisar_run($fila[2]) && revisar_run($fila[3]);
        }
        elseif (str_contains($csv, "Instituciones previsionales de salud")){
            $estado = revisar_rut($fila[3]) && revisar_enlace($fila[4]);
        }
        if ($estado != true){
            $resultadoERR[] = $fila;
            $cuenta += 1;
        }
        else {
            $resultado[] = $fila;
        }
       }
    echo "\n". 'Encontré ' . $cuenta .' errores de formato (correo, run/rut y enlace)'. "\n";
    escribir_ok_err($resultadoERR, $csv, "ERR");
    return $resultado;
}

function revisar_restriccion_integridad(array $tuplas, string $csv): array {
    //
    // funcion que recibe tuplas de una tabla y el nombre de un csv Revisa el cumplimiento
    // de las IC. Si no cumple, la manda a ERR y se printea cual no cumple
    //
    global $dict_rdi;
    global $carpeta_errores;

    $cuenta = 0;
    $array_ERR = array();
    $array_OK = array();
    $header     = leer_encabezado('csv_originales/' .$csv);
    $restricciones = $dict_rdi[$csv];
    foreach($tuplas as $fila){
        $correcto = True;
        //Iterar sobre las restricciones de parametros.php
        foreach($restricciones as $i => $attr){
            ;
            //Si el atributo es null o '' se skipea (la validaciond de nulls se ve despues)
            if ($fila[$i] == '' || is_null($fila[$i])){
                continue;
            }
            //Si el la restriccion dice atributo es un string, se revisa que así lo sea.
            if (gettype($attr) == "string"){
                if ($attr == 'integer' or $attr == 'float')
                {
                    if (!is_numeric($fila[$i])){
                        $cuenta += 1;
                        $array_ERR[] = $fila;
                        $correcto = False;
                        echo 'falla de la tupla ' . var_dump($fila[$i]) . ' en ' . $header[$i];
                        break;
                    }
                }
                elseif ($attr == 'string'){
                    if (gettype($fila[$i]) != $attr){
                        $cuenta += 1;
                        $array_ERR[] = $fila;
                        $correcto = False;
                        echo 'falla de la tupla ' . $fila . ' en ' . $header[$i];
                        break;
                }
            }
            }
            //Si la restriccion dice que el campo vale algun valor tipo ('administrativo', 'medico') lo revisa
            elseif (gettype($attr) == "array"){
                if(!in_array($fila[$i], $attr)){
                    $cuenta += 1;
                    $array_ERR[] = $fila;
                    $correcto = False;
                    break;
                }
            }
        }
        if ($correcto){
            $array_OK[] = $fila;
        }  
    }
    echo "\n" . $cuenta ." restricciones de integridad no respetadas. Agregando a ERR.\n";
    escribir_ok_err($array_ERR, $csv, "ERR");
    return $array_OK;
}

function estandarizar_tuplas(array $tuplas, string $csv): array{
    //
    // funcion que recibe tuplas y estandariza según el csv ingresado
    //
    $resultado = array();
    $resultadoERR = array();
    $cuenta = 0;
    foreach($tuplas as $fila){
        if ($csv == "Persona.csv"){ //7 -> tipo, 9 -> rol, 10 -> profesion
            if(!in_array($fila[7], array('beneficiario', 'titular', ''))){
                $cuenta += 1;
                $resultadoERR[] = $fila;
            }
            if(!in_array($fila[9], array('Staff médico', 'administrativo', 'paciente',
             '', 'Staff médico, paciente', 'administrativo, paciente'))){
                $cuenta += 1;
                $resultadoERR[] = $fila;
             }
            if(!in_array($fila[10], array('TENS', 'enfermero/a', 
            'Kinesiólogo/a', 'médico/a', ''))){
                $cuenta += 1;
                $resultadoERR[] = $fila;
            }
        }
        elseif($csv == "Instituciones previsionales de salud.csv"){
            if(!in_array(fila[2], array('abierta', 'cerrada'))){
                $cuenta += 1;
                $resultadoERR[] = $fila;
            }
        }
        elseif($csv == "Farmacia.csv"){
            if(!in_array($fila[3], array('Alimentos', 'Equipamiento', 
            'Fármacos', 'insumos', 'psicotrópicos', 'Refrigerados', 'Sueros'))){
                $cuenta += 1;
                $resultadoERR[] = $fila;
            }
            if(!in_array($fila[7], array('activo', 'inactivo'))){
                $cuenta += 1;
                $resultadoERR[] = $fila;
            }
            if(!in_array($fila[8], array('0', '1'))){
                $cuenta += 1;
                $resultadoERR[] = $fila;
            }
        }
    }
    echo "\n". 'Encontré ' . $cuenta .' errores de estandarizacion en '. $csv . "\n";
    echo "\n";
    escribir_ok_err($resultadoERR, $csv, "ERR");
    return $resultado;
}

function revisar_csv(string $csv, string $carpeta_csv): array{
    // 
    // Función que recibe el nobmre de un csv en formato "Persona.csv", lo corrige y lo retorna
    // como array. En paralelo, agrega los errores a CSV_LIMPIOS
    // 
    echo "\n" .'========================================================';
    echo "\n";
    echo "\n" .'              LIMPIANDO ' . $csv;
    echo "\n";
    echo '========================================================';
    // 0. Lectura
    $tuplas = leer_archivo($carpeta_csv . $csv);
    
    // 1. eliminación de duplicados
    $tuplas = eliminar_duplicados($tuplas, $csv);

    // 1.1 Estandariza los datos
    $tuplas = corregir_estandarizados_csv($tuplas, $csv);

    // 2. Revisión de restricciones IC
    $tuplas = revisar_restriccion_integridad($tuplas, $csv);
    
    // 3. Revisar Datos fuera de formato. (Personas, InstdeSalud y Atención)
    $tuplas = revisar_formatos($tuplas, $csv);

    // 4. Descarta los datos no estandarizados y no reparables
    $tuplas = estandarizar_tuplas($tuplas, $csv);

    echo "\n". 'Archivo de Errores escrito con éxito.' . "\n";

    escribir_ok_err($tuplas, $csv, "OK");
    escribir_log($csv);
    echo "\n" .'========================================================';
    echo "\n";
    echo "\n" .'              LIMPIEZA TERMINADA: ' . $csv;
    echo "\n";
    echo '========================================================';
    return $tuplas;
}
?>