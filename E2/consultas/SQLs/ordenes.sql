-- ordenes
SELECT 'Paciente: ' || p."Nombres" ||' ' || p."Apellidos" || ' RUN: ' || p."RUN" || '                   ' ||
'Diagnóstico: ' || a."Diagnostico" || '                   ' || string_agg(ara."ConsAtMedica", ' ' ORDER BY ara."ID") || '                   '
|| 'Fecha: ' || a."fecha" AS Ordenes
FROM persona AS p INNER JOIN atencion AS a ON p."ID" = a."IDPaciente"
INNER JOIN orden ON orden."IDAtencion" = a."ID" INNER JOIN arancel as ara
ON ara."ID" = orden."IDArancel"
WHERE a."ID" = 3
GROUP BY p."Nombres", p."Apellidos", p."RUN", a."Diagnostico", a."fecha";