# Informe Entrega 2 - Bases de datos IIC2413

### Datos del Alumno
| **Apellidos**       | **Nombres**          | **Número de Alumno** |
|---------------------|----------------------|----------------------|
| Jara García         | Fernando Martín      | 2420286J             |

### 2.1 Esquema Relacional
A continuación, se adjunta el esquema relacional proporcionado por PGAdmin. Se configuró este mismo para que muestre cardinalidades en notación CHEN, sin embargo, PGAdmin no tiene la opcion de mostrar el esquema en el formato visto en la E1 (Rectangulos, Rombos y Círculos).

![Esquema relacional](esquema.png)

### 2.2 Consultas SQL
#### 2.2.0 Preliminares
Para ejecutar las consultas, es necesario conectarse al servidor y posteriormente abrir la query tool. Una vez abierta, se pega el contenido del archivo SQL en la query tool y se ejecuta. Alternativamente se puede hacer con CMD y psql, pero la primera versión es más simple.

**IMPORTANTE**: Para ejecutar las querys presentadas en el informe, es necesario correr la siguiente query, que **renombra las tablas** para
manejarlas con mayor facilidad:
- `consultas/SQLs/consulta_inicial.sql`
**SI NO SE CORRE ESTA QUERY; LAS CONSULTAS ARROJARAN ERROR**


#### 2.2.1 Estadísticas de Pacientes
a) Para encontrar la estadísitica por institución de salud previsional, se creó la siguiente consulta:
- `consultas/SQLs/beneficiariosporinstitucion.sql`
Cuyos resultados se encuentran en:
- `consultas/Outputs/beneficiariosporinstitucion.txt`
Observción: Dado que el enunciado solicita encontrar las estadísticas **por institución**, no se considera a 'particulares' como una institución. Es decir, aquellas persons que no tienen provisión no son consideradas dentro de este cálculo.

b) Para encontrar la lista de titulares que son pacientes y tienen al menos un beneficiario que no es paciente se realizó la siguiente consulta:
- `consultas/SQLs/beneficiariosnocliente.sql`
Los resultados de dicha consulta se encuentran en:
- `consultas/Outputs_txt/beneficiariosnocliente.txt`

#### 2.2.2 Estadísticas de atenciones
a) Los 5 pacientes con mayor numero de diagnosticos distintos se encuentran en el siguiente archivo:
- `consultas/SQLs/top5dx.sql`
Mientras que los resultados de dicha consulta aparecen en el siguiente archivo de texto.
- `consultas/Outputs_txt/top5dx.txt`

Observación: Para que un diagnósito sea efectivo, se consideran solo las atenciones que han sido efectuadas. Considerar atenciones efectuadas no tiene sentido.

b) Los 5 pacientes a los que se les ha recetado más veces el mismo medicamento se encuentran en:
- `consultas/SQLs/top5farma.sql`
Y sus resultados en:
- `consultas/Outputs_txt/top5dx.txt`

#### 2.2.3 Atenciones perdidas
La query para atenciones perdidas se encuentra en:
- `consultas/SQLs/asistenciasperdidas.sql`
 
Mientras que sus resultados aparecen en:
-  `consultas/Outputs_txt/asistenciasperdidas.txt` 

Observación: Dado que PGAdmin no entrega una opción para leer multiples queries de una, copié y pegue los resultados de las tres queries por separado en un mismo txt.

#### 2.2.4 Medicamentos y exámenes más recetados
a) Los 5 medicamentos más solicitados, **considerando empates**, se encuentran en:
- `consultas/SQLs/top5farmacia.sql`
y sus resultados en:
- `consultas/Outputs_txt/top5farmacia.txt`

b) Los 5 exámenes más solicitados se encuentran en:
- `consultas/SQLs/top5orden.sql`
y sus resultados en:
- `consultas/Outputs_txt/top5orden.txt`
Observación: Para definir si una Orden es exámen, se considera que un exámen contiene la palabra 'exámen' o 'examen' en su atributo Orden."ConsAtMedica".

#### 2.2.5 Ingresos del Centro médico
Los ingresos y atenciones del centro médico, dividido por año, mes y previsión se encuentran en el archivo:
- `consultas/SQLs/ingresos.sql`
Cuyos resultados estan presentes en el siguiene txt:
- `consultas/Outputs_txt/ingresos.txt`

Observación: Para que una aención sea considerada en la cuenta y realmente genere ingresos, **esta debe ser efectuada**. Es decir, solo cuentan aquellas atenciones cuyo "Efectuada" es TRUE.

Observación 2: El ingreso del centro es independiente de la prevensión de las personas. Si una atención vale 10.000, independiente de la prevensión, la consulta seguirá valiendo 10.000, lo unico que cambia es cuanto de esos 10.000 paga el usuario y cuanto paga la previsión. Para esto se considerarán solo los valores DCColita. Formulaicamente: Valor atencion = Valor DCColita = Cobro a Paciente + Cobro a Prevención. 

Observación 3: En el output, los particulares aparecen con "InstSalud" null.

Observación 4: Para calcular el ingreso, se consideran los ingresos de venta de medicamentos.

#### 2.2.6 Generación de Recetas
Para la generación de recetas, psicotrópicas como no, se usa el siguiente archivo.
- `consultas/SQLs/recetas.sql`
Cuyos resultados estan presentes en el siguiente txt:
- `consultas/Outputs_txt/recetas.txt`

Por otro lado, para generar las ordenes se usa el siguiente sql:
- `consultas/SQLs/ordenes.sql`
Cuyos resultados estan presentes en el siguiente txt:
- `consultas/Outputs_txt/ordenes.txt`

Observación: Para este inciso, las tres queries usan ids dinstnos de atencion, pues fue dificil encontrar un caso que tenga recetas psico, no psico y ordenes al mismo tiempo.

#### 2.2.7 Valorización de atención y diagnóstico
Para calcular el valor de la atención enfrente grandes problemas: los números se inflablan mucho respecto al calculo manual. Para esto, dividí los froms usando subquerys y usé las funciones COALESCE y ILIKE referenciadas/citadas al final del markdown. El sql de esta sección se encuentra en:
- `consultas/SQLs/valor atencion.sql`
Mientras que el output del query está en:
- `consultas/Outputs_txt/valor atencion.txt`

### 2.3 Mejoras al esquema
De acuerdo a lo visto en clases, la herramienta más útil para prevenir anomalias es la incorporación de formas normales, idealmente BCNF. De esta menra, para prevenir anomalías debemos asegurarnos que el esquema cumpla con dicha forma normal.

Basandonos en lo visto en la entrega anterior, podemos observar que el esquema actual **no cumple con BCNF**. Por ejemplo, _Farmacia_ no cumple con BCNF, pues hay una dependencia funcional que lo rompe: CodOnu -> ClasOnu.

Adicionalmente, podemos prevenir anomalías mejorando el manejo de atributos Null. Por ejemplo, las personas que no tienen previsión poseen "InstSalud" Null, quienes también poseen beneficiario."IDtitular" Null. Este manejo de valores nulos es riesgoso para la búsqueda y manejo de la base de datos, además de ser un peligro a la hora de modificar la previsión de una persona y su familia, pues se podría cambiar "InstSalud" pero no cambiar su información de la tabla "beneficiario", lo que generaría contradicciones y anomalías de modificación.

Además, para evitar la redundancia, se podría dividir tablas como "persona" usando las jerarquías propuestas en la entrega anterior. De esta forma se elimina información inecesaria como persona.medico o medicamentos.psicotrópico, analogamente.

Por otro lado, basado en las últimas clases, se podrían realizar diversas mejoras a la base de datos asociado a vistas materializadas y triggers. Para evitar anomalías de modificación, se podrían implementar triggers que aseguren que los cambios, de la previsión de una persona por ejemplo, respeten y acualizen su información en beneficiario. Asimismo, dado las consultas que ha solicitado la empresa (sección 2.2), el uso de vistas materialziadas permitiría a la empresa acceder con mayor rapidez a las consultas asociadas a ingresos mensuales, perdida de atenciones y la emisión de papeleo.

Por último, para la emisión de boletas al paciente, el esquema **no cuenta con códigos de autorización**. Estos deben ser implementados para las recetas psicotrópicas y se pueden incluir como atributo de medicamentos. Estos se pueden crear a través de un Trigger que genere un número aleatorio no repetido para cada receta psicotrópica que se emita.

### REFERENCIAS CÓDIGO EXTERNO
En el desarrollo de la tarea, consulte las siguientes páginas para saber como usuar diversas funcionalidades de SQL:
1. COALESCE: https://stackoverflow.com/questions/16840522/replacing-null-with-0-in-a-sql-server-query
Esta función la usé en valor atencion e ingresos, pues me permitia definir que los costos null fueran 0 o 50.000 segun corresponda.

2. ILIKE: https://docs-getdbt-com.translate.goog/sql-reference/ilike?_x_tr_sl=en&_x_tr_tl=es&_x_tr_hl=es&_x_tr_pto=tc
El ILIKE lo use en valor atencion para asegurarme de que si una orden contenia la palabra consulta, independiente de si c era mayuscula o no, contara para cierto filtro.

3. CONCAT para concatenar strings en la pregunta de emision de recetas/ordenes. https://www.w3schools.com/sql/func_sqlserver_concat.asp 

4. STRING_AGG para juntar los strings de los medicamentos/ordenes que se les hacen a una persona: https://www.geeksforgeeks.org/sql-server/how-to-use-string_agg-to-concatenate-strings-in-sql-server 