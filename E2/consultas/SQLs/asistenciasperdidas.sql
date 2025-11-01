-- Numero de atenciones perdidas por mes/año. (Lo puse asi porque hay atenciones de 2024 y 2025, asi las identificamos mensualmente por año)
SELECT DATE_PART('YEAR', a."fecha") AS año, DATE_PART('MONTH', a."fecha") AS mes, COUNT(*) AS atenciones_perdidas
FROM atencion AS a
WHERE a."Efectuada" = False
GROUP BY año, mes
ORDER BY año ASC, mes ASC;

--Medicos con mayor perdida 
SELECT p."ID", p."Nombres", p."Apellidos", COUNT(*) AS cuenta
FROM atencion AS a INNER JOIN persona AS p ON p."ID" = a."IDMedico"
WHERE a."Efectuada" = False
GROUP BY p."ID", p."Nombres", p."Apellidos"
HAVING COUNT(*) IN (
	SELECT DISTINCT COUNT(*) AS top5
	FROM atencion INNER JOIN persona ON persona."ID" = atencion."IDMedico"
	WHERE atencion."Efectuada" = False
	GROUP BY persona."ID"
	ORDER BY top5 DESC
	LIMIT 5
)
ORDER BY COUNT(*) DESC;

--Pacientes con mayor inasisencia
SELECT p."ID", p."Nombres", p."Apellidos", COUNT(*) AS cuenta
FROM atencion AS a INNER JOIN persona AS p ON p."ID" = a."IDPaciente"
WHERE a."Efectuada" = False
GROUP BY p."ID", p."Nombres", p."Apellidos"
HAVING COUNT(*) IN (
	SELECT DISTINCT COUNT(*) AS top5
	FROM atencion INNER JOIN persona ON persona."ID" = atencion."IDPaciente"
	WHERE atencion."Efectuada" = False
	GROUP BY persona."ID"
	ORDER BY top5 DESC
	LIMIT 5
)
ORDER BY COUNT(*) DESC;