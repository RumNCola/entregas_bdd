-- NOTA IMPORTANTE: PARA ESTE CÓDIGO; USE MUCHO DEL DUMP ENTREGAO EN LA ENTREGA PASADA,
-- ES DECIR; USE ESE MISMO CÓDIGO, BORRE LO QUE NO SERVÍA Y PROGRAMÉ SOBRE EL.


-- Creación de las tablas
CREATE TABLE public."Arancel" (
    "codF" integer NOT NULL,
    "codA" integer,
    "atencion" varchar(100) NOT NULL,
    "valorFonasa" integer NOT NULL,
    "ValorColita" integer,
    "grupo" varchar(30),
    "tipo" varchar(30),
    PRIMARY KEY("codF")
);

CREATE TABLE public."Atencion" (
    "ID" integer NOT NULL UNIQUE,
    "runpaciente" char(10) NOT NULL,
    "runmedico" char(10) NOT NULL,
    "diagnostico" varchar(100), --puede ser null según el enunciado
    "efectuada" boolean DEFAULT false NOT NULL, --tiene más sentido que no sea null
    PRIMARY KEY("ID", "runpaciente", "runmedico")
);

CREATE TABLE public."Farmacia" (
    cod integer NOT NULL PRIMARY KEY,
    "nombre" varchar(100) NOT NULL,
    descripcion varchar(256) NOT NULL,
    tipo text NOT NULL,
    "CodONU" integer,
    "clasificacion" varchar(50) NOT NULL,
    "estado" text NOT NULL,
    "esencial" integer CHECK("esencial" = 0 OR "esencial" = 1),
    "precio" integer
);

CREATE TABLE public."FarmaciaONU"(
    "CodONU" integer PRIMARY KEY,
    "ClasONU" text,
    FOREIGN KEY ("CodONU") REFERENCES public."Farmacia"("CodONU")
);

CREATE TABLE public."InstituciondeSalud" (
    "codigo" integer NOT NULL UNIQUE,
    "nombre" varchar(30) NOT NULL,
    "tipo" char(7) NOT NULL,
    "RUT" text NOT NULL UNIQUE,
    "Codigo" integer NOT NULL,
    "enlace" text,
    PRIMARY KEY ("codigo")
);

CREATE TABLE public."Orden" (
    "IDAtencion" integer NOT NULL,
    "IDArancel" integer NOT NULL,
    "consulta" varchar(100),
    PRIMARY KEY ("IDArancel", "IDAtencion")
);

CREATE TABLE public."Persona" (
    "ID" integer NOT NULL,
    "RUN" varchar(10) NOT NULL UNIQUE,
    "nombre" varchar(30) NOT NULL,
    "apellido" varchar(30) NOT NULL,
    "dirección" varchar(100),
    "correo" text,
    "telefono" integer CHECK("telefono" >= 100000000), -- aca reviso que cumpla el formato 
    "InsSalPrev" varchar(30),
    "medico" boolean,
    PRIMARY KEY ("ID")
);

CREATE TABLE public."Planes" (
    "ID" integer NOT NULL PRIMARY KEY,
    "Grupo" varchar(100),
    "Bonificacion" integer CHECK("Bonificacion" >= 0 AND "Bonificacion" <= 100)
);

CREATE TABLE public."Rol" (
    "IDPersona" integer NOT NULL,
    "rol" text
);

CREATE TABLE public.beneficiario (
    "IDPersona" integer NOT NULL,
    "tipo" text DEFAULT 'titular',
    "titular" char(10),
    PRIMARY KEY ("IDPersona")
);

CREATE TABLE public.medicamentos (
    "IDAtencion" integer NOT NULL,
    "nombre" varchar(100) NOT NULL,
    "Posologia" varchar(100),
    "Psicotropico" boolean,
    PRIMARY KEY ("IDAtencion", "nombre")
);

CREATE TABLE public.profesion (
    "ID" integer NOT NULL,
    "firma" varchar(30),
    "profesion" text,
    PRIMARY KEY ("ID")
);
-- Acceso a las tablas
ALTER TABLE public."Arancel" OWNER TO current_user;
ALTER TABLE public."Atencion" OWNER TO current_user;
ALTER TABLE public."Farmacia" OWNER TO current_user;
ALTER TABLE public."InstituciondeSalud" OWNER TO current_user;
ALTER TABLE public."Persona" OWNER TO current_user;
ALTER TABLE public."Planes" OWNER TO current_user;
ALTER TABLE public."Rol" OWNER TO current_user;
ALTER TABLE public.beneficiario OWNER TO current_user;
ALTER TABLE public.medicamentos OWNER TO current_user;
ALTER TABLE public.profesion OWNER TO current_user;
ALTER TABLE public."Orden" OWNER TO current_user;

-- LLaves foraneas
ALTER TABLE ONLY public."Orden"
    ADD CONSTRAINT arancel FOREIGN KEY ("IDArancel") REFERENCES public."Arancel"("codF") NOT VALID;

ALTER TABLE ONLY public."Orden"
    ADD CONSTRAINT atencion FOREIGN KEY ("IDAtencion") REFERENCES public."Atencion"("ID") NOT VALID;

ALTER TABLE ONLY public.medicamentos
    ADD CONSTRAINT atencion FOREIGN KEY ("IDAtencion") REFERENCES public."Atencion"("ID") NOT VALID;

ALTER TABLE ONLY public."Planes"
    ADD CONSTRAINT "isapre-planes" FOREIGN KEY ("ID") REFERENCES public."InstituciondeSalud"("codigo") NOT VALID;

ALTER TABLE ONLY public.profesion
    ADD CONSTRAINT "medic-firma" FOREIGN KEY ("ID") REFERENCES public."Persona"("ID") NOT VALID;

ALTER TABLE ONLY public."Atencion"
    ADD CONSTRAINT "medico-atencion" FOREIGN KEY ("runmedico") REFERENCES public."Persona"("RUN") NOT VALID;

ALTER TABLE ONLY public."Atencion"
    ADD CONSTRAINT "paciente-atencion" FOREIGN KEY ("runpaciente") REFERENCES public."Persona"("RUN") NOT VALID;

ALTER TABLE ONLY public.beneficiario
    ADD CONSTRAINT persona FOREIGN KEY ("IDPersona") REFERENCES public."Persona"("ID") NOT VALID;

ALTER TABLE ONLY public."Persona"
    ADD CONSTRAINT "persona-isapre" FOREIGN KEY ("InsSalPrev") REFERENCES public."InstituciondeSalud"("nombre") NOT VALID;

ALTER TABLE ONLY public."Rol"
    ADD CONSTRAINT "persona-rol" FOREIGN KEY ("IDPersona") REFERENCES public."Persona"("ID") NOT VALID;

ALTER TABLE ONLY public.medicamentos
    ADD CONSTRAINT remedio FOREIGN KEY ("nombre") REFERENCES public."Farmacia"("nombre") NOT VALID;

ALTER TABLE ONLY public.beneficiario
    ADD CONSTRAINT titular FOREIGN KEY ("titular") REFERENCES public."Persona"("RUN") NOT VALID;

-- carga de los archivos
--me quedué sin tiempo aca asi que quedo incompleto hasta aqui :c
COPY public."Arancel" ("codF", "codA", "atencion", "ValorFonasa", "ValorColita", "grupo", "tipo") FROM 'csv_limpios/Arancel fonasaOK.csv' DELIMITER ';' CSV HEADER;
COPY public."Atencion" ("ID", "runpaciente", "runmedico", "diagnostico", "efectuada") FROM 'csv_limpios/AtencionOK.csv' DELIMITER ';' CSV HEADER;
COPY public."Farmacia" ("cod", "nombre", "descripcion", "tipo", "CodONU", "clasificacion", "estado", "esencial", "precio") FROM 'csv_limpios/FarmaciaOK.csv' DELIMITER ';' CSV HEADER;
COPY public."FarmaciaONU" ("CodONU", "ClasONU") FROM 'csv_limpios/FarmaciaOK.csv' DELIMITER ';' CSV HEADER;
COPY public."InstituciondeSalud" ("codigo", "nombre", "tipo", "RUT", "enlace") FROM 'csv_limpios/Instituciones previsionales de saludOK.csv' DELIMITER ';' CSV HEADER;
COPY public."Orden" ("IDAtencion", "IDArancel", "consulta") FROM 'csv_limpios/OrdenOK.csv' DELIMITER ';' CSV HEADER;
COPY public."Persona" ("ID", "RUN", "nombre", "apellido", "direccion", "correo", "telefono", "InsSalPrev", "medico") FROM 'csv_limpios/PersonaOK.csv' DELIMITER ';' CSV HEADER;
COPY public."Rol" ("IDPersona", "rol") FROM 'csv_limpios/PersonaOK.csv' DELIMITER ';' CSV HEADER;
COPY public.medicamentos ("IDAtencion", "nombre", "Posologia", "Psicotropico") FROM 'csv_limpios/MedicamentoOK.csv' DELIMITER ';' CSV HEADER;
COPY public.profesion ("ID", "firma", "profesion") FROM 'csv_limpios/PersonaOK.csv' DELIMITER ';' CSV HEADER;




