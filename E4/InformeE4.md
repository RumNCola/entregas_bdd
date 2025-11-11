# Informe Entrega 4 - Bases de datos IIC2413

### Datos del Alumno
| **Apellidos**       | **Nombres**          | **Número de Alumno** |
|---------------------|----------------------|----------------------|
| Jara García         | Fernando Martín      | 2420286J             |

### 0. Esquema de la base
![Esquema E4](esquema_e4.png)

### 1. Descripción de la solución 
<!-- El análisis de la solución, describe aquí qué y como usaste  HTML, CSS, PHP, SQL, PSQL. 
Cómo rescatas los datos de la base para desplegarlo en los formularios hy viceversa-->
#### Parte 1.a)
Debemos generar un índice sobre todas las tablas y consultas que utilizen **RUN**. Notamos que este atributo está contenido unicamente en la tabla Persona.
#### Parte 1.b)
Tras una breve Query en Agenda, notamos que su llave primaria es sus tres atributos juntos (ID, Fecha, Hora), por lo que para crear un indice en su PK, debemos crear un índice sobre los tres attributos. 
#### Parte 1.c)

#### Parte 1.d) 
Para generar los tres tipos de documentos asociados a una atención, se crea una función para cada tipo de documento. Por último, se crea una cuarta función que llama las tres funciones anteriores y retorna todas las recetas de una atención.

#### Parte 1.e)

#### Parte 1.f)
Para crear la vista, simplemente creé la query aosciada y agregué el CREATE VIEW Ficha AS (...).

#### Parte 1.g)

### 2. Referencias a documentación externa válida
<!-- Registra aquí fuentes externas de información utilizada (manuales, videos, etc. -->
Para la generación de recetas se consultó:
1. Mi consulta entregada en la E2
2. https://www.geeksforgeeks.org/sql-server/how-to-use-string_agg-to-concatenate-strings-in-sql-server Para usar string_agg
3. https://stackoverflow.com/questions/36028908/postgresql-newline-character Para hacer los newlines (Me costó harto encontrar uno que funcionara)
Para los StoredProcedure/Functions de la emisión de recetas se consultó:
1. https://www.w3schools.com/sql/func_sqlserver_coalesce.asp COALESCE util como siempre para manejar los nulls en la consulta de recetas (hay muchas consultas que no tienen receta psicotropica y al ser null rompian la consulta).



### 3. Instrucciones de ejecución de Entrega
<!-- Indica las instrucciones para ejecutar la aplicación web adicionales al URL -->

### 4. Observaciones adicionales