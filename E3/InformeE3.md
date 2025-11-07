# Informe Entrega 3 - Bases de datos IIC2413

### Datos del Alumno
| **Apellidos**       | **Nombres**          | **Número de Alumno** |
|---------------------|----------------------|----------------------|
| Jara García         | Fernando Martín      | 2420286J             |

### 1. Esquema Relacional
El esquema relacional de la **ENTREGA PASDADA es:**
![Esquema relacional](esquema.png)
#### Restaurar BCNF
Esta versión del esquema tiene una sección que rompe con 3NF;
1. Farmacia (CodONU -> ClasONU rome 3NF)
Para Transformar el esquema a BCNF, debemos quitar CodOnu y ClasONU de la tabla farmacia y crear una nueva tabla que guarde este valor, de la forma:
-- FarmaciaONU(CodONU SERIAL PK, ClasONU VARCHAR NOT NULL) --

#### Homologar Atributos y Restricciones de Integridad
Por otro lado, los archivos excel tienen distintos nobmres para los atributos de la entrega pasada, además hay restricciones de integridad que cambia (por ejemplo, en el esquema E2 hay parametros que son BOOL que en los csv son INT). Para esto, actualizaremos las siguientes tablas:
- Persona()



![Esquema relacional BCNF](esquema_nuevo.png)

### 2. Revisión PHP
#### Personas.csv
##### Revisión de restricciones de integridad.
1. ID no puede ser NULL y es UNIQUE. Es un número.
2. RUN es text de longitud 10. el primer carácter es un número del 1 al 9, el último carácter es un número del 0 al 9 o K y el resto de caracteres son numeros del 0 al 9.
3. nombre es texto de longitud máxima 3 y no nulo.
4. apellido es texto de longitud máxima 3 y no nulo.
5. direccion es texto de lingitud 100. Puede ser nulo
6. correo es texto que contiene un "@", a la derecha del arroba debe ir un punto y un finalizador (.com o .cl por ejemplo). Puede ser nulo.
7. telefono es un INT cuyo primer dígito es un número dle 1 al 9.
8. 

persona."rol" puede ser null (no es paciente ni trabajador del centro), 'paciente', 'Staff médico', 'administraivo', 'Staff médico, paciente' o 'administrativo, paciente'.
 
##### Revisión de implicancias de Persona.csv 
Según el enunciado se deben tener las siguientes consideraciones (serán escritas en pseudocódigo):
1. si persona."rol" ILIKE "%Staff médico%" entonces profesion."Profesion" de dicha persona IS NOT NULL. En caso contrario, profesion."Profesion" IS NULL.
2. Si persona."rol" ILIKE "%Staff médico%" OR persona."rol" ILIKE "%administrativo%", entonces beneficiario."Beneficiario" = False AND beneficiario."IDPersona" = beneficiario."IDTitular".
3. persona."InstSalud" IS NULL o contiene un texto contenido en institucion."Nombre".



### Bibliografía:
1. Para los códigos se reusó las funciones/lienas propuestas de php en las ayudantías 8 y 9. Esto incluye la funcipon de lectura, escritura y elminiación de duplicados de archivos, entre otras.
2. Se usa para concatenar strings https://www.w3schools.com/php/php_string_concatenate.asp 
3. Explode para hacer los splits de python en strings. https://www.php.net/explode 
4. filexists para revisar si los csv existen y usarlo como condicino en utilidades.php https://www.php.net/manual/en/function.file-exists.php 
5. COmo no hay diccionarios, tuve que usar esto: https://stackoverflow.com/questions/6490482/are-there-dictionaries-in-php 
6. Para poder iterar las listas y acceder a los indices https://stackoverflow.com/questions/141108/how-to-find-the-foreach-index 
7. Resulta que en php las variablesd eclaradas globalmente (parametros.php) no las lee localmente las funciones, asi que tuve que usar global https://forum.exercism.org/t/global-variables-declared-outside-the-expected-solution-functions-are-not-recognized/9750/8
8. Para revisar números en restriccciones de integridad https://www.php.net/manual/en/function.is-numeric.php