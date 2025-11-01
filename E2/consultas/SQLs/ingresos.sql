SELECT DATE_PART('YEAR', a."fecha") AS ano, DATE_PART('MONTH', a.fecha) AS mes, 
p."InstSalud", i."Nombre", COUNT(a."ID") as cuenta, 
SUM(COALESCE(ar_sum."valor_ordenes", 0) + COALESCE(m_sum."valor_medicamentos", 0)) AS Ingreso_Mensual
FROM atencion AS a LEFT JOIN persona AS p ON p."ID" = a."IDPaciente" LEFT JOIN instituciondesalud AS i ON i."ID" = p."InstSalud"
LEFT JOIN (
	SELECT ord."IDAtencion" AS "IDAtencion", SUM(ar."ValorColita") AS valor_ordenes
	FROM orden AS ord INNER JOIN arancel AS ar ON ar."ID" = ord."IDArancel"
	GROUP BY ord."IDAtencion"
) AS ar_sum ON ar_sum."IDAtencion" = a."ID" LEFT JOIN (
	SELECT m."IDAtencion" AS "IDAtencion", sum(f."Precio") AS valor_medicamentos
	FROM medicamentos as m LEFT JOIN farmacia as f ON m."Medicamento" = f."Nombre"
	GROUP BY m."IDAtencion"
) AS m_sum ON m_sum."IDAtencion" = a."ID"
WHERE a."Efectuada" = True
GROUP BY ano, mes, p."InstSalud", i."Nombre"
ORDER BY ano ASC, mes ASC