SELECT p."ID", p."Nombres", p."Apellidos", m."Medicamento", COUNT(m."Medicamento") AS cuenta
FROM persona AS p INNER JOIN atencion AS a ON a."IDPaciente" = p."ID" INNER JOIN medicamentos AS m ON m."IDAtencion" = a."ID"
WHERE a."Efectuada" = True 
GROUP BY p."ID", p."Nombres", p."Apellidos", m."Medicamento"
HAVING COUNT(m."Medicamento") IN (
	SELECT DISTINCT COUNT(medicamentos."Medicamento") AS top5
	FROM medicamentos INNER JOIN atencion on atencion."ID" = medicamentos."IDAtencion" INNER JOIN persona ON persona."ID" = atencion."IDPaciente"
	WHERE atencion."Efectuada" = True
	GROUP BY persona."ID", persona."Nombres", persona."Apellidos", medicamentos."Medicamento"
	ORDER BY top5 DESC
	LIMIT 5
)
ORDER BY COUNT(m."Medicamento") DESC