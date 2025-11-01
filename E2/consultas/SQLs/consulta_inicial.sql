ALTER TABLE "Arancel" RENAME TO arancel;
ALTER TABLE "Atencion" RENAME TO atencion;
ALTER TABLE "Farmacia" RENAME TO farmacia;
ALTER TABLE "Grupo" RENAME TO  grupo;
ALTER TABLE "InstituciondeSalud" RENAME TO instituciondesalud;
ALTER TABLE "Orden" RENAME TO orden;
ALTER TABLE "Persona" RENAME TO persona;
ALTER TABLE "Planes" RENAME TO planes;
ALTER TABLE "Rol" RENAME TO rol;
-- Esta consulta la usé al inicio para no tener que usar "Tabla" o public."Tabla"
-- Era confuso y dificil, sobretodo porque era caps sensitive.