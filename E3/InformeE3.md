# Informe Entrega 3 - Bases de datos IIC2413

### Datos del Alumno
| **Apellidos**       | **Nombres**          | **Número de Alumno** |
|---------------------|----------------------|----------------------|
| Jara García         | Fernando Martín      | 2420286J             |

## INSTRUCCIONES DE EJECUCIÓN:
Para ejecutar el programa, es necesario conocer los archivos **de código** importantes.
1. `parametros.php` -> Módulo donde se definen variables que serán meros parametros: por ejemplo las rutas de los archivos o dominios de los attributos.
2. `utilidades.php` -> Módulo que contiene las funciones importantes para hacer la limpeiza de los archivos. Las funciones están **parametrizadas** y **estandarizadas**, es decir, son compatibles con todos los archivos csv y se adaptan a cada uno.
3. `reparaciones.php` -> modulo que tiene las funciones para reparar/corregir attr de tuplas.
4. `main.php` -> Archivo principal que incorpora utilidades y parametros para hacer la limpieza. Aquí se llama a las funciones para crear los log y csvs nuevos.
5. `carga.sql` -> Archivo que carga los resultados de `main.php` a la BDD.

Por otro lado, están las carpetas importantes y **necesarias para la ejecución**:
1. `csv_limpios` -> carpeta donde se guardan los CsvOK.
2. `csv_errores` -> carpeta donde se guardan los csvERR.
3. `csv_logs`    -> carpeta donde se guardan los LOGS.
4. `csv_originales` -> carpeta donde se guardan los csv originales.

Entonces, para ejecutar los programas, es necesario correr en la terminal, situado en la carpeta base, es decir, donde están los archivos php y las carpetas:

`php main.php`

**IMPORTANTE** CADA VEZ QUE SE CORRA EL PROGRAMA, SE DEBE BORRAR LOS CSVS GENERADOS; PUES TRAS CADA EJECUCIÖN SE ESCRIBIRA SOBRE ELLOS, SIN BORRAR EL CONTENIDO ANTERIOR.

Con esto, se crearán los csv necesarios para importar a la bdd. Posteriormente, desde PGadmin psql se debe ejecutar el archivo carga para cargarlo a la BDD deseada. Personalmente, lo cargo directamente desde la interfaz de PGadmin, donde pide cargar un DUMP.

Observación; Los archivos php ya están calibrados para entregar los resultados en **una sola ejecución**.

## Esquema Relacional y Modificaciones
El esquema relacional de la **ENTREGA PASDADA es:**
![Esquema relacional](esquema.png)
#### Restaurar BCNF
Esta versión del esquema tiene una sección que rompe con 3NF;
1. Farmacia (CodONU -> ClasONU rome 3NF)
Para Transformar el esquema a BCNF, debemos quitar CodOnu y ClasONU de la tabla farmacia y crear una nueva tabla que guarde este valor, de la forma:
-- FarmaciaONU(CodONU SERIAL PK, ClasONU VARCHAR NOT NULL) --
2. Dentro de los csvs, hay parametros que no cumplen la primera forma normal, entre ellos el rol de persona, es decir, si es paciente, staff o admin. Esto se resuelve mediante `carga.sql`.
3. La tabla Grupo ya no sirve, pues no se tiene descripciones de las instituciones de salud.
#### Homologar Atributos y Restricciones de Integridad
Por otro lado, los archivos excel tienen distintos nobmres para los atributos de la entrega pasada, además hay restricciones de integridad que cambia (por ejemplo, en el esquema E2 hay parametros que son BOOL que en los csv son INT). Para esto, los nombres de los atributos serán dictados por los csvs de esta entrega y no los de la pasada. Adicionalmente, algunas tablas ocupan distintos atributos como llave, como Atencion que en veaz de usar idpaciente/idmedico usan su rut. Estos cambios son contemplados en el cambio de esquema.

![Esquema relacional BCNF](esquema_nuevo.png)

## Revisión con PHP y modificaciones a archivos
(acciones realizadas a los CSV)
A través de PHP , se revisa lo siguiente:
1. Eliminar tuplas duplicadas
2. Corregir datos estandarizados, tipo {activo, inactivo}
3. Revisar, superficialmente, restricciones de integridad
4. revisar y corregir datos fuera de formato
5. Estandarizar tuplas

Especificamente, en `reparaciones.php` se repara lo siguiente:
1. Profesiones de persona adecuadas/bien escritas/correctas
2. Roles de persona bien escritos
3. Tipo de institución sea abierta o cerrada
4. Los enlaces deben tener la estructura de uno (https://)
5. Los tipos de la farmacia deben respetar sus posibles valores
6. El estado de la farmacia es 'activo' o 'inactivo'
7. esencial de la farmacia se repara en caso de ser incorrecto. Si es nulo se asume que es inactivo.
8. se asegura la consistencia de una atencion efectuada o no.

Además, hay diversos prints en la terminal que permiten visualizar como es el progreso de limpieza en cada archivo, en cada tramo del cleansing.

Además, en el php se revisan las siguientes reglas del negocio:

1. 'Una persona puede ser paciente, trabajar en el centro medico o ambos. Los trabajadores se dividen en staff medico o administrativo' se revisa dentro del código PHP, especificamente en corregir_estandariados.
2. 'Solo los médico/as tienen especialidad y realizan atenciones ḿedicas, emiten recetas y ordenes.' se revisa dentro de corregir_estandarizados en el php.
3. Los médicos pueden ser pacientes y titulares. Esto se revisa en el php.
4. En php se revisa que las personas tengan una isapre registrada válida.
5. La restricción 5 se respeta por definición

Regla de 6 al 10 se revisan en SQL.

Una vez realizado la anterior, se encuentran disponibles los archivos apra ser cargados a la base de datos, además de los LOGS y ERRores, que fueron minimizados gracias a las reparaciones.






### Bibliografía:
Observación: Las fuentes se usaron para entender como funcionan ciertos comandos de php, en ningún momento para copiar código y pegarlo, de ningún tipo o manera.
1. Para los códigos se reusó las funciones/lienas propuestas de php en las ayudantías 8 y 9. Esto incluye la funcipon de lectura, escritura y elminiación de duplicados de archivos, entre otras.
2. Se usa para concatenar strings https://www.w3schools.com/php/php_string_concatenate.asp 
3. Explode para hacer los splits de python en strings. https://www.php.net/explode 
4. filexists para revisar si los csv existen y usarlo como condicino en utilidades.php https://www.php.net/manual/en/function.file-exists.php 
5. Como no hay diccionarios, tuve que usar arrays 'dirigidos' https://stackoverflow.com/questions/6490482/are-there-dictionaries-in-php 
6. Para poder iterar las listas y acceder a los indices https://stackoverflow.com/questions/141108/how-to-find-the-foreach-index 
7. Resulta que en php las variablesd eclaradas globalmente (parametros.php) no las lee localmente las funciones, asi que tuve que usar global https://forum.exercism.org/t/global-variables-declared-outside-the-expected-solution-functions-are-not-recognized/9750/8
8. Para revisar números en restriccciones de integridad https://www.php.net/manual/en/function.is-numeric.php
9. Para la revisión de ruts, str_contains() https://www.php.net/manual/en/function.str-contains.php
10. Uso de substrings, para los ruts https://www.php.net/manual/es/function.substr.php 
11. Usé trim para eliminar las tuplas vacías https://www.php.net/manual/es/function.trim.php 
12. Como en las descripciones de los csv habían ; que no eran separadores, tuve que buscar esto para bypassearlos. https://www.php.net/manual/es/function.fgetcsv.php