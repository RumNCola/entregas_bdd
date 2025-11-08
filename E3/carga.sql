SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 218 (class 1259 OID 25302)
-- Name: Arancel; Type: TABLE; Schema: public; Owner: current_user
--

CREATE TABLE public."Arancel" (
    "codF" integer NOT NULL UNIQUE PRIMARY KEY,
    "codA" integer,
    "atencion" varchar(100) NOT NULL,
    "valorFonasa" integer NOT NULL,
    "ValorColita" integer,
    "grupo" varchar(30),
    "tipo" varchar(30)
);


ALTER TABLE public."Arancel" OWNER TO current_user;

--
-- TOC entry 219 (class 1259 OID 25309)
-- Name: Atencion; Type: TABLE; Schema: public; Owner: current_user
--

CREATE TABLE public."Atencion" (
    "ID" integer NOT NULL UNIQUE PRIMARY KEY,
    "runpaciente" char(10) NOT NULL,
    "runmedico" char(10) NOT NULL,
    "diagnostico" varchar(100), --puede ser null según el enunciado
    "efectuada" boolean DEFAULT false NOT NULL --tiene más sentido que no sea null
);


ALTER TABLE public."Atencion" OWNER TO current_user;

--
-- TOC entry 220 (class 1259 OID 25319)
-- Name: Farmacia; Type: TABLE; Schema: public; Owner: current_user
--

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
    "CodONU" integer,
    "ClasONU" text
    FOREIGN KEY ("CodONU") REFERENCES public."Farmacia"("CodONU")
);


ALTER TABLE public."Farmacia" OWNER TO current_user;


--
-- TOC entry 222 (class 1259 OID 25335)
-- Name: InstituciondeSalud; Type: TABLE; Schema: public; Owner: current_user
--

CREATE TABLE public."InstituciondeSalud" (
    "codigo" integer NOT NULL UNIQUE,
    "RUT" text NOT NULL UNIQUE,
    "Codigo" integer NOT NULL,
    "nombre" varchar(30) NOT NULL,
    "tipo" char(7) NOT NULL,
    "enlace" text
);


ALTER TABLE public."InstituciondeSalud" OWNER TO current_user;

--
-- TOC entry 223 (class 1259 OID 25342)
-- Name: Orden; Type: TABLE; Schema: public; Owner: current_user
--

CREATE TABLE public."Orden" (
    "IDAtencion" integer NOT NULL,
    "IDArancel" integer NOT NULL,
    "consulta" varchar(100)
);


ALTER TABLE public."Orden" OWNER TO current_user;

--
-- TOC entry 224 (class 1259 OID 25347)
-- Name: Persona; Type: TABLE; Schema: public; Owner: current_user
--

CREATE TABLE public."Persona" (
    "ID" integer NOT NULL,
    "RUN" varchar(10) NOT NULL UNIQUE,
    "nombre" varchar(30) NOT NULL,
    "apellido" varchar(30) NOT NULL,
    "dirección" varchar(100),
    "correo" text,
    "telefono" integer CHECH("telefono" >= 100000000), -- aca reviso que cumpla el formato 
    "InsSalPrev" varchar(30),
    medico boolean
);


ALTER TABLE public."Persona" OWNER TO current_user;

--
-- TOC entry 225 (class 1259 OID 25354)
-- Name: Planes; Type: TABLE; Schema: public; Owner: current_user
--

CREATE TABLE public."Planes" (
    "ID" integer NOT NULL PRIMARY KEY,
    "Grupo" varchar(100),
    "Bonificacion" integer CHECK("Bonificacion" >= 0 AND "Bonificacion" <= 100)
);


ALTER TABLE public."Planes" OWNER TO current_user;

--
-- TOC entry 226 (class 1259 OID 25357)
-- Name: Rol; Type: TABLE; Schema: public; Owner: current_user
--

CREATE TABLE public."Rol" (
    "IDPersona" integer NOT NULL,
    "rol" text
);


ALTER TABLE public."Rol" OWNER TO current_user;

--
-- TOC entry 228 (class 1259 OID 25566)
-- Name: beneficiario; Type: TABLE; Schema: public; Owner: current_user
--

CREATE TABLE public.beneficiario (
    "IDpersona" integer NOT NULL,
    "tipo" text DEFAULT 'titular',
    "titular" char(10)
);


ALTER TABLE public.beneficiario OWNER TO current_user;

--
-- TOC entry 227 (class 1259 OID 25362)
-- Name: medicamentos; Type: TABLE; Schema: public; Owner: current_user
--

CREATE TABLE public.medicamentos (
    "IDAtencion" integer NOT NULL UNIQUE,
    "nombre" varchar(100) NOT NULL,
    "Posologia" varchar(100),
    "Psicotropico" boolean
);


ALTER TABLE public.medicamentos OWNER TO current_user;


CREATE TABLE public.profesion (
    "ID" integer NOT NULL,
    firma varchar(30),
    "profesion" text
);


ALTER TABLE public.profesion OWNER TO current_user;

--
-- TOC entry 3853 (class 0 OID 25302)
-- Dependencies: 218
-- Data for Name: Arancel; Type: TABLE DATA; Schema: public; Owner: current_user
--

COPY public."Arancel" ("ID", "Codigo", "Codigo_a", "ConsAtMedica", "ValorFonasa", "ValorColita", "Grupo", "Tipo") FROM stdin;

--
-- TOC entry 3673 (class 2606 OID 25308)
-- Name: Arancel Arancel_pkey; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Arancel"
    ADD CONSTRAINT "Arancel_pkey" PRIMARY KEY ("ID");


--
-- TOC entry 3675 (class 2606 OID 25318)
-- Name: Atencion Atencion_ID_key; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Atencion"
    ADD CONSTRAINT "Atencion_ID_key" UNIQUE ("ID");


--
-- TOC entry 3677 (class 2606 OID 25316)
-- Name: Atencion Atencion_pkey; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Atencion"
    ADD CONSTRAINT "Atencion_pkey" PRIMARY KEY ("ID", "IDPaciente", "IDMedico");


--
-- TOC entry 3679 (class 2606 OID 25325)
-- Name: Farmacia Farmacia_pkey; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Farmacia"
    ADD CONSTRAINT "Farmacia_pkey" PRIMARY KEY (codigo);


--
-- TOC entry 3683 (class 2606 OID 25334)
-- Name: Grupo Grupo_pkey; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Grupo"
    ADD CONSTRAINT "Grupo_pkey" PRIMARY KEY ("ID");


--
-- TOC entry 3685 (class 2606 OID 25341)
-- Name: InstituciondeSalud InstituciondeSalud_pkey; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."InstituciondeSalud"
    ADD CONSTRAINT "InstituciondeSalud_pkey" PRIMARY KEY ("ID");


--
-- TOC entry 3687 (class 2606 OID 25346)
-- Name: Orden Orden_pkey; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Orden"
    ADD CONSTRAINT "Orden_pkey" PRIMARY KEY ("IDArancel", "IDAtencion");


--
-- TOC entry 3689 (class 2606 OID 25353)
-- Name: Persona Persona_pkey; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Persona"
    ADD CONSTRAINT "Persona_pkey" PRIMARY KEY ("ID");


--
-- TOC entry 3693 (class 2606 OID 25571)
-- Name: beneficiario beneficiario_pkey; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public.beneficiario
    ADD CONSTRAINT beneficiario_pkey PRIMARY KEY ("IDpersona");


--
-- TOC entry 3691 (class 2606 OID 25368)
-- Name: medicamentos medicamentos_pkey; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public.medicamentos
    ADD CONSTRAINT medicamentos_pkey PRIMARY KEY ("IDAtencion", "Medicamento");


--
-- TOC entry 3671 (class 2606 OID 25301)
-- Name: profesion medico-firma; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public.profesion
    ADD CONSTRAINT "medico-firma" PRIMARY KEY ("ID");


--
-- TOC entry 3681 (class 2606 OID 25327)
-- Name: Farmacia nombre; Type: CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Farmacia"
    ADD CONSTRAINT nombre UNIQUE ("Nombre");


--
-- TOC entry 3697 (class 2606 OID 25379)
-- Name: Orden arancel; Type: FK CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Orden"
    ADD CONSTRAINT arancel FOREIGN KEY ("IDArancel") REFERENCES public."Arancel"("ID") NOT VALID;


--
-- TOC entry 3698 (class 2606 OID 25384)
-- Name: Orden atencion; Type: FK CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Orden"
    ADD CONSTRAINT atencion FOREIGN KEY ("IDAtencion") REFERENCES public."Atencion"("ID") NOT VALID;


--
-- TOC entry 3703 (class 2606 OID 25414)
-- Name: medicamentos atencion; Type: FK CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public.medicamentos
    ADD CONSTRAINT atencion FOREIGN KEY ("IDAtencion") REFERENCES public."Atencion"("ID") NOT VALID;


--
-- TOC entry 3700 (class 2606 OID 25394)
-- Name: Planes grupo-planes; Type: FK CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Planes"
    ADD CONSTRAINT "grupo-planes" FOREIGN KEY ("Grupo") REFERENCES public."Grupo"("ID") NOT VALID;


ALTER TABLE ONLY public."Planes"
    ADD CONSTRAINT "isapre-planes" FOREIGN KEY ("ID") REFERENCES public."InstituciondeSalud"("ID") NOT VALID;


ALTER TABLE ONLY public.profesion
    ADD CONSTRAINT "medic-firma" FOREIGN KEY ("ID") REFERENCES public."Persona"("ID") NOT VALID;


--
-- TOC entry 3695 (class 2606 OID 25369)
-- Name: Atencion medico-atencion; Type: FK CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Atencion"
    ADD CONSTRAINT "medico-atencion" FOREIGN KEY ("runmedico") REFERENCES public."Persona"("RUN") NOT VALID;


ALTER TABLE ONLY public."Atencion"
    ADD CONSTRAINT "paciente-atencion" FOREIGN KEY ("IDPaciente") REFERENCES public."Persona"("ID") NOT VALID;


ALTER TABLE ONLY public.beneficiario
    ADD CONSTRAINT persona FOREIGN KEY ("IDpersona") REFERENCES public."Persona"("ID") NOT VALID;


--
-- TOC entry 3699 (class 2606 OID 25389)
-- Name: Persona persona-isapre; Type: FK CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Persona"
    ADD CONSTRAINT "persona-isapre" FOREIGN KEY ("InsSalPrev") REFERENCES public."InstituciondeSalud"("nombre") NOT VALID;


--
-- TOC entry 3702 (class 2606 OID 25404)
-- Name: Rol persona-rol; Type: FK CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public."Rol"
    ADD CONSTRAINT "persona-rol" FOREIGN KEY ("IDPersona") REFERENCES public."Persona"("ID") NOT VALID;


--
-- TOC entry 3704 (class 2606 OID 25409)
-- Name: medicamentos remedio; Type: FK CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public.medicamentos
    ADD CONSTRAINT remedio FOREIGN KEY ("nombre") REFERENCES public."Farmacia"("nombre") NOT VALID;


--
-- TOC entry 3706 (class 2606 OID 25577)
-- Name: beneficiario titular; Type: FK CONSTRAINT; Schema: public; Owner: current_user
--

ALTER TABLE ONLY public.beneficiario
    ADD CONSTRAINT titular FOREIGN KEY ("titular") REFERENCES public."Persona"("RUT") NOT VALID;


-- Completed on 2025-09-30 14:32:41 -03

--
-- current_userQL database dump complete
--
