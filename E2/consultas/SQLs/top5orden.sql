SELECT ara."ConsAtMedica", COUNT(ara."ID") as cuenta
FROM arancel AS ara INNER JOIN orden AS o ON o."IDArancel" = ara."ID" INNER JOIN atencion AS ate ON ate."ID" = o."IDAtencion"
WHERE ara."ConsAtMedica" LIKE '%examen%' OR ara."ConsAtMedica" LIKE '%exámen%'
GROUP BY ara."ID", ara."ConsAtMedica"
HAVING COUNT(ara."ID") IN (
	SELECT DISTINCT COUNT(arancel."ID")
	FROM arancel INNER JOIN orden ON orden."IDArancel" = arancel."ID" INNER JOIN atencion ON atencion."ID" = orden."IDAtencion"
	WHERE arancel."ConsAtMedica" LIKE '%examen%' OR arancel."ConsAtMedica" LIKE '%exámen%'
	GROUP BY arancel."ID", arancel."ConsAtMedica"
	ORDER BY COUNT(arancel."ID") DESC
	LIMIT 5
)
ORDER BY cuenta DESC