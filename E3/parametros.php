<?php

// 
// Este módulo contiene los parametros para correr la limpieza con php
// 

// Nombres de archivos csv
$archivo_personas                   = "Persona.csv";
$archivo_orden                      = "Orden.csv";
$archivo_medicamento                = "Medicamento.csv";
$archivo_instituciones              = "Instituciones previsionales de salud.csv";
$archivo_farmacia                   = "Farmacia.csv";
$archivo_atencion                   = "Atencion.csv";
$archivo_arancel_fonasa             = "Arancel fonasa.csv";
$archivo_arancel_dcc                = "Arancel DCColita de rana.csv";

$carpeta_original                   = "csv_originales/";
$carpeta_limpios                    = "csv_limpios/";
$carpeta_errores                    = "csv_errores/";
$carpeta_logs                       = "csv_logs/";

$carpeta_planes_originales          = carpeta_origial + "planes/";
$carpeta_firmas_originales          = carpeta_original + "firmas/";
$carpeta_planes_limpios             = carpeta_limpios + "planes/";  
$carpeta_firmas_limpias             = carpeta_limpios + "firmas/";  


// restricciones de integridad: Son un array de arrays. Cada subarray es un atributo, cuyo primer 
// elemento es el type de la variable, el segundo su longitud máxima y el tercero True si IS NOT
// NULL, false si no.
$rdi_persona = array(
    array(string, 10, true),     //RUT
    array(string, 30, true),     //nombre
    array(string, 30, true),     //apellido
    array(string, 100, false),   //direccion
    array(string, 1e9, false),   //correo. el 1e9 es analogo a poner que es varchar(infinito)
    array(integer, 1e9, false),  // telefono, el número minimo es el que puse en el sgdo attr el 1e9
                                 // sirve para que se cumpla la restriccion del valor numérico.
    array(array('beneficiario', 'titular', ''),-1, false), //tipo
    array(array('Staff médico', 'administrativo', 'paciente', '', 'Staff médico, paciente', //rol
     'administrativo, paciente'), 1e9, false),
    array(string, 30, false),     // especialidad
    array(string, 30, false),     // firma
    array(string, 30, false)
    )

    
?>