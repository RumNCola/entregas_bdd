SELECT a."ID", a."Diagnostico", p."Nombres", p."Apellidos", COALESCE(vc."ValorColita", 50000) AS valor_consulta, COALESCE(o.valor_ordenes, 0) 
AS valor_ordenes, COALESCE(med.valor_medicamentos, 0) AS valor_medicamentos, COALESCE(vc."ValorColita", 50000) + COALESCE(o.valor_ordenes, 0)
+ COALESCE(med.valor_medicamentos, 0) AS valor_atencion
FROM atencion AS a LEFT JOIN persona AS p ON p."ID" = a."IDPaciente" LEFT JOIN (
  SELECT ord."IDAtencion" AS "IDAtencion",
         SUM(ar."ValorColita") AS valor_ordenes
  FROM orden AS ord
  JOIN arancel AS ar ON ar."ID" = ord."IDArancel"
  WHERE ar."ConsAtMedica" IS NULL OR ar."ConsAtMedica" NOT ILIKE '%consulta%'
  GROUP BY ord."IDAtencion"
) AS o ON o."IDAtencion" = a."ID" LEFT JOIN (
  SELECT m."IDAtencion" AS "IDAtencion",
         SUM(f."Precio") AS valor_medicamentos
  FROM medicamentos AS m
  LEFT JOIN farmacia AS f ON f."Nombre" = m."Medicamento"
  GROUP BY m."IDAtencion"
) AS med ON med."IDAtencion" = a."ID" LEFT JOIN (
  SELECT ord."IDAtencion" AS "IDAtencion",
         SUM(aranc."ValorColita") AS "ValorColita"
  FROM orden AS ord
  JOIN arancel AS aranc ON aranc."ID" = ord."IDArancel"
  WHERE aranc."ConsAtMedica" ILIKE '%consulta%'
  GROUP BY ord."IDAtencion"
) AS vc ON vc."IDAtencion" = a."ID"
WHERE a."Efectuada" = True
