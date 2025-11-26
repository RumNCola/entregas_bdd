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
Debemos generar un índice sobre todas las tablas y consultas que utilizen **RUN**. Notamos que este atributo está contenido unicamente en la tabla Persona, por lo que solo generamos el indice ahí.
#### Parte 1.b)
Tras una breve Query en Agenda, notamos que su llave primaria es sus tres atributos juntos (ID, Fecha, Hora), por lo que para crear un indice en su PK, debemos crear un índice sobre los tres atributos.

Adicionalmente, definimos (ID, Fecha, Hora) como llave primaria de la tabla Agenda.

#### Parte 1.c)
Dado que en la pregunta 1 no se ingresa, modifica o eliminan datos de la base, no se agregan transacciones entre la pregunta 1.a hasta la 1.g. Sin embargo, para la pregunta 2 en adelante, se agregarán transacciones para asegurar que no ocurran los problemas discutidos en la I2 (Asegurar ACID y prevenir problemas de lecturas sucias/no repetibles y sobreescrituras).

Las transacciones son manejadas a través del transaction manager de php. Se usarán a través de PHP, según el enlace citado en las fuentes del informe.

#### Parte 1.d) 
Para generar los tres tipos de documentos asociados a una atención, se crea una función para cada tipo de documento. Por último, se crea una cuarta función que llama las tres funciones anteriores y retorna todas las recetas de una atención.

#### Parte 1.e)
En el enunciado se habla de que el trigger se debe gatillar al terminar la atención (se declara como efectuada), sin embargo las siguientes preguntas solicitan que las ordenes y recetas sean ingresadas despues de que la orden sea declarada como efectuada. De esta manera, para implementar un trigger realmente funcional, este debe depender de las inserciones en la tabla ordenes, y medicamentos, pues ahí realmente ocurre la acción que debe gatillar lo solicitado en el enunciado.

De esta manera, para se crea un trigger para la tabla orden y otro para medicamentos, que tras una inserción de datos generan la receta.

#### Parte 1.f)
Para crear la vista, simplemente creé la query aosciada y agregué el CREATE VIEW Ficha AS (...). También, para agregar las especialidades desde el csv, desde el pgadmin usé la opcion de importar data directamente desde el csv. Para esto, dropee la tabla profesiones y la cree denuevo.

#### Parte 1.g)
Para validar los ingresos de datos a la base -tanto en formato como en contenido- se jhace una validación a través de php y las restricciones de integridad de las funciones de php definidas. Además, se hace una validación exhaustiva de los ruts a traves de validar_rut de utils.php

#### Parte 2
Para el manejo de usuarios, se crea INDEX.php que crea el cuadro para hacer el login. Este llama a validar_login.php que valida el login y revisa condiciones típicas: El user/contraseña no puedens ser vacios, deben ser numéricos, el usuario debe ser válido y la contraseña correctas. Además, se impide el uso de injections usando el contenido de la ayudantía 12. Por último, se revisa que se tengan las credenciales (ser admin o staff médico) y según el caso se lanza main_medico o main_admin o nada si el usuario no es ninguno.

Tanto para esta parte como para secciones futuras, el usuario debe ingresar RUNS sin puntos y con guión. En general los datos se deben ingresar tal cual comos e encuentran en la bdd o serán reconocidos como erroneos.

#### Pregunta 3 - Agendamiento de hora médica
El enunciado es poco claro respecto a como o quien selecciona la hora y el día. Dado que ezste dice que el sistema asigna una hora, a cada agendamiento se le asignará la hora más cercana disponible del doctor seleccionado.

Para buscar un doctor por nombre, se debe ingresar su primer o segundo nombre y su primer o segundo apellido.
Además, no importa si el nombre o apellido está completo, por ejemplo, ingresar histian varez permitirá identificar al doctor christian alvarez. una limitación es que se deben ingresar dos palabras y la primera debe corresponder a algun nombre y la segunda a algun apellido si o si.

Si se busca al doctor por especialidad, se seleccina al primero que aparece.

#### Pregunta 4
Aquí se despliega un menu donde se tiene que ingresar el run del paciente que se verifica, en caso de estar correcto se muestra la informacion solicitada en pantalla. Dado que esta es la recepción del paciente, es decir, todavía no se le atiende ni se le generan ordenes ni recetas, tuve que ajustar el trigger para que no dependa del cambio de efectuada y cuando se emite el bono solo se considera el valor de la atencion, no de ordenes ni de medicamentos, pues no tendría sentido.

En el bono de atencion médica del enunciado no se especifica que es "Atención", por lo que se asume que es la especialidad, pues el "código" solcitado anteriormente ya muestra información de la atención.

Para ver el bono me informe y considero lo siguiente:
1. El valor es valorColita, cuanto vale en si la consulta independiente de la prevension.
2. La bonificacion es el porcentaje que cubre la prevension. Si es particular, este valor es 0.
3. Copago es lo que paga finalmente el cliente, sería valorColita * bonificador para los isapre y valorfonasa para los fonasa.

De acuerdo a lo que busqué en google, el copago de alguien con isapre es valor * (1 - bonificacion). Al igual que en la E2, en caso de que una consulta médica no tenga valor definido, se asume que valdrá 50.000 y si la bonificacion no está definida, asume que será 0.

#### Pregunta 5:
Se implementa un menú simpel donde se ingresa el run del paciente. El sistema busca y printea sus atenciones,
se selecciona un id de la atencion que se desee cancelar (la elimina de la tabla).

#### Pregunta 6
Esta pregunta la dejé a la mitad. El médico ingresa el run del usuario y el diagnostico y se hacen las revisiones pertinentes para ingresar el diagnostico en caso de ser válido.

### 2. Referencias a documentación externa válida
<!-- Registra aquí fuentes externas de información utilizada (manuales, videos, etc. -->
#### Para la pregunta 1:
1. Para agregarle la PK a la tabla Agenda, usando constraint, se consulto: https://www.w3schools.com/sql/sql_primarykey.ASP 
Para la generación de recetas se consultó:
1. Mi consulta entregada en la E2
2. https://www.geeksforgeeks.org/sql-server/how-to-use-string_agg-to-concatenate-strings-in-sql-server Para usar string_agg
3. https://stackoverflow.com/questions/36028908/postgresql-newline-character Para hacer los newlines (Me costó harto encontrar uno que funcionara)

Para los StoredProcedure/Functions de la emisión de recetas se consultó:
1. https://www.w3schools.com/sql/func_sqlserver_coalesce.asp COALESCE util como siempre para manejar los nulls en la consulta de recetas (hay muchas consultas que no tienen receta psicotropica y al ser null rompian la consulta).

Para agregar el rigger de 1.e, tuve que consultar:
1. https://stackoverflow.com/questions/42920998/pl-pgsql-perform-vs-execute porque no me estaba funcionando el llamado de las funciones en el trigger y funciones. No sabía que se hacian de distinta forma (perform/execute) según el caso.

En general, volví a usar mis códigos de la E2/E3, asi que todo lo citado en esa entrega se aplica también a esta.

#### Para la pregunta 2 y posteriores. 
1. Se usa gran parte de la ayudantía 12 para elaborar index.php y validar_login.php
2. En general, se usó código de la entrega 3 del semestre anterior. Esto bajo la issue donde el profesor Bustos autorizó su uso.
3. Para manejar las transacciones y las excepciones, se consultó esta página https://www.php.net/manual/en/pdo.transactions.php 
4. https://www.php.net/manual/en/pdostatement.fetch.php Para recibir las querys como arrays y mostrarlas


### 3. Instrucciones de ejecución de Entrega
<!-- Indica las instrucciones para ejecutar la aplicación web adicionales al URL -->
Para ejecutar la entrega se debe hacer lo siguiente:
1. La base de datos ya está configurada, pero si se deseara hacer desde cero se debería ejecutar el main.sql,
cada linea por separado de preferencia. despues se ha de truncar la tabla profesion y se le agrega la columna especialidad. Por último, las especialidades se cargan desde la funcionalidad de importar de pgadmin. COn esto la base queda lista. 
Para esto, tqambipen incluí el dumpe4actualizado que permite acelerar este proceso en caso de querer hacerlo desde cero.
2. Es necesario acceder a stonebraker.ing.uc.cl/jara.fernando.e4/E4 para acceder a la página.

### 4. Observaciones importantes
1. En las Issues se menciona que para presentar las recetas o documentos se pueden descargar los archivos o presentarlos en pantalla. En mi tarea escogí mostrarlos en pantalla.
2. Dado que no es requisito de la tarea y no tengo conocimientos profundos del tema, no impelmente un diseño css por lo que los sitios serán feos, pero funcionales.
3.