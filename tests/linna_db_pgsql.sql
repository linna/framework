--
-- PostgreSQL database dump
--

-- Dumped from database version 11.5 (Ubuntu 11.5-1.pgdg18.04+1)
-- Dumped by pg_dump version 11.5

-- Started on 2019-09-16 23:30:56

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE linna_db;
--
-- TOC entry 2894 (class 1262 OID 16384)
-- Name: linna_db; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE linna_db WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8';


ALTER DATABASE linna_db OWNER TO postgres;

\connect linna_db

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 196 (class 1259 OID 16410)
-- Name: session; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.session (
    session_id character(255) NOT NULL,
    session_data character varying(4096) NOT NULL,
    last_update timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.session OWNER TO postgres;

--
-- TOC entry 2888 (class 0 OID 16410)
-- Dependencies: 196
-- Data for Name: session; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.session (session_id, session_data, last_update) FROM stdin;
\.


--
-- TOC entry 2766 (class 2606 OID 16418)
-- Name: session session_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.session
    ADD CONSTRAINT session_pkey PRIMARY KEY (session_id);


-- Completed on 2019-09-16 23:30:57

--
-- PostgreSQL database dump complete
--

