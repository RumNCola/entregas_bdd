-- recetas no psico
SELECT 'Paciente: ' || p."Nombres" ||' ' || p."Apellidos" || ' RUN: ' || p."RUN" || '                   ' ||
'Diagnóstico: ' || a."Diagnostico" || '                   ' || string_agg(m."Medicamento" || ' ' || m."Posologia" , '                   ' ORDER BY m."Medicamento") || '                   '
|| 'Fecha: ' || a."fecha" AS Receta
FROM persona AS p INNER JOIN atencion AS a ON p."ID" = a."IDPaciente"
FULL OUTER JOIN medicamentos AS m ON m."IDAtencion" = a."ID" FULL OUTER JOIN farmacia AS f ON f."Nombre" = m."Medicamento" 
WHERE a."ID" = 182 AND m."Psicotropico" = False
GROUP BY p."Nombres", p."Apellidos", p."RUN", a."Diagnostico", a."fecha";

-- recetas psico
SELECT 'Paciente: ' || p."Nombres" ||' ' || p."Apellidos" || ' RUN: ' || p."RUN" || '                   ' ||
'Diagnóstico: ' || a."Diagnostico" || '                   ' || string_agg(m."Medicamento" || ' ' || m."Posologia" , '                   ' ORDER BY m."Medicamento") || '                   '
|| 'Código de Autorización: ' || 'NO HAY CODIGOS DE AUTORIZACIÖN EN EL ESQUEMA'
'Fecha: ' || a."fecha" AS Receta
FROM persona AS p INNER JOIN atencion AS a ON p."ID" = a."IDPaciente"
FULL OUTER JOIN medicamentos AS m ON m."IDAtencion" = a."ID" FULL OUTER JOIN farmacia AS f ON f."Nombre" = m."Medicamento" 
WHERE a."ID" = 27 AND m."Psicotropico" = True
GROUP BY p."Nombres", p."Apellidos", p."RUN", a."Diagnostico", a."fecha";