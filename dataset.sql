USE sarc_db;

-- ++++++++++++++++++++++++++++++ INSERCIONES INICIALES DE LA BASE DE DATOS ++++++++++++++++++++++++

INSERT INTO ROLES(ROL_NOMBRE) -- los demás valores se van por defecto
VALUES ('ADMINISTRADOR'),
       ('SUPERVISOR'),
       ('AGENTE'),
       ('CLIENTE');

-- ROL: administrador
-- EMAIL: mrpineda@mail.com
-- PASSWORD: mrpineda
INSERT INTO USUARIOS(USU_ROL_ID, USU_USERNAME, USU_EMAIL, USU_CLAVE)
VALUES (1,
        'mrpineda',
        'mrpineda@mail.com',
        '$2y$06$0M2k9N5fCrwHWk16KJoWQOwTaf.ZD.wM/biMN1.Cq.4mrZ9CrKwU6');

-- ROL: administrador
-- EMAIL: admin@mail.com
-- PASSWORD: admin
INSERT INTO USUARIOS(USU_ROL_ID, USU_USERNAME, USU_EMAIL, USU_CLAVE)
VALUES (1,
        'admin',
        'admin@mail.com',
        '$2y$06$FbVbpz.LyoHMw2JB1yhUoOK6f4LYiU1o2yXJc3a6r5ADr0KShItIC');


-- SUPERVISOR
-- PASSWORD: mhernandez
INSERT INTO USUARIOS(USU_ROL_ID, USU_USERNAME, USU_EMAIL, USU_CLAVE)
VALUES
(2, 'mhernandez', 'mhernandez@mail.com', '$2y$06$ESh3xUcVnvC9bOD.u.3zQuhiXzaSroOYSxJBjRvWUpso9VAt63dP2');

-- AGENCIAS
INSERT INTO AGENCIA (AGE_NOMBRE, AGE_DIRECCION, AGE_TELEFONO) VALUES
('Agencia Central', 'Calle Principal #123, San Salvador', '2222-1111'),
('Agencia Santa Ana', 'Av. Libertad #45, Santa Ana', '2444-2222');

-- AGENTES
-- PASSWORDS: jcastillo, rlopez
INSERT INTO USUARIOS(USU_ROL_ID, USU_USERNAME, USU_EMAIL, USU_CLAVE, USU_AGENCIA_ID)
VALUES
(3, 'jcastillo', 'jcastillo@mail.com', '$2y$06$74o6/8GPXYzOZpYSdWvqWeF/JsbHNa1td7DNF/vk6.b1JaJ5qszdO', 1),
(3, 'rlopez', 'rlopez@mail.com', '$2y$06$vPcsLkOnP9LUVgbUmWaL4ukfDP3NCEKo61AKzlFcteEZnE9RPP0rm', 1);

-- CLIENTES
-- PASSWORDS: cvelasquez, asantos
INSERT INTO USUARIOS(USU_ROL_ID, USU_USERNAME, USU_EMAIL, USU_CLAVE)
VALUES
(4, 'cvelasquez', 'cvelasquez@mail.com', '$2y$06$kw.PqqXwzXFniEY0oreNE.yuGbP9.b2qRdydA4yzkK5ttI2K2Ooa2'),
(4, 'asantos', 'asantos@mail.com', '$2y$06$jHwtK3WWcKgDlTEy2FhmnuhyzK9niGXQ6Sf2Vjk6WGC1oiOtqLDMK');


-- CLIENTES (vinculados a usuarios cvelasquez y asantos)
INSERT INTO CLIENTES (CLI_USUARIO_ID, CLI_PRIMER_NOM, CLI_PRIMER_APE, CLI_DUI, CLI_TELEFONO)
VALUES
(5, 'Carlos', 'Velasquez', '01234567-8', '7777-1111'),
(6, 'Ana', 'Santos', '98765432-1', '7777-2222');

-- ESTADOS DE TICKET (con IDs fijos)
INSERT INTO ESTADO_TICKET (EST_CODIGO, EST_NOMBRE, EST_DESCRIPCION) VALUES
(1, 'RECIBIDO', 'Ticket recién creado'),
(2, 'ASIGNADO', 'Ticket asignado a un agente'),
(3, 'EN PROCESO', 'Ticket en revisión por un agente'),
(4, 'PENDIENTE', 'Ticket en cola de espera por falta de agentes'),
(5, 'ESCALADO', 'Ticket escalado a otra área, agente queda libre'),
(6, 'COMPLETADO', 'Ticket cerrado y resuelto');

-- AREAS
INSERT INTO AREA (AREA_NOMBRE, AREA_DESCRIPCION) VALUES
('Soporte Técnico', 'Atención a problemas técnicos'),
('Contabilidad', 'Casos relacionados a facturación y cobros');

-- TICKETS (cliente crea ticket en agencia central, origen LLAMADA)
INSERT INTO TICKETS (TKT_CLIENTE_ID, TKT_AGENCIA_ID, TKT_AREA_ID, TKT_ESTADO_ID, TKT_ASUNTO, TKT_DESCRIPCION, TKT_ORIGEN, TKT_PRIORIDAD)
VALUES
(1, 1, null, 1, 'Problema con factura', 'El cliente reporta un error en el monto de la factura', 'PRESENCIAL', 'ALTA'),
(2, 1, null, 1, 'Corte de servicio', 'El cliente no tiene acceso al servicio desde ayer', 'LLAMADA', 'MEDIA');

-- ASIGNACIONES (ejemplo: ticket 1 asignado a agente1)
INSERT INTO ASIGNACION_TICKET (ASIG_TKT_ID, ASIG_USUARIO_ID, ASIG_TIPO, ASIG_OBSERVACION, ASIG_FINALIZADA)
VALUES
(1, 3, 'AUTOMATICA', 'Asignado automáticamente al agente1', 'N');

-- HISTORIAL DE TICKETS
INSERT INTO HISTORIAL_TICKET (HIST_TKT_ID, HIST_USUARIO_ID, HIST_ESTADO_ANT, HIST_ESTADO_NUEVO, HIST_COMENTARIO)
VALUES
(1, 3, 1, 2, 'Ticket asignado a agente1'),
(1, 3, 2, 3, 'Agente inició revisión del ticket');

-- COMENTARIOS DE TICKETS
INSERT INTO COMENTARIO_TICKET (COM_TKT_ID, COM_USUARIO_ID, COM_MENSAJE)
VALUES
(1, 3, 'Estamos revisando el caso, se notificará al cliente en breve.'),
(2, 4, 'Cliente solicita confirmación de la visita técnica.');
