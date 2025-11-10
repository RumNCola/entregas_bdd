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

### 2. Referencias a documentación externa válida
<!-- Registra aquí fuentes externas de información utilizada (manuales, videos, etc. -->


### 3. Instrucciones de ejecución de Entrega
<!-- Indica las instrucciones para ejecutar la aplicación web adicionales al URL -->

### 4. Observaciones adicionales