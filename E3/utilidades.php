<?php
include "parametros.php";

// Funcion copiada de la ayudantía
function leer_archivo(string $nombreArchivo): array{
    //
    // funcion que recibe un nombre de archivo en forma string, lee su contenido y lo retorna como
    // un array
    // 
    $arreglo    = array();
    $archivo    = fopen($nombreArchivo, "r");
    $header         = fgets($archivo); // sacar encabezado
    while (!feof($archivo)) {
        $linea = fgets($archivo);
        if ($linea !== false && $linea != "") {
            $arreglo[] = explode(",", $linea);
        }
    }
    fclose($archivo);
    return $arreglo;
}

function leer_encabezado(string $nombreArchivo): array{
    // 
    // Funcion que lee un csv de nombre  y retorna su encabezado en forma de array
    // 
    $archivo    = fopen($nombreArchivo, "r");
    $arreglo    = array();
    $header     = fgets($archivo);
    $arreglo[]  = explode(",", $header);
    fclose($archivo);
    return $header;
}

// funcion copiada de la ayudantía
function eliminar_duplicados(array $array, string $nombre_csv): array{
    // 
    // función que recibe un array y el nombre csv sin la extension ("Persona") y retorna este pero
    //  elminando los elementos duplicados
    // 
    $archivo_log    = fopen($carpeta_errores . $nombre_csv . "LOG.txt", "w");
    $archivo_error  = fopen($carpeta_errores . $nombre_csv . "ERR.csv", "w");
    

    $resultado      = array();
    $duplicados     = array();
    foreach ($array as $fila) {
        if (!in_array($fila, $resultado)) {
            $resultado[]  = $fila;
        }
        else {
            $duplicados[] = $fila;
        }
    }
    return array($resultado, $duplicados);
}

function escribir_oks(array $array, string $csv, string $tipo_archivo="OK"): void{
    // 
    // funcion que recibe un array de datos y los escribe en el archivo csv
    // csv_limpios/{$csv}{$tipo_archivo}.csv. El $tipo_archivo puede ser OK o ERR
    //
    $csv_nombre         = explode(".", $csv)[0];
    $ruta_archivo_ok    = $carpeta_limpios . $csv_nombre . $tipo_archivo . "csv";
    
    // Si el archivo de oks no existe, lo creamos y agregamos el header (atributos)
    if (!file_exists(ruta_archivo_ok)){
        $archivo_ok     = fopen($ruta_archivo_ok, "w");
    }
    else {
        $archivo_ok     = fopen($ruta_archivo_ok, "w");
        $atributos      = leer_encabezado($carpeta_original . $csv);
        fwrite($archivo_ok, implode(';', $atributos) . "\n");
    }

    // Escribimos los datos correctos de $array y cerramos el archivo.
    foreach ($array as $fila) {
        fwrite($archivo_ok, implode(';', $fila) . "\n");
    fclose($archivo_ok);

    // Agregué este print pa depurar.
    echo 'Archivo ' . $ruta_archivo_ok . ' escrito con éxito.\n';
    return;
}


fclose($archivo_rol);
}


function revisar_personas(){
    #Lectura de archivos importantes
    $personas               = leer_archivo($carpeta_original + $carpeta_personas);
    $personas               = eliminar_duplicados($personas);
    $personas_no_duplicadas = $personas[0];
    $personas_duplicadas    = $personas[1];


    return $resultado;
}

?>