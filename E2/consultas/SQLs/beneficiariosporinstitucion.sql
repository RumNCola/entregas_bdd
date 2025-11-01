SELECT i."ID", i."Nombre", b."Beneficiario", COUNT(p."InstSalud") as cuenta
FROM instituciondesalud AS i INNER JOIN persona AS p ON p."InstSalud" = i."ID" INNER JOIN beneficiario AS b ON b."IDpersona" = p."ID"
INNER JOIN rol as r ON r."IDPersona" = p."ID"
WHERE r."Rol" like '%paciente%'
GROUP BY i."ID", i."Nombre", b."Beneficiario"
ORDER BY i."ID" ASC, b."Beneficiario" DESC