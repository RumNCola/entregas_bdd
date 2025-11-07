<?php
include "utilidades.php";

echo '\n========================================================';
echo "\n";
echo '\n              EJECUTANDO MAIN - PURGA INICIADA';
echo "\n";
echo '\n========================================================';

// revisar_csv($archivo_personas, $carpeta_original);

// revisar_csv($archivo_orden, $carpeta_original);

// revisar_csv($archivo_medicamento, $carpeta_original);

revisar_csv($archivo_instituciones, $carpeta_original);

// revisar_csv($archivo_atencion, $carpeta_original);

// revisar_csv($archivo_arancel_fonasa, $carpeta_original);

// revisar_csv($archivo_arancel_dcc, $carpeta_original);

// revisar_csv($archivo_farmacia, $carpeta_original);


?>