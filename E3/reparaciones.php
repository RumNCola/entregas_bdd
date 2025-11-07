<?php
//funciones para persona
function reparar_profesion_persona(array $tuplas): array{
    // funcion que recibe un array de tuplas (de Persona) y el nombre del archivo y repara las
    //profesiones para que respeten el formato. Las retorna en array.
    $cuenta = 0;
    foreach($tuplas as $i => $fila){ //nota persoal: indice 10 es profesion
        if($fila[10] == 'kinesiólogo/a'){
            $tuplas[$i][10] = 'Kinesiólogo/a';
            $cuenta += 1;
        }
        elseif($fila[10] == 'TENSS'){
            $tuplas[$i][10] = 'TENS';
            $cuenta += 1;
        }
        elseif($fila[10] == 'medico(a)'){
            $tuplas[$i][10] = 'médico/a';
            $cuenta += 1;
        }

        // solo los médicos tienen profesion
        if(str_contains($fila[9], 'médico') && $fila[10] == ''){
            $tuplas[$i][10] = 'No Informada';
        }
        elseif(!str_contains($fila[9], 'médico') && $fila[10] != ''){
            $tuplas[$i][10] = '';
        }
        // los medicos/admins son titulares
        if(str_contains($fila[10], 'médico') || str_contains($fila[10], 'admin')){
            if($fila[8] != $fila[1]){
                $tuplas[$i][8] = $fila[1]; //si no es titular, se le hace titular
            }
        }
    }
    echo "\n" . 'Se ha reparado '. $cuenta .' profesion/es en persona';
    return $tuplas;
}

function reparar_rol_persona(array $tuplas): array{
    //funcion que recibe un array con las tuplas de persona y repara los atributos 'rol' 
    // mal escritos y los retorna en un array
    $cuenta = 0;
    foreach($tuplas as $i => $fila){
        if($fila[9] == 'Staff médico, Staff médico,paciente'){
            $tuplas[$i][9] = 'Staff médico, paciente';
            $cuenta += 1;
            
        }
    }
    echo "\n" . 'Se ha/n reparado ' . $cuenta . ' rol de persona'. "\n" ;
    return $tuplas;
}
//reparaciones Instituciones
function reparar_tipo_inst(array $tuplas): array{
    // funcion que recibe un array de tuplas de institicuino de salud y les repara el parametro
    // tipo para que sea 'abierto' o 'cerrado'. Retorna le resultado en u array.
    // SUPUESTO: si no se sabe, se asume que está cerrada.
    $cuenta = 0;
    foreach($tuplas as $i => $fila){
        if ($fila[2] != 'abierta' && $fila[2] != 'cerrada'){
            $tuplas[$i][2] = 'cerrada';
            $cuenta += 1;
        }  
    }
    echo "\n" . 'Se ham reparado '. $cuenta .' "tipo" de institucion'. "\n" ;
    return $tuplas;
}

function reparar_enlace(array $tuplas): array{
    //
    //funcion que recibe tuplas de formad e array de inst. de salud y repara los enlaces.
    $cuenta = 0;
    foreach($tuplas as $i => $fila){
        if($fila[4] != '' && !str_contains($fila[4], 'http')){
            $tuplas[$i][4] = 'https://' . $tuplas[$i][4];
            $cuenta += 1;
        }
    }
    echo "\n" . 'Se ha reparado '. $cuenta. ' enlaces de institución de salud'. "\n" ;
    return $tuplas;
}

// reparaciones para farmacia
function reparar_tipo_farmacia(array $tuplas): array{
    //
    // funcion que recibe tuplas de farmacia y repara los tipos, retornandolos en array
    //
    $cuenta = 0;
    foreach($tuplas as $i => $fila){ //indice 4
        if(!in_array($fila[3], array('Alimentos', 'Equipamiento', 
        'Fármacos', 'insumos', 'psicotrópicos', 'Refrigerados', 'Sueros'))){
            $tuplas[$i][3] = '';
            $cuenta += 1;
            
        }
    }
    echo "\n" . 'Se han reparado '. $cuenta . ' formatos en tipo farmacia';
    return $tuplas; 
}

function reparar_estado_farmacia(array $tuplas): array{
    // 
    // funcion que recibe tuplas de farmacia y repara los estados, retornando las tuplas en array
    // Supuesto: si hay una i en el sring, es porque está inactivo pero mal escrito. Si no esta la 
    //i, se asume que está activo.
    $cuenta = 0;
    foreach($tuplas as $i => $fila){
        if(!in_array($fila[7], array('activo', 'inactivo'))){
            if(str_contains($fila[7], 'i')){
                $tuplas[$i][7] = 'inactivo';
                $cuenta += 1;
                
            }
            else{ //en caso contrario voy a asumir que esta activo nomas
                $tuplas[$i][7] = 'activo';
                $cuenta += 1;
            }
        }
    }
    echo "\n" . $cuenta .' correcciones en estado farmacia realizada (inactivo corregido)';
    return $tuplas;
}

function reparar_esencial_farmacia(array $tuplas): array{
    //
    //funcion que repara esencial de farmacia. Si no se sabe, se asume que no es esecnial
    // returna las tuplas editadas
    $cuenta = 0;
    foreach($tuplas as $i => $filas){
        if (!in_array($filas[8], array('0', '1', 0, 1))){
            $tuplas[$i][8] = '0';
            $cuenta += 1;
        }
    }
    echo "\n" . $cuenta.  ' correcciones esencial en farmacia realizada'. "\n" ;
    return $tuplas;
}

// atencion
function corregir_efectuada_atencion(array $tuplas): array{
    //
    // funcion que recibe las tuplas de atencion, revisa que si esta efectuada, el 
    // diagnostico no sea null también revisa que si no fue efectuada, el diagnóstico sea null
    $cuenta = 0;
    foreach($tuplas as $i => $fila){ //efectuada indice 4, diagnostico indice 3
        if($fila[4] == FALSE){
            if($fila[3] != ''){
                $tuplas[$i][3] = '';
                $cuenta += 1;
                
            }
        elseif($fila[4] == TRUE){
            if($fila[3] == '' || $fila[3] == Null){
                $tuplas[$i][3] = 'Diagnóstico realizado pero no informado';
                $cuenta += 1;
                
            }
        }
        }
    }
    echo "\n" . $cuenta . ' diagnosticos corregidos'. "\n" ;
    return $tuplas;
}

function corregir_estandarizados_csv(array $tuplas, string $csv): array{
    ///
    // función que recive tuplas, el nobmre del archivo y efectua las reparaciones correspondiente.
    // returna las tuplas reparadas como array y printea las reparaciones efectuadas.
    if ($csv == "Persona.csv"){
        $tuplas = reparar_profesion_persona($tuplas);
        $tuplas = reparar_rol_persona($tuplas);
    }

    elseif($csv == "Farmacia.csv"){
        $tuplas = reparar_tipo_farmacia($tuplas);
        $tuplas = reparar_estado_farmacia($tuplas);
        $tuplas = reparar_esencial_farmacia($tuplas);
    }

    elseif($csv == "Atencion.csv"){
        $tuplas = corregir_efectuada_atencion($tuplas);

    }
    elseif($csv == "Instituciones previsionales de salud.csv"){
        $tuplas = reparar_tipo_inst($tuplas);
        $tuplas = reparar_enlace($tuplas);

    }
    else{
        echo "\n" . 'No es posible realizar reparaciones' . "\n";
    }
    return $tuplas;
}

?>