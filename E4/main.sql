--1.a. Creación del indice sobre RUN
CREATE INDEX indice_run ON public."Persona"("RUN");

--1.b. Creación del Índice en Agenda
CREATE INDEX indice_agenda ON public."Agenda"("ID", "Fecha", "Hora");

--1.c. Creación de transacciones

--1.d. Stored Procedure

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

