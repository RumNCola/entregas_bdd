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
    echo("\nSe eliminaron " . $cuenta . " duplicados en el archivo " . $csv . "\n");
    return $resultado;
}

function escribir_log(array $array, string $csv_nombre): void{
    // 
    // funcion que recibe un array (fila) y la escribe en el log entregado en el archivo de 
    // csv_nombre, que viene en formato "Persona.csv".
    //
    $ruta_log       = $carpeta_logs . explode(".", $csv_nombre)[0] . "LOG.txt";
    $archivo_log    = fopen($ruta_log, "w");
    fwrite($archivo_log, implode(";", $array) . "\n");
    
    #Printeo para depurar e identificar el error
    echo 'Fila con error escrita en el log: ' . implode(';', $array) . '\n';
    fclose($archivo_log);
    return;
}

function escribir_ok_err(array $array, string $csv, string $tipo_archivo="OK"): void{
    // 
    // funcion que recibe un array de datos y los escribe en el archivo csv (OK o ERR)
    // csv_limpios/{$csv}{$tipo_archivo}.csv si $tipo_archivo es OK o en csv_errores/{$csv}
    // {$tipo_archivo}.csv si $tipo_archivo es ERR
    //

    $csv_nombre                = explode(".", $csv)[0];
    
    // Definimos la ruta del archivo a escribir segun tipo_archivo
    if($tipo_archivo == "OK"){
        $ruta_archivo_nuevo    = $carpeta_limpios . $csv_nombre . $tipo_archivo . "csv";
    }
    else{
        $ruta_archivo_nuevo    = $carpeta_errores . $csv_nombre . $tipo_archivo . "csv";
    }
    
    // Si el archivo de oks no existe, lo creamos y agregamos el header (atributos)
    if (!file_exists($ruta_archivo_nuevo)){
        $archivo        = fopen($ruta_archivo_nuevo, "w");
    }
    else {
        $archivo        = fopen($ruta_archivo_nuevo, "w");
        $atributos      = leer_encabezado($carpeta_original . $csv);
        fwrite($archivo, implode(';', $atributos) . "\n");
    }

    // Escribimos los datos correctos de $array y cerramos el archivo.
    foreach ($array as $fila) {
        fwrite($archivo, implode(';', $fila) . "\n");
    }
    fclose($archivo);

    // Agregué este print pa depurar.
    echo 'Archivo ' . $ruta_archivo_nuevo . ' escrito con éxito.\n';
    return;
}

function revisar_restriccion_integridad(array $tuplas, string $csv): array {
    //
    // funcion que recibe tuplas de una tabla y el nombre de un csv Revisa el cumplimiento
    // de las IC. Si no cumple, la manda a ERR y se printea cual no cumple
    //
    $array_ERR = array();
    $array_OK = array();
    echo ($csv);
    $restricciones = $dict_rdi[$csv];
    foreach($tuplas as $fila){
        foreach($restricciones as $i => $attr){
            $correcto = True;
            if (gettype($attr) == "string"){
                if (gettype($fila[i]) != $attr){
                    echo "\nRestriccion de integridad no respetada. Agregando a ERR.\n";
                    $array_ERR[] = $fila;
                    $correcto = False;
                    break;
                }
            elseif (gettype($attr) == "array"){
                if(!in_array($fila[i], $attr)){
                    echo "\nRestriccion de integridad no respetada. Agregando a ERR.\n";
                    $array_ERR[] = $fila;
                    $correcto = False;
                    break;

                }
            }
        }
        }
        if ($correcto){
            $array_OK[] = $fila;
        }  
    }
    escribir_ok_err_log($array_ERR, $csv, "ERR");
    return array_OK;
}
function revisar_csv(string $csv, string $carpeta_csv): array{
    // 
    // Función que recibe el nobmre de un csv en formato "Persona.csv", lo corrige y lo retorna
    // como array, es decir:
    // 0. Lectura de datos
    // 1. Elimina duplicados
    // 2. Revisa restricciones de integridad
    // 3. Revisa dominios de las restricciones de integridad
    // 4. Corregir datos nulos 
    // 5. Eliminar llaves duplicadas
    // 6. Estandarizar Datos
    // 

    // 0. Lectura
    $tuplas = leer_archivo($carpeta_csv . $csv);
    
    // 1. eliminación de duplicados
    $tuplas = eliminar_duplicados($tuplas, $csv);

    // 2. Revisión de restricciones IC
    $tuplas = revisar_restriccion_integridad($tuplas, $csv);
    
    //2. 
    

    return $tuplas;
}

?>