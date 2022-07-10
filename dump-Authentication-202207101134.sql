--
-- PostgreSQL database dump
--

-- Dumped from database version 9.4.26
-- Dumped by pg_dump version 9.4.26
-- Started on 2022-07-10 11:34:54

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 173 (class 1259 OID 28723)
-- Name: app_user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.app_user (
    id integer NOT NULL,
    username character varying(32) NOT NULL,
    password character varying(60) NOT NULL,
    secret integer,
    status integer,
    create_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.app_user OWNER TO postgres;

--
-- TOC entry 174 (class 1259 OID 28727)
-- Name: app_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.app_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.app_user_id_seq OWNER TO postgres;

--
-- TOC entry 2113 (class 0 OID 0)
-- Dependencies: 174
-- Name: app_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.app_user_id_seq OWNED BY public.app_user.id;


--
-- TOC entry 190 (class 1259 OID 28839)
-- Name: bolivar_exchange; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.bolivar_exchange (
    id integer NOT NULL,
    dolar real,
    euro real,
    date timestamp with time zone DEFAULT now()
);


ALTER TABLE public.bolivar_exchange OWNER TO postgres;

--
-- TOC entry 189 (class 1259 OID 28837)
-- Name: bolivar_exchange_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.bolivar_exchange_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.bolivar_exchange_id_seq OWNER TO postgres;

--
-- TOC entry 2114 (class 0 OID 0)
-- Dependencies: 189
-- Name: bolivar_exchange_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.bolivar_exchange_id_seq OWNED BY public.bolivar_exchange.id;


--
-- TOC entry 175 (class 1259 OID 28729)
-- Name: permission; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.permission (
    id integer NOT NULL,
    permission character varying(16),
    description character varying(64)
);


ALTER TABLE public.permission OWNER TO postgres;

--
-- TOC entry 176 (class 1259 OID 28732)
-- Name: permission_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permission_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.permission_id_seq OWNER TO postgres;

--
-- TOC entry 2115 (class 0 OID 0)
-- Dependencies: 176
-- Name: permission_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permission_id_seq OWNED BY public.permission.id;


--
-- TOC entry 177 (class 1259 OID 28734)
-- Name: rol; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.rol (
    id integer NOT NULL,
    rol character varying(16),
    level integer,
    description character varying(64)
);


ALTER TABLE public.rol OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 28737)
-- Name: rol_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rol_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rol_id_seq OWNER TO postgres;

--
-- TOC entry 2116 (class 0 OID 0)
-- Dependencies: 178
-- Name: rol_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rol_id_seq OWNED BY public.rol.id;


--
-- TOC entry 179 (class 1259 OID 28739)
-- Name: rol_permission; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.rol_permission (
    id integer NOT NULL,
    rol integer,
    permission integer,
    allowed boolean DEFAULT false NOT NULL,
    reg_data timestamp without time zone DEFAULT now()
);


ALTER TABLE public.rol_permission OWNER TO postgres;

--
-- TOC entry 180 (class 1259 OID 28744)
-- Name: rol_permission_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rol_permission_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rol_permission_id_seq OWNER TO postgres;

--
-- TOC entry 2117 (class 0 OID 0)
-- Dependencies: 180
-- Name: rol_permission_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rol_permission_id_seq OWNED BY public.rol_permission.id;


--
-- TOC entry 181 (class 1259 OID 28746)
-- Name: secret; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.secret (
    id integer NOT NULL,
    question character varying(60),
    answer character varying(60),
    question_two character varying(60),
    answer_two character varying(60),
    question_three character varying(60),
    answer_three character varying(60),
    create_at timestamp without time zone DEFAULT now(),
    update_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.secret OWNER TO postgres;

--
-- TOC entry 182 (class 1259 OID 28751)
-- Name: secret_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.secret_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.secret_id_seq OWNER TO postgres;

--
-- TOC entry 2118 (class 0 OID 0)
-- Dependencies: 182
-- Name: secret_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.secret_id_seq OWNED BY public.secret.id;


--
-- TOC entry 183 (class 1259 OID 28753)
-- Name: session; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.session (
    id integer NOT NULL,
    token character varying(128) NOT NULL,
    "user" integer,
    expiration_date timestamp without time zone DEFAULT now()
);


ALTER TABLE public.session OWNER TO postgres;

--
-- TOC entry 184 (class 1259 OID 28757)
-- Name: session_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.session_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.session_id_seq OWNER TO postgres;

--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 184
-- Name: session_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.session_id_seq OWNED BY public.session.id;


--
-- TOC entry 185 (class 1259 OID 28759)
-- Name: user_rol; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.user_rol (
    id integer NOT NULL,
    rol integer,
    "user" integer,
    reg_data timestamp without time zone DEFAULT now()
);


ALTER TABLE public.user_rol OWNER TO postgres;

--
-- TOC entry 186 (class 1259 OID 28763)
-- Name: user_rol_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_rol_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_rol_id_seq OWNER TO postgres;

--
-- TOC entry 2120 (class 0 OID 0)
-- Dependencies: 186
-- Name: user_rol_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_rol_id_seq OWNED BY public.user_rol.id;


--
-- TOC entry 187 (class 1259 OID 28765)
-- Name: user_status; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.user_status (
    id integer NOT NULL,
    status character varying(16),
    description character varying(64)
);


ALTER TABLE public.user_status OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 28768)
-- Name: user_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_status_id_seq OWNER TO postgres;

--
-- TOC entry 2121 (class 0 OID 0)
-- Dependencies: 188
-- Name: user_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_status_id_seq OWNED BY public.user_status.id;


--
-- TOC entry 1930 (class 2604 OID 28770)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_user ALTER COLUMN id SET DEFAULT nextval('public.app_user_id_seq'::regclass);


--
-- TOC entry 1944 (class 2604 OID 28842)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bolivar_exchange ALTER COLUMN id SET DEFAULT nextval('public.bolivar_exchange_id_seq'::regclass);


--
-- TOC entry 1931 (class 2604 OID 28771)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permission ALTER COLUMN id SET DEFAULT nextval('public.permission_id_seq'::regclass);


--
-- TOC entry 1932 (class 2604 OID 28772)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol ALTER COLUMN id SET DEFAULT nextval('public.rol_id_seq'::regclass);


--
-- TOC entry 1935 (class 2604 OID 28773)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol_permission ALTER COLUMN id SET DEFAULT nextval('public.rol_permission_id_seq'::regclass);


--
-- TOC entry 1938 (class 2604 OID 28774)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.secret ALTER COLUMN id SET DEFAULT nextval('public.secret_id_seq'::regclass);


--
-- TOC entry 1940 (class 2604 OID 28775)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.session ALTER COLUMN id SET DEFAULT nextval('public.session_id_seq'::regclass);


--
-- TOC entry 1942 (class 2604 OID 28776)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_rol ALTER COLUMN id SET DEFAULT nextval('public.user_rol_id_seq'::regclass);


--
-- TOC entry 1943 (class 2604 OID 28777)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_status ALTER COLUMN id SET DEFAULT nextval('public.user_status_id_seq'::regclass);


--
-- TOC entry 2088 (class 0 OID 28723)
-- Dependencies: 173
-- Data for Name: app_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.app_user VALUES (1, 'usuariocaja', '$2y$10$7Gph39fltqM3Ee/KAdE0r.BHlFEel5wB3GeyqqzQCOZTJuDdJ4x5y', 1, 1, '2022-07-10 11:29:53.658');


--
-- TOC entry 2122 (class 0 OID 0)
-- Dependencies: 174
-- Name: app_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.app_user_id_seq', 1, true);


--
-- TOC entry 2105 (class 0 OID 28839)
-- Dependencies: 190
-- Data for Name: bolivar_exchange; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.bolivar_exchange VALUES (1, 5.5552001, 5.77963018, '2022-07-01 17:00:20.789752-04');
INSERT INTO public.bolivar_exchange VALUES (3, 5.5, 5.5999999, '2022-07-02 12:15:59-04');
INSERT INTO public.bolivar_exchange VALUES (4, 6, 5, '2022-07-07 23:13:19-04');


--
-- TOC entry 2123 (class 0 OID 0)
-- Dependencies: 189
-- Name: bolivar_exchange_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.bolivar_exchange_id_seq', 4, true);


--
-- TOC entry 2090 (class 0 OID 28729)
-- Dependencies: 175
-- Data for Name: permission; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2124 (class 0 OID 0)
-- Dependencies: 176
-- Name: permission_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permission_id_seq', 1, false);


--
-- TOC entry 2092 (class 0 OID 28734)
-- Dependencies: 177
-- Data for Name: rol; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.rol VALUES (1, 'admin', 1, 'administrador');


--
-- TOC entry 2125 (class 0 OID 0)
-- Dependencies: 178
-- Name: rol_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rol_id_seq', 1, true);


--
-- TOC entry 2094 (class 0 OID 28739)
-- Dependencies: 179
-- Data for Name: rol_permission; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2126 (class 0 OID 0)
-- Dependencies: 180
-- Name: rol_permission_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rol_permission_id_seq', 1, false);


--
-- TOC entry 2096 (class 0 OID 28746)
-- Dependencies: 181
-- Data for Name: secret; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.secret VALUES (1, 'Clave de recuperación #1', '$2y$10$eLmbgGHwnZxgWl5km2zLyeirLLYhFABRwU3V/rbPeg0.jqVA1QtFG', 'Clave de recuperación #2', '$2y$10$iSalVJBJ2bwAjOaWGBUQ3.Bk6j5LAjeM9eFjRkZBiytgOMTktG0ky', 'Clave de recuperación #3', '$2y$10$46UCHxTEd9/CxIPLjMXhl.U2kWru.MRstRW8BofvwKd3SG9bMdRua', '2022-07-10 11:29:53.658', '2022-07-10 11:29:53.658');


--
-- TOC entry 2127 (class 0 OID 0)
-- Dependencies: 182
-- Name: secret_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.secret_id_seq', 1, true);


--
-- TOC entry 2098 (class 0 OID 28753)
-- Dependencies: 183
-- Data for Name: session; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2128 (class 0 OID 0)
-- Dependencies: 184
-- Name: session_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.session_id_seq', 1, false);


--
-- TOC entry 2100 (class 0 OID 28759)
-- Dependencies: 185
-- Data for Name: user_rol; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.user_rol VALUES (1, 1, 1, '2022-07-10 11:29:53.658');


--
-- TOC entry 2129 (class 0 OID 0)
-- Dependencies: 186
-- Name: user_rol_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_rol_id_seq', 1, true);


--
-- TOC entry 2102 (class 0 OID 28765)
-- Dependencies: 187
-- Data for Name: user_status; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.user_status VALUES (0, 'INACTIVO', NULL);
INSERT INTO public.user_status VALUES (1, 'ACTIVO', NULL);


--
-- TOC entry 2130 (class 0 OID 0)
-- Dependencies: 188
-- Name: user_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_status_id_seq', 1, true);


--
-- TOC entry 1947 (class 2606 OID 28779)
-- Name: app_user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT app_user_pkey PRIMARY KEY (id);


--
-- TOC entry 1971 (class 2606 OID 28845)
-- Name: bolivar_exchange_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.bolivar_exchange
    ADD CONSTRAINT bolivar_exchange_pk PRIMARY KEY (id);


--
-- TOC entry 1949 (class 2606 OID 28781)
-- Name: permission_permission_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.permission
    ADD CONSTRAINT permission_permission_key UNIQUE (permission);


--
-- TOC entry 1951 (class 2606 OID 28783)
-- Name: permission_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.permission
    ADD CONSTRAINT permission_pkey PRIMARY KEY (id);


--
-- TOC entry 1957 (class 2606 OID 28785)
-- Name: rol_permission_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.rol_permission
    ADD CONSTRAINT rol_permission_pkey PRIMARY KEY (id);


--
-- TOC entry 1953 (class 2606 OID 28787)
-- Name: rol_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_pkey PRIMARY KEY (id);


--
-- TOC entry 1955 (class 2606 OID 28789)
-- Name: rol_rol_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_rol_key UNIQUE (rol);


--
-- TOC entry 1959 (class 2606 OID 28791)
-- Name: secret_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.secret
    ADD CONSTRAINT secret_pkey PRIMARY KEY (id);


--
-- TOC entry 1961 (class 2606 OID 28793)
-- Name: session_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.session
    ADD CONSTRAINT session_pkey PRIMARY KEY (id);


--
-- TOC entry 1963 (class 2606 OID 28795)
-- Name: session_token_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.session
    ADD CONSTRAINT session_token_key UNIQUE (token);


--
-- TOC entry 1965 (class 2606 OID 28797)
-- Name: user_rol_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.user_rol
    ADD CONSTRAINT user_rol_pkey PRIMARY KEY (id);


--
-- TOC entry 1967 (class 2606 OID 28799)
-- Name: user_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.user_status
    ADD CONSTRAINT user_status_pkey PRIMARY KEY (id);


--
-- TOC entry 1969 (class 2606 OID 28801)
-- Name: user_status_status_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.user_status
    ADD CONSTRAINT user_status_status_key UNIQUE (status);


--
-- TOC entry 1976 (class 2606 OID 28802)
-- Name: fk_session_u; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.session
    ADD CONSTRAINT fk_session_u FOREIGN KEY ("user") REFERENCES public.app_user(id) ON DELETE CASCADE;


--
-- TOC entry 1974 (class 2606 OID 28807)
-- Name: fk_user_rol_per_p; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol_permission
    ADD CONSTRAINT fk_user_rol_per_p FOREIGN KEY (permission) REFERENCES public.permission(id) ON DELETE CASCADE;


--
-- TOC entry 1975 (class 2606 OID 28812)
-- Name: fk_user_rol_per_r; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol_permission
    ADD CONSTRAINT fk_user_rol_per_r FOREIGN KEY (rol) REFERENCES public.rol(id) ON DELETE CASCADE;


--
-- TOC entry 1977 (class 2606 OID 28817)
-- Name: fk_user_rol_r; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_rol
    ADD CONSTRAINT fk_user_rol_r FOREIGN KEY (rol) REFERENCES public.rol(id) ON DELETE CASCADE;


--
-- TOC entry 1978 (class 2606 OID 28822)
-- Name: fk_user_rol_u; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_rol
    ADD CONSTRAINT fk_user_rol_u FOREIGN KEY ("user") REFERENCES public.app_user(id) ON DELETE CASCADE;


--
-- TOC entry 1972 (class 2606 OID 28827)
-- Name: fk_user_secret; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT fk_user_secret FOREIGN KEY (secret) REFERENCES public.secret(id) ON DELETE CASCADE;


--
-- TOC entry 1973 (class 2606 OID 28832)
-- Name: fk_user_status; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT fk_user_status FOREIGN KEY (status) REFERENCES public.user_status(id) ON DELETE CASCADE;


--
-- TOC entry 2112 (class 0 OID 0)
-- Dependencies: 7
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2022-07-10 11:34:56

--
-- PostgreSQL database dump complete
--

