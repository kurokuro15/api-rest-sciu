--
-- PostgreSQL database dump
--

-- Dumped from database version 9.4.26
-- Dumped by pg_dump version 9.4.26
-- Started on 2022-07-04 15:27:27

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;

--
-- TOC entry 2075 (class 0 OID 28921)
-- Dependencies: 179
-- Data for Name: carreras; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.carreras VALUES ('1', 'Informática', 1);
INSERT INTO public.carreras VALUES ('2', 'Contaduría', 2);
INSERT INTO public.carreras VALUES ('3', 'Educación Integral', 3);
INSERT INTO public.carreras VALUES ('7', 'Administración de empresas', 2);
INSERT INTO public.carreras VALUES ('4', 'Educación Preescolar', 9);
INSERT INTO public.carreras VALUES ('5', 'Electrotecnia', 13);
INSERT INTO public.carreras VALUES ('6', 'Electrónica', 13);
INSERT INTO public.carreras VALUES ('35', 'Comunidad', 14);


--
-- TOC entry 2076 (class 0 OID 28927)
-- Dependencies: 180
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.categorias VALUES (7, 'Chemise Educ. Preescolar', '00bbff');
INSERT INTO public.categorias VALUES (12, 'Chemise Educ. Integral', '888888');
INSERT INTO public.categorias VALUES (8, 'Taller de FOC', 'ffffee');
INSERT INTO public.categorias VALUES (21, 'Programa de Estudio (C.D)', 'aaccff');
INSERT INTO public.categorias VALUES (5, 'Constancia:Estudio', 'aa00ff');
INSERT INTO public.categorias VALUES (1, 'Constancias:Autent. Notas/Título', 'ff0000');
INSERT INTO public.categorias VALUES (16, 'Constancias:Buena Conducta', 'ffccff');
INSERT INTO public.categorias VALUES (6, 'Constancias:Certificación Calificac.', '00ffff');
INSERT INTO public.categorias VALUES (33, 'Constancias:Certificación de Programas', '00ff00');
INSERT INTO public.categorias VALUES (17, 'Constancias:Culminación de Estudio', '888800');
INSERT INTO public.categorias VALUES (32, 'Constancias:Mención Honorífica', '0000aa');
INSERT INTO public.categorias VALUES (19, 'Constancias:Mínimo Aprobatorio', 'ccffff');
INSERT INTO public.categorias VALUES (15, 'Constancias:Pensum de Estudio', '0088dd');
INSERT INTO public.categorias VALUES (29, 'Constancias:Puesto en la Promoción', '0000ff');
INSERT INTO public.categorias VALUES (20, 'Constancias:Firma y Sello de Programa Est.', 'ffaaff');
INSERT INTO public.categorias VALUES (34, 'Cinta de Porta Carnet', '008800');
INSERT INTO public.categorias VALUES (22, 'Reimpresión de Solvencia', '00aa00');
INSERT INTO public.categorias VALUES (23, 'Guías (varias)', 'ffff55');
INSERT INTO public.categorias VALUES (27, 'Constancias:Acta de Grado', '00ff00');
INSERT INTO public.categorias VALUES (4, 'Tramitación de títulos', '44ffcc');
INSERT INTO public.categorias VALUES (28, 'Taller: C.V.A', 'ffffee');
INSERT INTO public.categorias VALUES (3, 'Derecho de Inscripción', '77bbff');
INSERT INTO public.categorias VALUES (2, 'Mensualidades', 'ffcc77');
INSERT INTO public.categorias VALUES (11, 'Derecho a Grado', 'ff00ff');
INSERT INTO public.categorias VALUES (30, 'Control de Pago', '00ddbb');
INSERT INTO public.categorias VALUES (26, 'Porta Carnet con Cinta', 'ff9900');
INSERT INTO public.categorias VALUES (0, 'Control de Formación Complementaria', 'ffffff');
INSERT INTO public.categorias VALUES (36, 'Alquiler REJO', 'ff1177');
INSERT INTO public.categorias VALUES (19900, 'Mensajes de Sigea', 'ffff00');
INSERT INTO public.categorias VALUES (35, 'Transporte IUJO ', '334499');
INSERT INTO public.categorias VALUES (19901, 'Servicio de transporte Estud. y Prof. ', '000000');
INSERT INTO public.categorias VALUES (19902, 'Constancia tramitación de documentos ', '000000');
INSERT INTO public.categorias VALUES (25, 'Constancia tramitación de documentos', '884400');
INSERT INTO public.categorias VALUES (19903, 'Paquete de Grado ', 'ffaa99');
INSERT INTO public.categorias VALUES (19904, 'Propedeutico', '880000');
INSERT INTO public.categorias VALUES (19905, 'Cambio de Carrera', '00ffbb');
INSERT INTO public.categorias VALUES (19906, 'Equivalencia de Carrera ', 'ccdd00');
INSERT INTO public.categorias VALUES (19907, 'Reincorporación ', '77ff00');
INSERT INTO public.categorias VALUES (19908, 'Cursos CEP-01 Excel', '004444');
INSERT INTO public.categorias VALUES (19909, 'CEP-CISCO-01 CISCO modulo 1', '004433');
INSERT INTO public.categorias VALUES (19910, 'CEP 03 DISEÑO PARA NO DISEÑADORES', '0066aa');


--
-- TOC entry 2077 (class 0 OID 28933)
-- Dependencies: 181
-- Data for Name: coordinaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.coordinaciones VALUES (1, 'Informática');
INSERT INTO public.coordinaciones VALUES (9, 'Educación Preescolar');
INSERT INTO public.coordinaciones VALUES (3, 'Educación Integral');
INSERT INTO public.coordinaciones VALUES (2, 'Contaduría');
INSERT INTO public.coordinaciones VALUES (13, 'Electrónica / Electrotecnia');
INSERT INTO public.coordinaciones VALUES (14, 'Comunidad');


--
-- TOC entry 2078 (class 0 OID 28966)
-- Dependencies: 187
-- Data for Name: parametros; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.parametros VALUES (11, 'Instituto Universitario Jesús Obrero', 'J-30576524-3', 'Calle Real de los Flores con calle Andrés Bello', 'Catia - Caracas', '(0212)8616557/ 8627172', '1-2013', '2-2013');
INSERT INTO public.parametros VALUES (11, 'Instituto Universitario Jesús Obrero', 'J-30576524-3', 'Calle Real de los Flores con calle Andrés Bello', 'Catia - Caracas', '(0212)8616557/ 8627172', '1-2013', '2-2013');


--
-- TOC entry 2079 (class 0 OID 28972)
-- Dependencies: 188
-- Data for Name: productos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.productos VALUES (33, 'Taller: Expresión Corporal: "Venciendo a la Tímidez"', 0, false, 3.00, 8, 0);
INSERT INTO public.productos VALUES (86, 'Hojas de Examen', 0, false, 35.00, 23, 0);
INSERT INTO public.productos VALUES (60, 'Taller: Mercado Personal "Cuando el Producto Soy Yo"', 0, false, 15.00, 8, 0);
INSERT INTO public.productos VALUES (47, 'Taller: Mercado personal "Cuando el Producto Soy Yo"', 0, false, 15.00, 8, 0);
INSERT INTO public.productos VALUES (10, 'Chemise de Educ. Preescolar (talla:SS)', 5, false, 555.00, 7, 0);
INSERT INTO public.productos VALUES (45, 'Chemise de Educ. Preescolar (talla:M)', 2, false, 555.00, 7, 0);
INSERT INTO public.productos VALUES (70, 'Chemise Prof. Pract. Profesional', 14, true, 130.00, 21, 0);
INSERT INTO public.productos VALUES (72, 'Guía: Estadisticas', 0, false, 10.00, 23, 0);
INSERT INTO public.productos VALUES (41, 'Chemise de Educ. Integral (talla:S)', 3, false, 555.00, 12, 0);
INSERT INTO public.productos VALUES (11, 'Chemise de Educ. Preescolar (talla:L)', 1, false, 555.00, 7, 0);
INSERT INTO public.productos VALUES (68, 'Pago tardío (Sanción) FOC ', 2, false, 5000.00, 8, 0);
INSERT INTO public.productos VALUES (64, 'Taller: Teatro ', 1, false, 100.00, 8, 0);
INSERT INTO public.productos VALUES (40, 'Guía: Programas (E)', 24, false, 900.00, 23, 0);
INSERT INTO public.productos VALUES (42, 'Chemise de Educ. Integral (talla:M)', 1, false, 555.00, 12, 0);
INSERT INTO public.productos VALUES (81, 'Sudaderas (Talla XL)', 0, false, 4500.00, 12, 0);
INSERT INTO public.productos VALUES (12, 'Chemise de Educ. Integral (talla:SS)', 2, false, 555.00, 12, 0);
INSERT INTO public.productos VALUES (80, 'Sudaderas (Talla L)', 0, false, 4500.00, 12, 0);
INSERT INTO public.productos VALUES (43, 'Chemise de Educ. Integral (talla:L)', 5, false, 555.00, 12, 0);
INSERT INTO public.productos VALUES (77, 'Sudaderas (Talla SS)', 1, false, 4500.00, 12, 0);
INSERT INTO public.productos VALUES (85, 'Taller: PRESENTACIONES ELECTRÓNICAS EFECTIVAS', 1, false, 50.00, 8, 0);
INSERT INTO public.productos VALUES (59, 'Taller: Paquete FOC 6º Semestre', 15, false, 600.00, 8, 0);
INSERT INTO public.productos VALUES (89, 'Taller: Paquete FOC 1º Semestre', 21, false, 600.00, 8, 0);
INSERT INTO public.productos VALUES (13, 'Chemise de Educ. Pract. Prof.  (talla:S)', 2, false, 555.00, 12, 0);
INSERT INTO public.productos VALUES (62, 'Dif.Paquete de Grado', 9, false, 200000.00, 4, 0);
INSERT INTO public.productos VALUES (67, 'Taller: Lenguaje de Señas', 5, false, 100.00, 8, 0);
INSERT INTO public.productos VALUES (57, 'Taller: Paquete FOC 2º Semestre ', 55, false, 600.00, 8, 0);
INSERT INTO public.productos VALUES (17, 'Taller: "Sigueme y te Sigo"', 2, false, 50.00, 8, 0);
INSERT INTO public.productos VALUES (88, 'Taller: I.SL.R.', 1, false, 100.00, 8, 0);
INSERT INTO public.productos VALUES (83, 'Taller: Paquete FOC 3º Semestre ', 16, false, 600.00, 8, 0);
INSERT INTO public.productos VALUES (29, 'Aporte Rec.Transporte IUJO', 128, false, 500.00, 35, 0);
INSERT INTO public.productos VALUES (78, 'Sudaderas (Talla S)', 0, false, 4500.00, 12, 0);
INSERT INTO public.productos VALUES (3, 'Control de Formación Complementaria', 44, false, 1000.00, 0, 0);
INSERT INTO public.productos VALUES (99, 'Pago Serv. Tintorería', 0, false, 50000.00, 28, 0);
INSERT INTO public.productos VALUES (79, 'Sudaderas (Talla M)', 2, false, 4500.00, 12, 0);
INSERT INTO public.productos VALUES (49, 'Taller: Versos Prosas y Poesías', 0, false, 100.00, 8, 0);
INSERT INTO public.productos VALUES (61, 'Taller: Paquete FOC 6º Semestre (Sanción)', 1, false, 0.00, 8, 0);
INSERT INTO public.productos VALUES (73, 'Taller: Paquete FOC 5º Semestre (Sanción)', 0, false, 0.00, 8, 0);
INSERT INTO public.productos VALUES (66, 'Taller: Paquete FOC 3º Semestre (Sanción)', 1, false, 0.00, 8, 0);
INSERT INTO public.productos VALUES (97, 'Permiso Acto de Grado', 3, false, 30.00, 11, 0);
INSERT INTO public.productos VALUES (102, 'Porta título (Paquete de Grado)', 10, false, 700000.00, 4, 0);
INSERT INTO public.productos VALUES (84, 'Solicitud de Acto de Grado por Secretaria', 6, false, 20000.00, 11, 0);
INSERT INTO public.productos VALUES (106, 'Asistencia a Socialización TEG', 28, false, 10000.00, 25, 0);
INSERT INTO public.productos VALUES (87, 'Asistencia a Talleres ', 12, false, 10000.00, 25, 0);
INSERT INTO public.productos VALUES (74, 'Asistencia a Asesorías de TEG', 1, false, 10000.00, 25, 0);
INSERT INTO public.productos VALUES (28, 'Carnet Estudiantil', 155, false, 126000.00, 34, 0);
INSERT INTO public.productos VALUES (26, 'Reimpresión de Solvencia', 46, false, 40000.00, 22, 0);
INSERT INTO public.productos VALUES (39, 'Asistencia Trámite Institucional', 2, false, 10000.00, 27, 0);
INSERT INTO public.productos VALUES (32, 'Culminación de Estudios (Act)', 173, false, 20000.00, 5, 0);
INSERT INTO public.productos VALUES (69, 'Hoja Graduandos', 95, false, 20000.00, 23, 0);
INSERT INTO public.productos VALUES (50, 'Taller: Álgebra', 33, false, 8000.00, 8, 0);
INSERT INTO public.productos VALUES (75, 'Asistencia a Reuniones', 4, false, 10000.00, 25, 0);
INSERT INTO public.productos VALUES (55, 'Constancia Horas Académicas', 55, false, 40000.00, 27, 0);
INSERT INTO public.productos VALUES (98, 'Hoja de Graduandos (SACE)', 0, false, 4500.00, 11, 0);
INSERT INTO public.productos VALUES (36, 'Extravío recibo de pago Carnet', 8, false, 26000.00, 22, 0);
INSERT INTO public.productos VALUES (14, 'Taller: Excel Avanzado', 1, false, 8000.00, 8, 0);
INSERT INTO public.productos VALUES (94, 'Porta Carnet', 9, false, 15000.00, 26, 0);
INSERT INTO public.productos VALUES (34, 'Participación/Apoyo LogÍstica TEG', 38, false, 4500.00, 25, 0);
INSERT INTO public.productos VALUES (90, 'Taller: AutoCAD', 4, false, 8000.00, 8, 0);
INSERT INTO public.productos VALUES (48, 'Taller: Contabilidad', 6, false, 8000.00, 8, 0);
INSERT INTO public.productos VALUES (58, 'Taller: Conociendo el niño(a) en su Desarrollo Evolutivo', 10, false, 13000.00, 8, 0);
INSERT INTO public.productos VALUES (76, 'Resuelto ministerial (Extravío Título sin Registrar)', 177, false, 40000.00, 27, 0);
INSERT INTO public.productos VALUES (27, 'Culminación de estudios (Egr)', 31, false, 40000.00, 5, 0);
INSERT INTO public.productos VALUES (95, 'Fondo Negro Título', 4, false, 13000.00, 4, 0);
INSERT INTO public.productos VALUES (93, 'Títulos DG', 38, false, 16.64, 11, 0);
INSERT INTO public.productos VALUES (37, 'Equivalencia Internas Entre Carrera (Activo)', 7, false, 910000.00, 3, 0);
INSERT INTO public.productos VALUES (31, 'Constancias:Puesto en la Promoción', 30, false, 1107000.00, 29, 0);
INSERT INTO public.productos VALUES (24, 'Constancias:Firma y Sello de Programas de Estudios EGR.', 25, false, 2070000.00, 27, 0);
INSERT INTO public.productos VALUES (53, 'Constancia Servicio Comunitario (Egr)', 22, false, 1107000.00, 5, 0);
INSERT INTO public.productos VALUES (51, 'Reingreso ', 5, false, 100000.00, 3, 0);
INSERT INTO public.productos VALUES (56, 'Constancia Servicio Comunitario (Act)', 0, false, 276750.00, 5, 0);
INSERT INTO public.productos VALUES (18, 'Taller FOC', 142, false, 7749000.00, 8, 0);
INSERT INTO public.productos VALUES (38, 'Constancia de Horarios Firmados y Sellados', 11, false, 553500.00, 5, 0);
INSERT INTO public.productos VALUES (44, 'Constancia de Inscripción', 2, false, 276750.00, 3, 0);
INSERT INTO public.productos VALUES (25, 'Constancia Modalidad de Estudio', 50, false, 1107000.00, 27, 0);
INSERT INTO public.productos VALUES (8, 'Constancias de Estudios', 97, false, 276750.00, 5, 0);
INSERT INTO public.productos VALUES (54, 'Constancias Mnimo Aprobatorio EGR.', 12, false, 1107000.00, 27, 0);
INSERT INTO public.productos VALUES (52, 'Constancias Pasantías EGR', 23, false, 1107000.00, 5, 0);
INSERT INTO public.productos VALUES (71, 'Constancias: Certificación de Programas EGR.', 29, false, 2214000.00, 27, 0);
INSERT INTO public.productos VALUES (20, 'Constancias:Pensum de Estudio', 110, false, 1107000.00, 15, 0);
INSERT INTO public.productos VALUES (7, 'Constancias:Tramitación de Título (DG)', 18, false, 276750.00, 11, 0);
INSERT INTO public.productos VALUES (96, 'Planilla Reincorporación / Retiro Sem', 60, false, 1600000.00, 23, 0);
INSERT INTO public.productos VALUES (30, 'Extravío de Carnet', 23, false, 3321000.00, 34, 0);
INSERT INTO public.productos VALUES (5, 'Mención Honorífica', 135, false, 1107000.00, 27, 0);
INSERT INTO public.productos VALUES (15, 'Multa biblioteca (Perdida de libro)', 8, false, 6642000.00, 11, 0);
INSERT INTO public.productos VALUES (23, 'Multa de Biblioteca (Entrega Tardia)', 11, false, 3321000.00, 11, 0);
INSERT INTO public.productos VALUES (63, 'Veredicto Aprobatorio', 24, false, 1107000.00, 5, 0);
INSERT INTO public.productos VALUES (65, 'Prueba Extraordinaria', 6, false, 276750.00, 5, 0);
INSERT INTO public.productos VALUES (21, 'Constancias:Buena Conducta EGR.', 24, false, 1107000.00, 16, 0);
INSERT INTO public.productos VALUES (22, 'Constancias:Culminación de Estudio(DG)', 31, false, 1107000.00, 27, 0);
INSERT INTO public.productos VALUES (92, 'Constancias:Notas Certificadas REG.', 29, false, 1107000.00, 6, 0);
INSERT INTO public.productos VALUES (9, 'Equivalencia Interna Entre Carrera (Egr)', 59, false, 63.50, 30, 0);
INSERT INTO public.productos VALUES (35, 'Hojas retiro e Inscripción de materia', 178, false, 930000.00, 23, 0);
INSERT INTO public.productos VALUES (4, 'Constancias:Autenticación de Notas/Título', 113, false, 3780000.00, 1, 0);
INSERT INTO public.productos VALUES (103, 'Paquete constancias graduandos', 23, false, 4.16, 11, 0);
INSERT INTO public.productos VALUES (101, 'Asistencia a Convivencias', 11, false, 10000.00, 25, 0);
INSERT INTO public.productos VALUES (19, 'Constancia Notas Certificadas (Egr)', 114, false, 1107000.00, 27, 0);
INSERT INTO public.productos VALUES (82, 'Constancia: Acta de Grado (Egr)', 18, false, 1107000.00, 27, 0);
INSERT INTO public.productos VALUES (46, 'Constancia de Pasantías ACT', 27, false, 276750.00, 5, 0);
INSERT INTO public.productos VALUES (16, 'Curso Introductorio', 25, false, 7749000.00, 5, 0);
INSERT INTO public.productos VALUES (91, 'Constancias:Buena Conducta ACT.', 9, false, 276750.00, 16, 0);
INSERT INTO public.productos VALUES (111, 'CEP-01 Excel. Basico', 1, false, 44.40, 19908, 0);
INSERT INTO public.productos VALUES (104, 'Mensualidad', 889, false, 7749000.00, 2, 0);
INSERT INTO public.productos VALUES (6, 'Derecho de Inscripción', 199, false, 7749000.00, 3, 0);
INSERT INTO public.productos VALUES (100, 'Petición de grado', 28, false, 4.16, 4, 0);
INSERT INTO public.productos VALUES (107, 'Propedeutico', 0, false, 46.07, 19904, 0);
INSERT INTO public.productos VALUES (108, 'Cambio de Carrera', 0, false, 2.30, 19905, 0);
INSERT INTO public.productos VALUES (109, 'Reincorporación', 0, false, 2.30, 19907, 0);
INSERT INTO public.productos VALUES (110, 'Equivalencia de Carrera ', 0, false, 2.30, 19906, 0);


--
-- TOC entry 2087 (class 0 OID 0)
-- Dependencies: 189
-- Name: productos_idproducto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.productos_idproducto_seq', 111, true);


--
-- TOC entry 2081 (class 0 OID 28980)
-- Dependencies: 190
-- Data for Name: retiros; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.retiros VALUES (0, 'Matriculado/a');
INSERT INTO public.retiros VALUES (1, 'Retiro temporal');
INSERT INTO public.retiros VALUES (2, 'Retiro definitvo');
INSERT INTO public.retiros VALUES (3, 'Egreso');
INSERT INTO public.retiros VALUES (4, 'Inasistente');
INSERT INTO public.retiros VALUES (5, 'Pendiente de reingreso');


-- Completed on 2022-07-04 15:27:28

--
-- PostgreSQL database dump complete
--

INSERT INTO public.alumnos
(id_cedula, id_carrera, fechainscr, apellido1, apellido2, nombre1, nombre2, semestre, retiro, fecharetiro)
VALUES(26576198, '1', '2020-02-12 00:00:00.000', 'González', 'Mejias', 'Reynaldo', 'Antonio', '5', 0, NULL);
INSERT INTO public.alumnos
(id_cedula, id_carrera, fechainscr, apellido1, apellido2, nombre1, nombre2, semestre, retiro, fecharetiro)
VALUES(29784799, '1', '2020-02-12 00:00:00.000', 'Montaño', 'Vargas', 'Yhan', 'Carlos', '5', 0, NULL);
