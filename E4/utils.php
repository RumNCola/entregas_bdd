<?php
// Código extraido de la ayudantía 12
function conectarBD() {
    // $host = 'stonebraker.ing.uc.cl';
    $host = 'localhost';
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
?>