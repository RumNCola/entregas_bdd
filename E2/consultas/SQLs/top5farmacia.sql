SELECT m."Medicamento", COUNT(m."Medicamento") AS cuenta
FROM medicamentos AS m
GROUP BY m."Medicamento"
HAVING COUNT(m."Medicamento") IN (
	SELECT DISTINCT COUNT(medicamentos."Medicamento") AS top5
	FROM medicamentos
	GROUP BY medicamentos."Medicamento"
	ORDER BY top5 DESC
	LIMIT 5
)
ORDER BY cuenta DESC