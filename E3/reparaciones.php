<?php
//funciones para persona
function reparar_profesion_persona(array $tuplas): array{
    // funcion que recibe un array de tuplas (de Persona) y el nombre del archivo y repara las
    //profesiones para que respeten el formato. Las retorna en array.
    foreach($tuplas as $i => $fila){ //nota persoal: indice 10 es profesion
        if($fila[10] == 'kinesiólogo/a'){
            $tuplas[$i][10] = 'Kinesiólogo/a';
            echo "\n" . 'Se ha reparado una profesion en persona';
        }
        elseif($fila[10] == 'TENSS'){
            $tuplas[$i][10] = 'TENS';
            echo "\n" . 'Se ha reparado una profesion en persona';
        }
        elseif($fila[10] == 'medico(a)'){
            $tuplas[$i][10] = 'médico/a';
            echo "\n" . 'Se ha reparado una profesion en persona';
        }
    }
    return $tuplas;
}

function reparar_rol_persona(array $tuplas): array{
    //funcion que recibe un array con las tuplas de persona y repara los atributos 'rol' 
    // mal escritos y los retorna en un array
    foreach($tuplas as $i => $fila){
        if($fila[9] == 'Staff médico, Staff médico,paciente'){
            $tuplas[$i][9] = 'Staff médico, paciente';
            echo "\n" . 'Se ha reparado un rol de persona'. "\n" ;
        }
    }
    return $tuplas;
}
//reparaciones Instituciones
function reparar_tipo_inst(array $tuplas): array{
    // funcion que recibe un array de tuplas de institicuino de salud y les repara el parametro
    // tipo para que sea 'abierto' o 'cerrado'. Retorna le resultado en u array.
    // SUPUESTO: si no se sabe, se asume que está cerrada.
    foreach($tuplas as $i => $fila){
        if ($fila[2] != 'abierta' && $fila[2] != 'cerrada'){
            $tuplas[$i][2] = 'cerrada';
            echo "\n" . 'Se ha reparado un "tipo" de institucion'. "\n" ;
        }  
    }
    return $tuplas;
}

function reparar_enlace(array $tuplas): array{
    //
    //funcion que recibe tuplas de formad e array de inst. de salud y repara los enlaces.
    foreach($tuplas as $i => $fila){
        if($fila[4] != '' && !str_contains($fila[4], 'http')){
            $tuplas[$i][4] = 'https://' . $tuplas[$i][4];
            echo "\n" . 'Se ha reparado un enlace de institución de salud'. "\n" ;
        }
    }
    return $tuplas;
}

// reparaciones para farmacia
function reparar_tipo_farmacia(array $tuplas): array{
    //
    // funcion que recibe tuplas de farmacia y repara los tipos, retornandolos en array
    //
    foreach($tuplas as $i => $fila){ //indice 4
        if(!in_array($fila[3], array('Alimentos', 'Equipamiento', 
        'Fármacos', 'insumos', 'psicotrópicos', 'Refrigerados', 'Sueros'))){
            $tuplas[$i][3] = '';
            echo "\n" . 'Se ha reparado un formato en tipo farmacia';
        }
    }
    return $tuplas; 
}

function reparar_estado_farmacia(array $tuplas): array{
    // 
    // funcion que recibe tuplas de farmacia y repara los estados, retornando las tuplas en array
    // Supuesto: si hay una i en el sring, es porque está inactivo pero mal escrito. Si no esta la 
    //i, se asume que está activo.
    foreach($tuplas as $i => $fila){
        if(!in_array($fila[7], array('activo', 'inactivo'))){
            if(str_contains($fila[7], 'i')){
                $tuplas[$i][7] = 'inactivo';
                echo "\n" . 'Correccion en estado farmacia realizada (inactivo corregido)';
            }
            else{ //en caso contrario voy a asumir que esta activo nomas
                $tuplas[$i][7] = 'activo';
                echo "\n" . 'Correccion en estado farmacia realizada (activo corregido)';
            }
        }
    }
    return $tuplas;
}

function reparar_esencial_farmacia(array $tuplas): array{
    //
    //funcion que repara esencial de farmacia. Si no se sabe, se asume que no es esecnial
    // returna las tuplas editadas
    foreach($tuplas as $i => $filas){
        if (!in_array($filas[8], array('0', '1', 0, 1))){
            $tuplas[$i][8] = '0';
            echo "\n" . 'Correccion esencial en farmacia realizada'. "\n" ;
        }
    }
    return $tuplas;
}

// atencion
function corregir_efectuada_atencion(array $tuplas): array{
    //
    // funcion que recibe las tuplas de atencion, revisa que si esta efectuada, el 
    // diagnostico no sea null también revisa que si no fue efectuada, el diagnóstico sea null
    foreach($tuplas as $i => $fila){ //efectuada indice 4, diagnostico indice 3
        if($fila[4] == FALSE){
            if($fila[3] != ''){
                $tuplas[$i][3] = '';
                echo "\n" . 'Concordancia efectuada-diagnostico corregida (hay diagnostico pero no efectuada)'. "\n" ;
            }
        elseif($fila[4] == TRUE){
            if($fila[3] == '' || $fila[3] == Null){
                $tuplas[$i][3] = 'Diagnóstico realizado pero no informado';
                echo "\n" . 'Diagnosico no informado corregido'. "\n" ;
            }
        }
        }
    }
    return $tuplas;
}
?>