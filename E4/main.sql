--Creación del indice sobre RUN
CREATE INDEX indice_run ON public."Persona"("RUN");

--Creación del Índice en Agenda
CREATE INDEX indice_agenda ON public."Agenda"("ID", "Fecha", "Hora");