SELECT p."ID", p."Nombres", p."Apellidos", COUNT(DISTINCT(a."Diagnostico")) AS dx
FROM persona AS p INNER JOIN atencion AS a ON a."IDPaciente" = p."ID"
WHERE a."Efectuada" = TRUE  AND a."Diagnostico" IS NOT NULL 
GROUP BY p."ID", p."Nombres", p."Apellidos"

HAVING COUNT(DISTINCT(a."Diagnostico")) IN (
SELECT DISTINCT COUNT(DISTINCT(atencion."Diagnostico")) as top5
FROM persona INNER JOIN atencion ON atencion."IDPaciente" = persona."ID"
WHERE atencion."Efectuada" = TRUE AND atencion."Diagnostico" IS NOT NULL
GROUP BY persona."ID", persona."Nombres", persona."Apellidos"
ORDER BY top5 DESC
LIMIT 5
)
ORDER BY dx DESC