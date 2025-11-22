-- Consulta inicial - extraido de mi entrega 2 para facilitar las busquedas
-- NOTA IMPORTANtE: No estan todas las tablas porque algunas ya son accesibles de la forma 'directa'
-- como profesion, por ejemplo.
ALTER TABLE public."Arancel" RENAME TO arancel;
ALTER TABLE public."Atencion" RENAME TO atencion;
ALTER TABLE public."Farmacia" RENAME TO farmacia;
ALTER TABLE public."Grupo" RENAME TO  grupo;
ALTER TABLE public."InstituciondeSalud" RENAME TO instituciondesalud;
ALTER TABLE public."Orden" RENAME TO orden;
ALTER TABLE public."Persona" RENAME TO persona;
ALTER TABLE public."Planes" RENAME TO planes;
ALTER TABLE public."Rol" RENAME TO rol;
ALTER TABLE public."Agenda" RENAME TO agenda;

-- LO USÉ HARTO PARA PROBAR COMO AGREGAR LAS ESpECLAIDADES Y ELIMINAR LOS DUPLICADOS EN AGENDA.
-- DROP TABLE agenda CASCADE;
-- DROP TABLE orden CASCADE;
-- DROP TABLE arancel CASCADE;
-- DROP TABLE atencion CASCADE;
-- DROP TABLE farmacia CASCADE;
-- DROP TABLE grupo CASCADE;
-- DROP TABLE instituciondesalud CASCADE;
-- DROP TABLE persona CASCADE;
-- DROP TABLE planes CASCADE;
-- DROP TABLE rol CASCADE;
-- DROP TABLE beneficiario CASCADE;
-- DROP TABLE medicamentos CASCADE;
-- DROP TABLE profesion CASCADE;

--1.a. Creación del indice sobre RUN
CREATE INDEX indice_run ON persona("RUN");

--1.b. Creación del Índice en Agenda
ALTER TABLE agenda
ADD CONSTRAINT PK_Agenda PRIMARY KEY ("ID", "Fecha", "Hora");
CREATE INDEX indice_agenda ON agenda("ID", "Fecha", "Hora");

--1.c. Creación de transacciones

--1.d. Stored Procedure
-- Emisión de receta no psic
CREATE OR REPLACE FUNCTION emitir_receta(id_paciente integer)
RETURNS TABLE (Receta text) AS $$
BEGIN
    RETURN QUERY
    --consulta receta
    SELECT ('Paciente: ' || personas.paciente_nombre ||' ' || personas.paciente_apelllido || E'\n' ||
        'RUN: ' || personas.paciente_RUN || E'\n' ||
        'Diagnóstico: ' || personas.diagnostico || E'\n' ||
            string_agg(meds."Medicamento" || ' - ' || meds."Posologia", E'\n' ORDER BY meds."Medicamento")
            || E'\n' || E'\n' ||
            'Fecha: ' || personas.fecha
            || E'\n' ||
            'Dr: ' || personas.apellido_doctor || E'\n' ||
            personas."firma") AS Receta
    FROM 
        medicamentos AS meds LEFT JOIN
        farmacia AS farm ON farm."Nombre" = meds."Medicamento" LEFT JOIN (
            SELECT atencion."ID" AS "ID", paciente."Nombres" AS paciente_nombre, paciente."Apellidos" AS paciente_apelllido,
                paciente."RUN" AS paciente_RUN, doctor."Apellidos" AS apellido_doctor, doctor."RUN", profesion."firma", 
                atencion."Diagnostico" AS diagnostico, atencion."fecha" AS fecha
            FROM atencion LEFT JOIN persona AS paciente ON atencion."IDPaciente" = paciente."ID"
                LEFT JOIN persona AS doctor ON doctor."ID" = atencion."IDMedico" LEFT JOIN profesion ON
                profesion."ID" = doctor."ID"
            WHERE atencion."Efectuada" = True AND atencion."ID" = id_paciente
        ) AS personas ON meds."IDAtencion" = personas."ID"
    WHERE meds."Psicotropico" = False 
    GROUP BY personas.paciente_nombre, personas.paciente_apelllido, personas.paciente_RUN,
        personas.fecha, personas.apellido_doctor, personas."firma", personas.diagnostico
    LIMIT 1;
END;
$$ LANGUAGE plpgsql;

-- Emisión de receta Psicotrópica
CREATE OR REPLACE FUNCTION emitir_receta_psicotropica(id_paciente integer)
RETURNS TABLE (Receta_psicotropica text) AS $$
BEGIN
    RETURN QUERY
    SELECT ('Paciente: ' || personas.paciente_nombre ||' ' || personas.paciente_apelllido || E'\n' ||
        'RUN: ' || personas.paciente_RUN || E'\n' ||
        'Diagnóstico: ' || personas.diagnostico || E'\n' ||
            string_agg(meds."Medicamento" || ' - ' || meds."Posologia", E'\n' ORDER BY meds."Medicamento")
            || E'\n' || E'\n' ||
            'Fecha: ' || personas.fecha
            || E'\n' ||
            'Dr: ' || personas.apellido_doctor || E'\n' ||
            personas."firma") AS Receta_psicotropica
    FROM 
        medicamentos AS meds LEFT JOIN
        farmacia AS farm ON farm."Nombre" = meds."Medicamento" LEFT JOIN (
            SELECT atencion."ID" AS "ID", paciente."Nombres" AS paciente_nombre, paciente."Apellidos" AS paciente_apelllido,
                paciente."RUN" AS paciente_RUN, doctor."Apellidos" AS apellido_doctor, doctor."RUN", profesion."firma", 
                atencion."Diagnostico" AS diagnostico, atencion."fecha" AS fecha
            FROM atencion LEFT JOIN persona AS paciente ON atencion."IDPaciente" = paciente."ID"
                LEFT JOIN persona AS doctor ON doctor."ID" = atencion."IDMedico" LEFT JOIN profesion ON
                profesion."ID" = doctor."ID"
            WHERE atencion."Efectuada" = True AND atencion."ID" = id_paciente
        ) AS personas ON meds."IDAtencion" = personas."ID"
    WHERE meds."Psicotropico" = True 
    GROUP BY personas.paciente_nombre, personas.paciente_apelllido, personas.paciente_RUN,
        personas.fecha, personas.apellido_doctor, personas."firma", personas.diagnostico
    LIMIT 1;
END;
$$ LANGUAGE plpgsql;

-- emision de ordenes
CREATE OR REPLACE FUNCTION emitir_orden(id_paciente integer)
RETURNS TABLE(Ordenes_de_examen text) AS $$
BEGIN
    RETURN QUERY
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
    FROM orden AS ord LEFT JOIN arancel AS ara ON ord."IDArancel" = ara."ID"
    LEFT JOIN (
            SELECT atencion."ID" AS "ID", paciente."Nombres" AS paciente_nombre, paciente."Apellidos" AS paciente_apelllido,
                paciente."RUN" AS paciente_RUN, doctor."Apellidos" AS apellido_doctor, doctor."RUN", profesion."firma", 
                atencion."Diagnostico" AS diagnostico, atencion."fecha" AS fecha
            FROM atencion LEFT JOIN persona AS paciente ON atencion."IDPaciente" = paciente."ID"
                LEFT JOIN persona AS doctor ON doctor."ID" = atencion."IDMedico" LEFT JOIN profesion ON
                profesion."ID" = doctor."ID"
            WHERE atencion."Efectuada" = True AND atencion."ID" = 1
        ) AS personas ON personas."ID" = ord."IDAtencion"
    GROUP BY personas.paciente_nombre, personas.paciente_apelllido, personas.paciente_RUN,
        personas.fecha, personas.apellido_doctor, personas."firma", personas.diagnostico
    LIMIT 1;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION emitir_documentos(id_paciente integer)
RETURNS TABLE(Documentos_consulta text) AS $$
BEGIN 
    RETURN QUERY
    SELECT ('Recetas: ' || E'\n' ||  COALESCE(R.Receta, 'No hay recetas') || E'\n' || E'\n' || E'\n'|| 
	   'Recetas Psic: ' ||  E'\n' || COALESCE(RP.Receta_psicotropica, 'No hay recetas psic.') || E'\n' || E'\n' || E'\n'|| 
	   'Ordenes de examen: ' ||  E'\n' || COALESCE(O.Ordenes_de_examen, 'No hay ordenes de examen')) AS Documentos_consulta
    FROM emitir_receta(id_paciente) AS R, emitir_receta_psicotropica(id_paciente) AS RP, emitir_orden(id_paciente) AS O;
END;
$$ LANGUAGE plpgsql;

--1.e. Trigger al SP
-- Tuve que crear estas tres vistas porque sino el código de atencion_terminada quedaba gigante y feo.
CREATE OR REPLACE VIEW hay_recetas AS (
    SELECT medicamentos."IDAtencion" as "IDAtencion", 
    COUNT(medicamentos."IDAtencion") as "cuenta"
    FROM medicamentos
    GROUP BY medicamentos."IDAtencion" 
);

CREATE OR REPLACE VIEW hay_ordenes AS (
    SELECT orden."IDAtencion" as "IDAtencion", 
    COUNT(orden."IDAtencion") as "cuenta"
    FROM orden
    GROUP BY orden."IDAtencion"
);

CREATE OR REPLACE VIEW diagnosticadas_efectuadas AS (
    SELECT atencion."ID" as "ID", (COALESCE(atencion."Efectuada", FALSE)) 
    AND (atencion."Diagnostico" IS NOT NULL) AS "diagnosticadas_efectuada"
    FROM atencion
);

-- Esta función la cree para saber si la atención terminó y tiene algún documento (receta y/o orden)
-- emitido. para esto, vemos efectuada =True y que exista almenos una orden o medicamento asociado.
-- Para ahorrar el código, usé las tres vistas anteriores.
CREATE OR REPLACE FUNCTION atencion_terminada(id_atencion integer)
RETURNS boolean AS $$
DECLARE
    criterio_atencion boolean;
    
    auxiliar_orden int;
    criterio_orden boolean;

    auxiliar_medicamento int;
    criterio_medicamento boolean;
     
BEGIN
    criterio_atencion := (
        SELECT diagnosticadas_efectuadas."diagnosticadas_efectuada"
        FROM diagnosticadas_efectuadas
        WHERE diagnosticadas_efectuadas."ID" = id_atencion
        );

    auxiliar_orden := (
        SELECT COALESCE(hay_ordenes."cuenta", 0)
        FROM hay_ordenes
        WHERE hay_ordenes."IDAtencion" = id_atencion
    );

    auxiliar_medicamento := (
        SELECT COALESCE(hay_recetas."cuenta", 0)
        FROM hay_recetas
        WHERE hay_recetas."IDAtencion" = id_atencion
    );

    IF (auxiliar_medicamento IS NULL) THEN
        criterio_medicamento := FALSE;
    ELSIF auxiliar_medicamento > 0 THEN
        criterio_medicamento := TRUE;
    ELSE
        criterio_medicamento := FALSE;
    END IF;

    IF (auxiliar_orden IS NULL) THEN
        criterio_orden := FALSE;
    ELSIF auxiliar_orden > 0 THEN
        criterio_orden := TRUE;
    ELSE
        criterio_orden := FALSE;
    END IF;

    IF (criterio_orden OR criterio_medicamento) AND criterio_atencion THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE plpgsql;

-- Esta función es para que funcione el trigger
CREATE OR REPLACE FUNCTION func_trigger_documentos()
RETURNS trigger AS $$
DECLARE
    realizada boolean;
BEGIN
    IF NEW."Efectuada" = TRUE AND (OLD."Efectuada" = FALSE OR OLD."Efectuada" IS NULL) THEN
        realizada := atencion_terminada(NEW."ID");
    
        IF realizada THEN
            PERFORM emitir_documentos(NEW."IDPaciente");
        END IF;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- el triggerr
CREATE OR REPLACE TRIGGER trigger_documentos 
AFTER UPDATE ON atencion
FOR EACH ROW
EXECUTE FUNCTION func_trigger_documentos();

--1.f. Vista Ficha 
CREATE OR REPLACE VIEW Ficha AS 
SELECT P."ID", P."Nombres" AS nombre_paciente, P."Apellidos" AS apellido_paciente,
A."fecha", A."Diagnostico", medico."Nombres" AS medico_nombre, medico."Apellidos" AS medico_apellido, 
ARA."Especialidad" AS medico_especialidad
FROM atencion AS A LEFT JOIN ppersona AS P ON A."IDPaciente" = P."ID"
LEFT JOIN (
	SELECT persona."ID", persona."Nombres", persona."Apellidos", profesion."profesion"
	FROM persona LEFT JOIN profesion ON profesion."ID" = persona."ID"
    WHERE profesion."profesion" ILIKE '%médic%' OR profesion."profesion" ILIKE '%medic%' 
) AS medico ON medico."ID" = A."IDMedico" LEFT JOIN orden ON orden."IDAtencion" = A."ID"
LEFT JOIN (
    SELECT arancel"ID" as "ID", arancel"ConsAtMedica" as "ConsAtMedica",
		       REPLACE(SUBSTRING(arancel."ConsAtMedica", 35, 1000000000), 'd en ', '') as "Especialidad"
	FROM   arancel"Arancel" 
	WHERE  arancel"Arancel"."ConsAtMedica" ILIKE '%consulta médica de especialidad en%' OR
		       arancel."ConsAtMedica" ILIKE '%consulta medica de especialidad en%'
    ) as ARA ON ARA."ID" = orden."IDArancel" 
WHERE A."Efectuada" = True
ORDER BY P."ID" ASC, A."fecha" DESC
;

--1.e. Validación de Ingreso de datos

