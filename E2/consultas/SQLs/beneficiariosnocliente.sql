SELECT p."ID", p."Nombres", p."Apellidos"
FROM persona AS p INNER JOIN rol AS r ON p."ID" = r."IDPersona" INNER JOIN beneficiario AS b ON b."IDpersona" = p."ID"
WHERE b."Beneficiario" = FALSE AND r."Rol" LIKE '%paciente%' AND p."ID" IN (
	SELECT DISTINCT beneficiario."IDtitular" as "ID"
	FROM beneficiario INNER JOIN persona ON persona."ID" = beneficiario."IDpersona" INNER JOIN rol ON rol."IDPersona" = persona."ID"
	WHERE beneficiario."Beneficiario" = True AND (rol."Rol" NOT LIKE '%paciente%' OR rol."Rol" IS NULL) 
)
ORDER BY p."ID" ASC;
