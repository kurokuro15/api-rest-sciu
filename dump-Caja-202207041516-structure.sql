--
-- PostgreSQL database dump
--

-- Dumped from database version 9.4.26
-- Dumped by pg_dump version 9.4.26
-- Started on 2022-07-04 15:16:41

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;

--
-- TOC entry 548 (class 1247 OID 28900)
-- Name: breakpoint; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.breakpoint AS (
	func oid,
	linenumber integer,
	targetname text
);


ALTER TYPE public.breakpoint OWNER TO postgres;

--
-- TOC entry 551 (class 1247 OID 28903)
-- Name: frame; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.frame AS (
	level integer,
	targetname text,
	func oid,
	linenumber integer,
	args text
);


ALTER TYPE public.frame OWNER TO postgres;

--
-- TOC entry 554 (class 1247 OID 28906)
-- Name: proxyinfo; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.proxyinfo AS (
	serverversionstr text,
	serverversionnum integer,
	proxyapiver integer,
	serverprocessid integer
);


ALTER TYPE public.proxyinfo OWNER TO postgres;

--
-- TOC entry 557 (class 1247 OID 28909)
-- Name: targetinfo; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.targetinfo AS (
	target oid,
	schema oid,
	nargs integer,
	argtypes oidvector,
	targetname name,
	argmodes "char"[],
	argnames text[],
	targetlang oid,
	fqname text,
	returnsset boolean,
	returntype oid
);


ALTER TYPE public.targetinfo OWNER TO postgres;

--
-- TOC entry 560 (class 1247 OID 28912)
-- Name: var; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.var AS (
	name text,
	varclass character(1),
	linenumber integer,
	isunique boolean,
	isconst boolean,
	isnotnull boolean,
	dtype oid,
	value text
);


ALTER TYPE public.var OWNER TO postgres;

--
-- TOC entry 208 (class 1255 OID 28913)
-- Name: iif(boolean, text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.iif(boolean, text, text) RETURNS text
    LANGUAGE plpgsql
    AS $_$ 
begin 
        IF $1 THEN 
            RETURN $2; 
        ELSE 
            RETURN $3; 
        END IF; 
 end; 
$_$;


ALTER FUNCTION public.iif(boolean, text, text) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 178 (class 1259 OID 28914)
-- Name: alumnos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.alumnos (
    id_cedula integer NOT NULL,
    id_carrera text,
    fechainscr timestamp without time zone,
    apellido1 text,
    apellido2 text,
    nombre1 text,
    nombre2 text,
    semestre text,
    retiro smallint,
    fecharetiro timestamp without time zone
);


ALTER TABLE public.alumnos OWNER TO postgres;

--
-- TOC entry 179 (class 1259 OID 28921)
-- Name: carreras; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.carreras (
    id_carrer text NOT NULL,
    nombrecarrera text,
    idcoordinacio integer
);


ALTER TABLE public.carreras OWNER TO postgres;

--
-- TOC entry 180 (class 1259 OID 28927)
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.categorias (
    idcategoria smallint NOT NULL,
    nombrecategoria text,
    colorcategoria text
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- TOC entry 181 (class 1259 OID 28933)
-- Name: coordinaciones; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.coordinaciones (
    idcoordinacion integer NOT NULL,
    nombrecoordinacion text
);


ALTER TABLE public.coordinaciones OWNER TO postgres;

--
-- TOC entry 182 (class 1259 OID 28939)
-- Name: emisiones_idregistro_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.emisiones_idregistro_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.emisiones_idregistro_seq OWNER TO postgres;

--
-- TOC entry 183 (class 1259 OID 28941)
-- Name: emisiones; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.emisiones (
    idregistro integer DEFAULT nextval('public.emisiones_idregistro_seq'::regclass) NOT NULL,
    idcategori smallint,
    fechaemision timestamp without time zone,
    concepto text,
    claveconcepto text,
    monto numeric,
    id_cedul integer,
    idproduct integer DEFAULT 0,
    unidades smallint DEFAULT 0
);


ALTER TABLE public.emisiones OWNER TO postgres;

--
-- TOC entry 2136 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN emisiones.idproduct; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.emisiones.idproduct IS 'Indica si esta orden de pago tiene que ver con la venta de un producto.';


--
-- TOC entry 184 (class 1259 OID 28950)
-- Name: idpagoseq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.idpagoseq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.idpagoseq OWNER TO postgres;

--
-- TOC entry 185 (class 1259 OID 28952)
-- Name: paginas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.paginas (
    idpagina integer NOT NULL,
    titulopagina text,
    nombrepagina text
);


ALTER TABLE public.paginas OWNER TO postgres;

--
-- TOC entry 186 (class 1259 OID 28958)
-- Name: pagos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.pagos (
    idpago integer DEFAULT nextval('public.idpagoseq'::regclass) NOT NULL,
    idregistr integer,
    fechapago timestamp without time zone,
    monto numeric,
    idtipopag text,
    factura integer,
    registrador text,
    anulado boolean DEFAULT false,
    autorizacion character(12)
);


ALTER TABLE public.pagos OWNER TO postgres;

--
-- TOC entry 187 (class 1259 OID 28966)
-- Name: parametros; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.parametros (
    semilla smallint,
    nombre text,
    rif text,
    direccion text,
    poblacion text,
    telefono text,
    lapso text,
    lapsosiguiente text
);


ALTER TABLE public.parametros OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 28972)
-- Name: productos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.productos (
    idproducto integer NOT NULL,
    nombreproducto text,
    cantidad integer,
    contable boolean,
    preciounitario numeric,
    idcategor smallint,
    vecesusado integer
);


ALTER TABLE public.productos OWNER TO postgres;

--
-- TOC entry 189 (class 1259 OID 28978)
-- Name: productos_idproducto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.productos_idproducto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.productos_idproducto_seq OWNER TO postgres;

--
-- TOC entry 2137 (class 0 OID 0)
-- Dependencies: 189
-- Name: productos_idproducto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.productos_idproducto_seq OWNED BY public.productos.idproducto;


--
-- TOC entry 190 (class 1259 OID 28980)
-- Name: retiros; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.retiros (
    idretiro smallint NOT NULL,
    nombreretiro text
);


ALTER TABLE public.retiros OWNER TO postgres;

--
-- TOC entry 191 (class 1259 OID 28986)
-- Name: tipospago; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.tipospago (
    idtipopago text NOT NULL,
    fecha timestamp without time zone,
    banco text,
    concepto text,
    tipopago text
);


ALTER TABLE public.tipospago OWNER TO postgres;

--
-- TOC entry 192 (class 1259 OID 28992)
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.usuarios (
    idusuario integer NOT NULL,
    nombreusuario text
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- TOC entry 193 (class 1259 OID 28998)
-- Name: usuarioscategorias; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.usuarioscategorias (
    idusuar integer NOT NULL,
    idcategor integer NOT NULL
);


ALTER TABLE public.usuarioscategorias OWNER TO postgres;

--
-- TOC entry 194 (class 1259 OID 29001)
-- Name: usuarioscoordinaciones; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.usuarioscoordinaciones (
    idusuari integer NOT NULL,
    idcoordinaci integer NOT NULL
);


ALTER TABLE public.usuarioscoordinaciones OWNER TO postgres;

--
-- TOC entry 195 (class 1259 OID 29004)
-- Name: usuariospaginas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.usuariospaginas (
    idusua integer NOT NULL,
    idpagin integer NOT NULL
);


ALTER TABLE public.usuariospaginas OWNER TO postgres;

--
-- TOC entry 1979 (class 2604 OID 29007)
-- Name: idproducto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos ALTER COLUMN idproducto SET DEFAULT nextval('public.productos_idproducto_seq'::regclass);


--
-- TOC entry 1981 (class 2606 OID 29009)
-- Name: alumnos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.alumnos
    ADD CONSTRAINT alumnos_pkey PRIMARY KEY (id_cedula);


--
-- TOC entry 1983 (class 2606 OID 29011)
-- Name: carreras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_pkey PRIMARY KEY (id_carrer);


--
-- TOC entry 1985 (class 2606 OID 29013)
-- Name: categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (idcategoria);


--
-- TOC entry 1987 (class 2606 OID 29015)
-- Name: coordinaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.coordinaciones
    ADD CONSTRAINT coordinaciones_pkey PRIMARY KEY (idcoordinacion);


--
-- TOC entry 1989 (class 2606 OID 29017)
-- Name: emisiones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.emisiones
    ADD CONSTRAINT emisiones_pkey PRIMARY KEY (idregistro);


--
-- TOC entry 1991 (class 2606 OID 29019)
-- Name: paginas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.paginas
    ADD CONSTRAINT paginas_pkey PRIMARY KEY (idpagina);


--
-- TOC entry 1993 (class 2606 OID 29021)
-- Name: pagos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.pagos
    ADD CONSTRAINT pagos_pkey PRIMARY KEY (idpago);


--
-- TOC entry 1995 (class 2606 OID 29023)
-- Name: pk_productos; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.productos
    ADD CONSTRAINT pk_productos PRIMARY KEY (idproducto);


--
-- TOC entry 1997 (class 2606 OID 29025)
-- Name: retiros_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.retiros
    ADD CONSTRAINT retiros_pkey PRIMARY KEY (idretiro);


--
-- TOC entry 1999 (class 2606 OID 29027)
-- Name: tipospago_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.tipospago
    ADD CONSTRAINT tipospago_pkey PRIMARY KEY (idtipopago);


--
-- TOC entry 2001 (class 2606 OID 29029)
-- Name: usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (idusuario);


--
-- TOC entry 2003 (class 2606 OID 29031)
-- Name: usuarioscategorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.usuarioscategorias
    ADD CONSTRAINT usuarioscategorias_pkey PRIMARY KEY (idusuar, idcategor);


--
-- TOC entry 2005 (class 2606 OID 29033)
-- Name: usuarioscoordinaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.usuarioscoordinaciones
    ADD CONSTRAINT usuarioscoordinaciones_pkey PRIMARY KEY (idusuari, idcoordinaci);


--
-- TOC entry 2007 (class 2606 OID 29035)
-- Name: usuariospaginas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.usuariospaginas
    ADD CONSTRAINT usuariospaginas_pkey PRIMARY KEY (idusua, idpagin);


--
-- TOC entry 2008 (class 2606 OID 29036)
-- Name: alucar; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.alumnos
    ADD CONSTRAINT alucar FOREIGN KEY (id_carrera) REFERENCES public.carreras(id_carrer) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2009 (class 2606 OID 29041)
-- Name: aluret; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.alumnos
    ADD CONSTRAINT aluret FOREIGN KEY (retiro) REFERENCES public.retiros(idretiro) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2010 (class 2606 OID 29046)
-- Name: emisiones_id_cedul_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.emisiones
    ADD CONSTRAINT emisiones_id_cedul_fkey FOREIGN KEY (id_cedul) REFERENCES public.alumnos(id_cedula) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2011 (class 2606 OID 29051)
-- Name: emisiones_idcategori_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.emisiones
    ADD CONSTRAINT emisiones_idcategori_fkey FOREIGN KEY (idcategori) REFERENCES public.categorias(idcategoria) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2014 (class 2606 OID 29056)
-- Name: fk_categorias; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos
    ADD CONSTRAINT fk_categorias FOREIGN KEY (idcategor) REFERENCES public.categorias(idcategoria) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2012 (class 2606 OID 29061)
-- Name: pagemis; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pagos
    ADD CONSTRAINT pagemis FOREIGN KEY (idregistr) REFERENCES public.emisiones(idregistro) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2013 (class 2606 OID 29066)
-- Name: pagtpa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pagos
    ADD CONSTRAINT pagtpa FOREIGN KEY (idtipopag) REFERENCES public.tipospago(idtipopago) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2015 (class 2606 OID 29071)
-- Name: ucacat; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarioscategorias
    ADD CONSTRAINT ucacat FOREIGN KEY (idcategor) REFERENCES public.categorias(idcategoria) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2016 (class 2606 OID 29076)
-- Name: ucausu; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarioscategorias
    ADD CONSTRAINT ucausu FOREIGN KEY (idusuar) REFERENCES public.usuarios(idusuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2017 (class 2606 OID 29081)
-- Name: ucocoo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarioscoordinaciones
    ADD CONSTRAINT ucocoo FOREIGN KEY (idcoordinaci) REFERENCES public.coordinaciones(idcoordinacion) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2018 (class 2606 OID 29086)
-- Name: ucousu; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarioscoordinaciones
    ADD CONSTRAINT ucousu FOREIGN KEY (idusuari) REFERENCES public.usuarios(idusuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2019 (class 2606 OID 29091)
-- Name: upapag; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuariospaginas
    ADD CONSTRAINT upapag FOREIGN KEY (idpagin) REFERENCES public.paginas(idpagina) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2020 (class 2606 OID 29096)
-- Name: upausu; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuariospaginas
    ADD CONSTRAINT upausu FOREIGN KEY (idusua) REFERENCES public.usuarios(idusuario) ON UPDATE CASCADE ON DELETE CASCADE;


-- Completed on 2022-07-04 15:16:42

--
-- PostgreSQL database dump complete
--

