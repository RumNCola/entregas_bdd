--1.a. Creación del indice sobre RUN
CREATE INDEX indice_run ON public."Persona"("RUN");

--1.b. Creación del Índice en Agenda
CREATE INDEX indice_agenda ON public."Agenda"("ID", "Fecha", "Hora");

--1.c. Creación de transacciones

--1.d. Stored Procedure

--consulta receta
SELECT ('Paciente: ' || personas.paciente_nombre ||' ' || personas.paciente_apelllido || E'\n' ||
	   'RUN: ' || personas.paciente_RUN || E'\n' ||
	   'Diagnóstico: ' || personas.diagnostico || E'\n' ||
	    string_agg(meds."Medicamento" || ' - ' || meds."Posologia", E'\n' ORDER BY meds."Medicamento")
		|| E'\n' || E'\n' ||
		'Fecha: ' || personas.fecha
		|| E'\n' ||
		'Dr: ' || personas.apellido_doctor || E'\n' ||
		personas."firma") AS Recet
FROM 
	public."medicamentos" AS meds LEFT JOIN
	public."Farmacia" AS farm ON farm."Nombre" = meds."Medicamento" LEFT JOIN (
		SELECT atencion."ID" AS "ID", paciente."Nombres" AS paciente_nombre, paciente."Apellidos" AS paciente_apelllido,
			paciente."RUN" AS paciente_RUN, doctor."Apellidos" AS apellido_doctor, doctor."RUN", profesion."firma", 
			atencion."Diagnostico" AS diagnostico, atencion."fecha" AS fecha
		FROM public."Atencion" AS atencion LEFT JOIN public."Persona" AS paciente ON atencion."IDPaciente" = paciente."ID"
			LEFT JOIN public."Persona" AS doctor ON doctor."ID" = atencion."IDMedico" LEFT JOIN public."profesion" AS profesion ON
			profesion."ID" = doctor."ID"
		WHERE atencion."Efectuada" = True AND atencion."ID" = 1
	) AS personas ON meds."IDAtencion" = personas."ID"
WHERE meds."Psicotropico" = False 
GROUP BY personas.paciente_nombre, personas.paciente_apelllido, personas.paciente_RUN,
	personas.fecha, personas.apellido_doctor, personas."firma", personas.diagnostico
LIMIT 1;

--consulta receta psicotrópica
SELECT ('Paciente: ' || personas.paciente_nombre ||' ' || personas.paciente_apelllido || E'\n' ||
	   'RUN: ' || personas.paciente_RUN || E'\n' ||
	   'Diagnóstico: ' || personas.diagnostico || E'\n' ||
	    string_agg(meds."Medicamento" || ' - ' || meds."Posologia", E'\n' ORDER BY meds."Medicamento")
		|| E'\n' || E'\n' ||
		'Fecha: ' || personas.fecha
		|| E'\n' ||
		'Dr: ' || personas.apellido_doctor || E'\n' ||
		personas."firma") AS Recet
FROM 
	public."medicamentos" AS meds LEFT JOIN
	public."Farmacia" AS farm ON farm."Nombre" = meds."Medicamento" LEFT JOIN (
		SELECT atencion."ID" AS "ID", paciente."Nombres" AS paciente_nombre, paciente."Apellidos" AS paciente_apelllido,
			paciente."RUN" AS paciente_RUN, doctor."Apellidos" AS apellido_doctor, doctor."RUN", profesion."firma", 
			atencion."Diagnostico" AS diagnostico, atencion."fecha" AS fecha
		FROM public."Atencion" AS atencion LEFT JOIN public."Persona" AS paciente ON atencion."IDPaciente" = paciente."ID"
			LEFT JOIN public."Persona" AS doctor ON doctor."ID" = atencion."IDMedico" LEFT JOIN public."profesion" AS profesion ON
			profesion."ID" = doctor."ID"
		WHERE atencion."Efectuada" = True AND atencion."ID" = 1
	) AS personas ON meds."IDAtencion" = personas."ID"
WHERE meds."Psicotropico" = True 
GROUP BY personas.paciente_nombre, personas.paciente_apelllido, personas.paciente_RUN,
	personas.fecha, personas.apellido_doctor, personas."firma", personas.diagnostico
LIMIT 1;

-- consulta orden
SELECT 
	('Paciente: ' || personas.paciente_nombre ||' ' || personas.paciente_apelllido || E'\n' ||
 	'RUN: ' || personas.paciente_RUN || E'\n' ||
 	'Diagnóstico: ' || personas.diagnostico || E'\n' ||
  	string_agg(ara."ConsAtMedica", ' ' ORDER BY ara."ID") || E'\n' || E'\n' ||
	'Fecha: ' || personas.fecha
	|| E'\n' ||
	'Dr: ' || personas.apellido_doctor || E'\n' ||
	personas."firma")
	AS Ordenes_de_examen
FROM public."Orden" AS ord LEFT JOIN public."Arancel" AS ara ON ord."IDArancel" = ara."ID"
LEFT JOIN (
		SELECT atencion."ID" AS "ID", paciente."Nombres" AS paciente_nombre, paciente."Apellidos" AS paciente_apelllido,
			paciente."RUN" AS paciente_RUN, doctor."Apellidos" AS apellido_doctor, doctor."RUN", profesion."firma", 
			atencion."Diagnostico" AS diagnostico, atencion."fecha" AS fecha
		FROM public."Atencion" AS atencion LEFT JOIN public."Persona" AS paciente ON atencion."IDPaciente" = paciente."ID"
			LEFT JOIN public."Persona" AS doctor ON doctor."ID" = atencion."IDMedico" LEFT JOIN public."profesion" AS profesion ON
			profesion."ID" = doctor."ID"
		WHERE atencion."Efectuada" = True AND atencion."ID" = 1
	) AS personas ON personas."ID" = ord."IDAtencion"
GROUP BY personas.paciente_nombre, personas.paciente_apelllido, personas.paciente_RUN,
	personas.fecha, personas.apellido_doctor, personas."firma", personas.diagnostico
LIMIT 1;


--1.e. Trigger al SP

--1.f. Vista Ficha 
-- ACTUALIZAR CUANDO ME RESPONDAN LA ISSUE DE ESPECIALDIAD DEL MEDICO

CREATE VIEW Ficha AS (
SELECT P."ID", P."Nombres" AS nombre_paciente, P."Apellidos" AS apellido_paciente,
A."fecha", A."Diagnostico", medico."Nombres" AS medico_nombre, medico."Apellidos" AS medico_apellido, 
medico."profesion" AS medico_especialidad
FROM public."Atencion" AS A LEFT JOIN public."Persona" AS P ON A."IDPaciente" = P."ID"
LEFT JOIN (
	SELECT persona."ID", persona."Nombres", persona."Apellidos", profesion."profesion"
	FROM public."Persona" AS persona LEFT JOIN public."profesion" AS profesion ON profesion."ID" = persona."ID"
) AS medico ON medico."ID" = A."IDMedico"
WHERE A."Efectuada" = True
ORDER BY P."ID" ASC, A."fecha" DESC
);

--1.e. Validación de Ingreso de datos

