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

$carpeta_planes_originales          = $carpeta_original . "planes/";
$carpeta_firmas_originales          = $carpeta_original . "firmas/";
$carpeta_planes_limpios             = $carpeta_limpios  . "planes/";  
$carpeta_firmas_limpias             = $carpeta_limpios  . "firmas/";  


// restricciones de integridad: Son un array de arrays. Cada subarray es un atributo, cuyo primer 
// elemento es el type de la variable, el segundo su longitud máxima y el tercero True si IS NOT
// NULL, false si no.

// restricciones para Persona.csv
$rdi_persona = array(
    'integer',  //id
    'string',     //RUT
    'string',     //nombre
    'string',     //apellido
    'string',   //direccion
    'string',   //correo. el 1e9 es analogo a poner que es varchar(infinito)
    'integer',  // telefono, el número minimo es el que puse en el sgdo attr el 1e9
                                 // sirve para que se cumpla la restriccion del valor numérico.
    array('beneficiario', 'titular', ''), //tipo
    array('Staff médico', 'administrativo', 'paciente', '', 'Staff médico, paciente', //rol
     'administrativo, paciente'),
    'string',     // especialidad
    'string',     // firma
    'string'
);
$rdi_persona_dominio = array(
    NULL, //id
    10,     //RUT
    30,     //nombre
    30,     //apellido
    100,   //direccion
    Null,   //correo. el 1e9 es analogo a poner que es varchar(infinito)
    1e9,  // telefono, el número minimo es el que puse en el sgdo attr el 1e9
                                 // sirve para que se cumpla la restriccion del valor numérico.
    NULL, //tipo
    // array(NULL),               //tiitular
    30,     // especialidad
    30,     // firma
    30      // InsSalPrev
);

// restricciones para Instituciones previsionales de salud.csv
$rdi_ips = array(
    'integer', //codigo
    'string',  //nombre
    array('abierta', 'cerrada'), //tipo
    'string', //rut
    'string'  //enlace
);

// restricciones para Farmacia.csv
$rdi_farmacia = array(
    'integer',         // id
    'string',          //nombre
    'string',          //descripcion
    array('Alimentos', 'Equipamiento', 'Fármacos', 'insumos',
    'psicotrópicos', 'Refrigerados', 'Sueros'), //tipo
    'integer',          //codonu
    'string',           //clasonu
    'string',           //clasificacion
    array('activo', 'inactivo'), //estado
    array('0', '1'),        //esencial
    'integer'               //precio
);

// restricciones para Arancel DCColita de rana.csv
$rdi_arancel_DCC = array(
    'integer', //codigo
    'string', //codFonasa
    'string', //atencion
    'integer' //valor
);

// restricciones para Arancel fonasa.csv
$rdi_arancel_fonasa = array(
    'integer', //codF
    'integer', //codA
    'string', //atencion
    'integer', //valor
    'string', //grupo
    'string' //tipo
);


// restricciones atencion
$rdi_atencion = array(
    'integer', //id
    'string', //runpaciente
    'string', //runmedico
    'string', //diagnostico
    'bool' //efectuada
);
// restricciones med
$rdi_medicamento = array(
    'integer', //IDAtencion
    'string', //nombre
    'string', //posología
    'bool' //psicotropico
);
// restricciones orden
$rdi_orden = array(
    'integer', //IDAtencion
    'integer', //IDArancel
    'string' //consulta
);
// restricciones plan
$rdi_plan = array(
    'integer', //bonificacion
    'grupo' //text
);

// ese 'diccionario' lo uso para hacer la revision de IC
$dict_rdi = array(
    "Persona.csv"           => $rdi_persona, #Por llenar
    "Orden.csv"             => $rdi_orden,
    "Medicamento.csv"       => $rdi_medicamento,
    "Instituciones previsionales de salud.csv" => $rdi_ips,
    "Farmacia.csv"          => $rdi_farmacia,
    "Atencion.csv"          => $rdi_atencion,
    "Arancel fonasa.csv"    => $rdi_arancel_fonasa,
    "Arancel DCColita de rana.csv"             => $rdi_arancel_DCC
    "plan"                  => $rdi_plan
);

// $rdi_persona = array(
// //     array('integer', false, true), //id
// //     array('string', 10, true),     //RUT
// //     array('string', 30, true),     //nombre
// //     array('string', 30, true),     //apellido
// //     array('string', 100, false),   //direccion
// //     array('string', 1e9, false),   //correo. el 1e9 es analogo a poner que es varchar(infinito)
// //     array('integer', 1e9, false),  // telefono, el número minimo es el que puse en el sgdo attr el 1e9
// //                                  // sirve para que se cumpla la restriccion del valor numérico.
// //     array(array('beneficiario', 'titular', ''), false, false), //tipo
// //     array(array('Staff médico', 'administrativo', 'paciente', '', 'Staff médico, paciente', //rol
// //      'administrativo, paciente'), false, false),
// //     array('string', 30, false),     // especialidad
// //     array('string', 30, false),     // firma
// //     array('string', 30, false)        
// // );


// $rdi_instituciones_salud = array(
//     array('integer', false, true),        // codigo
//     array('string', 30, true),            // nombre
//     array(array('abierta', 'cerrada'),  //tipo de institucion
//     false, false), 
//     array('string', 12, true),            // rut
//     array('string', 1e9, false)           // enlace
// ); 
// $rdi_farmacia = array(
//     array('integer', false, true),        // cod igo generico minsal
//     array('string', 100, true),           // nombre
//     array('string', 256, true),           // descripcion
//     array(array('Alimentos', 'Equipamiento', 'Fármacos', 'insumos', 'psicotrópicos', 'Refrigerados
//     ', 'Sueros'), false, false),        // tipo
//     array('integer', 1e9, false),         //codonu
//     array('string', 50, false),           //clasonu
//     array('string', 50, true),            //clasificacion
//     array(array('activo', 'inactivo'), false, false), //estado
//     array(array(0,1), false, false),    //tipo
//     array('integer', 1e9, true)
// );
?>