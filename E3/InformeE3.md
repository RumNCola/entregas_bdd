# Informe Entrega 3 - Bases de datos IIC2413

### Datos del Alumno
| **Apellidos**       | **Nombres**          | **Número de Alumno** |
|---------------------|----------------------|----------------------|
| Jara García         | Fernando Martín      | 2420286J             |

### 1. Esquema Relacional
El esquema relacional de la **ENTREGA PASDADA es:**
![Esquema relacional](esquema.png)
#### Lo que está bien:
1. Persona
2. Rol
3. Beneficiario
4. Orden (En los csv trae un atributo adicional: ConsAtMedica, lo que no representa un problema para BCNF).
5. Medicamentos (Revisé en los archivos de E2 y encontré que no existen dependencias funcionales Entre medicamento o Posología que rompan formas normales)
6. Arancel
7. InstituciondeSalud
8. Grupo
9. Planes
10. Atencion (En los archivos CSV, se puede observar que se identifica al paciente y médico a través de su RUN, cosa que difiere con lo de la entrega 2. Por esto, se debe cambir la FK IDPaciente e IDMedico por RUNPaciente y RUNMedico que hagan referencias a Persona.RUN.)
11. Persona
#### Lo que se debe arreglar:
1. Farmacia (Se rompe 3NF con CodONU y ClasONU)
Para Transformar el esquema a BCNF, debemos quitar CodOnu y ClasONU de la tabla farmacia y crear una nueva tabla que guarde este valor, de la forma:
-- FarmaciaONU(CodONU SERIAL PK, ClasONU VARCHAR NOT NULL) --
Como detalle, canasta_esencial esta en formato numérico dentro de los csv y no como boolean.

Observación: Si una columna tiene otro nombre en los csv, por ejemplo Farmacia.codigo se llama Código genérico en el csv, asumiremos que esto no representa un problema que se debe arreglar.

Por lo tanto, el esquema corregido se vería de la siguiente forma:

![Esquema relacional BCNF](esquema_nuevo.png)

### 2. Revisión PHP
#### Revisión de Persona.csv
Según el enunciado se deben tener las siguientes consideraciones (serán escritas en pseudocódigo):
1. persona."rol" puede ser null (no es paciente ni trabajador del centro), 'paciente', 'Staff médico', 'administraivo', 'Staff médico, paciente' o 'administrativo, paciente'.
2. si persona."rol" ILIKE "%Staff médico%" entonces profesion."Profesion" de dicha persona IS NOT NULL. En caso contrario, profesion."Profesion" IS NULL.
3. Si persona."rol" ILIKE "%Staff médico%" OR persona."rol" ILIKE "%administrativo%", entonces beneficiario."Beneficiario" = False AND beneficiario."IDPersona" = beneficiario."IDTitular".
4. persona."InstSalud" IS NULL o contiene un texto contenido en institucion."Nombre".



