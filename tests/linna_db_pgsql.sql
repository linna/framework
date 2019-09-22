--
-- PostgreSQL database dump
--

-- Dumped from database version 11.5 (Ubuntu 11.5-1.pgdg19.04+1)
-- Dumped by pg_dump version 11.5 (Ubuntu 11.5-1.pgdg19.04+1)

-- Started on 2019-09-17 10:24:57 CEST

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
-- TOC entry 3016 (class 1262 OID 16992)
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
-- TOC entry 196 (class 1259 OID 19559)
-- Name: login_attempt; Type: TABLE; Schema: public; Owner: postgres
--


CREATE TABLE public.login_attempt (
    login_attempt_id serial NOT NULL,
    user_name character varying(64) NOT NULL,
    session_id character varying(255) NOT NULL,
    ip inet NOT NULL,
    date_time timestamp without time zone NOT NULL,
    last_update timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);

ALTER TABLE public.login_attempt OWNER TO postgres;

--
-- TOC entry 197 (class 1259 OID 19568)
-- Name: permission; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permission (
    permission_id serial NOT NULL,
    name character varying(32) NOT NULL,
    description text,
    last_update timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.permission OWNER TO postgres;

--
-- TOC entry 198 (class 1259 OID 19577)
-- Name: role; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role (
    role_id serial NOT NULL,
    name character varying(32) NOT NULL,
    description text,
    active boolean DEFAULT false NOT NULL,
    last_update timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.role OWNER TO postgres;

--
-- TOC entry 199 (class 1259 OID 19587)
-- Name: role_permission; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_permission (
    role_permission_id serial NOT NULL,
    role_id integer,
    permission_id integer,
    last_update timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.role_permission OWNER TO postgres;

--
-- TOC entry 200 (class 1259 OID 19605)
-- Name: session; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.session (
    session_id character varying(255) NOT NULL,
    session_data character varying(4096) NOT NULL,
    last_update timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.session OWNER TO postgres;

--
-- TOC entry 201 (class 1259 OID 19614)
-- Name: user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."user" (
    user_id serial NOT NULL,
    uuid uuid NOT NULL,
    name character varying(32) NOT NULL,
    description text,
    email character varying(64) DEFAULT NULL::bpchar,
    password character varying(255) NOT NULL,
    active boolean DEFAULT false NOT NULL,
    created timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    last_update timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public."user" OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 19628)
-- Name: user_permission; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_permission (
    user_permission_id serial NOT NULL,
    user_id integer NOT NULL,
    permission_id integer NOT NULL,
    last_update timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.user_permission OWNER TO postgres;

--
-- TOC entry 203 (class 1259 OID 19646)
-- Name: user_role; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_role (
    user_role_id serial NOT NULL,
    user_id integer NOT NULL,
    role_id integer NOT NULL,
    last_update timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.user_role OWNER TO postgres;

--
-- TOC entry 3003 (class 0 OID 19559)
-- Dependencies: 196
-- Data for Name: login_attempt; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.login_attempt (login_attempt_id, user_name, session_id, ip, date_time, last_update) FROM stdin;
\.


--
-- TOC entry 3004 (class 0 OID 19568)
-- Dependencies: 197
-- Data for Name: permission; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permission (permission_id, name, description, last_update) FROM stdin;
1	see users	Get a list of all Users	2018-10-08 15:39:39
2	update user	Update a User	2018-10-08 15:39:52
3	delete user	Delete a User	2018-10-08 15:39:52
4	create user	Create a User	2018-10-08 15:39:52
5	enable user	Enable a User	2018-10-08 15:39:52
6	disable user	Disable a User	2018-10-08 15:39:52
\.

ALTER SEQUENCE public.permission_permission_id_seq RESTART WITH 7 INCREMENT BY 1;
ALTER SEQUENCE public.role_role_id_seq RESTART WITH 4 INCREMENT BY 1;
ALTER SEQUENCE public.role_permission_role_permission_id_seq RESTART WITH 12 INCREMENT BY 1;
ALTER SEQUENCE public.user_user_id_seq RESTART WITH 8 INCREMENT BY 1;
ALTER SEQUENCE public.user_permission_user_permission_id_seq RESTART WITH 9 INCREMENT BY 1;
ALTER SEQUENCE public.user_role_user_role_id_seq RESTART WITH 14 INCREMENT BY 1;

--
-- TOC entry 3005 (class 0 OID 19577)
-- Dependencies: 198
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role (role_id, name, description, active, last_update) FROM stdin;
1	Administrator	System Administrators	t	2017-03-14 18:21:09
2	Power Users	System Power Users	t	2018-10-08 16:00:34
3	Users	System Users	t	2018-10-08 16:00:34
\.

--
-- TOC entry 3006 (class 0 OID 19587)
-- Dependencies: 199
-- Data for Name: role_permission; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_permission (role_permission_id, role_id, permission_id, last_update) FROM stdin;
1	1	1	2018-11-13 10:23:46
2	1	2	2018-11-13 10:23:46
3	1	3	2018-11-13 10:23:46
4	1	4	2018-11-13 10:23:46
5	1	5	2018-11-13 10:23:46
6	1	6	2018-11-13 10:23:46
7	2	1	2018-11-13 10:24:01
8	2	2	2018-11-13 10:25:02
9	2	5	2018-11-13 10:25:02
10	2	6	2018-11-13 10:25:02
11	3	1	2018-11-13 10:25:09
\.

--
-- TOC entry 3007 (class 0 OID 19605)
-- Dependencies: 200
-- Data for Name: session; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.session (session_id, session_data, last_update) FROM stdin;
\.


--
-- TOC entry 3008 (class 0 OID 19614)
-- Dependencies: 201
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."user" (user_id, uuid, name, description, email, password, active, created, last_update) FROM stdin;
1	ea172268-1d16-428b-9666-4d087475cf74	root	System User	\N	$2y$11$4IAn6SRaB0osPz8afZC5D.CmTrBGxnb5FQEygPjDirK9SWE/u8YuO                                                                                                                                                                                                   	t	2015-02-14 10:39:00	2017-10-29 17:59:18
2	fda9da6f-973a-4212-b151-f731128349b9	User_0	Descrizione User_0	\N	$2y$11$IeS/XWjIy2CBE6.hLJhruuHedtq.wm.PI6VJpPmVaxCPct2wSWRJa                                                                                                                                                                                                   	f	2015-08-07 18:50:44	2018-07-12 21:23:16
3	5ab77795-ad18-4150-87e3-cffb4b47d3c9	User_1	Descrizione User_1	\N	$2y$11$Jjs/IB4cKWtU/ZKwb5t6eeXbR0iWrOzqUyJDriSfcOY/dRrpf9zKC                                                                                                                                                                                                   	t	2015-08-07 18:50:44	2017-10-29 17:59:18
4	9eb97425-8f44-4ba1-a4b5-89b65026f30c	User_2	Descrizione User_2	\N	$2y$11$pJalB4tJwvD3ZHSAvhIFE.bZFC7M3QdXRJ/SOBPU7ylqQ2aW9spY.                                                                                                                                                                                                   	t	2015-08-07 18:50:44	2017-10-29 17:59:18
5	f6dbe6dd-89e8-40e5-acd6-5873caec060a	User_3	Descrizione User_3	\N	$2y$11$J/9GjpefJUvqO1clihPkEuxZrWVwDIXk1zR9XAjOEzK5HI90cr5FO                                                                                                                                                                                                   	f	2015-08-07 18:50:45	2017-10-29 17:59:18
6	1421bc55-e428-4604-b0f7-1d1b98855b2e	User_4	Descrizione User_4	\N	$2y$11$oC64K4.qGruuVqEgJV.Nxe7/f84jR2cSEywf3BmfmWnlM0wDO/ApG                                                                                                                                                                                                   	f	2015-08-07 18:50:45	2017-10-29 17:59:18
7	a36ee340-a432-4d48-85f2-5cf3f708c4ce	User_5	Descrizione User_5	\N	$2y$11$aLl8Rdz3duayXTOpNqFCUOw4aIyPQOwIEsZCMfoVJ8ZjJajt4SpPe                                                                                                                                                                                                   	f	2015-08-07 18:50:45	2017-10-29 17:59:18
\.

--
-- TOC entry 3009 (class 0 OID 19628)
-- Dependencies: 202
-- Data for Name: user_permission; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_permission (user_permission_id, user_id, permission_id, last_update) FROM stdin;
3	4	5	2018-11-13 18:34:54
4	4	6	2018-11-13 18:34:54
5	5	3	2018-11-13 18:34:54
6	5	4	2018-11-13 18:34:54
7	5	5	2018-11-13 18:34:54
8	5	6	2018-11-13 18:34:54
\.


--
-- TOC entry 3010 (class 0 OID 19646)
-- Dependencies: 203
-- Data for Name: user_role; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_role (user_role_id, user_id, role_id, last_update) FROM stdin;
1	1	1	2018-11-05 19:58:33
8	2	2	2018-11-13 10:47:49
9	3	2	2018-11-13 10:47:49
10	4	3	2018-11-13 10:47:49
11	5	3	2018-11-13 10:47:49
12	6	3	2018-11-13 10:47:49
13	7	3	2018-11-13 10:47:49
\.

--
-- TOC entry 2853 (class 2606 OID 19567)
-- Name: login_attempt login_attempt_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.login_attempt
    ADD CONSTRAINT login_attempt_pkey PRIMARY KEY (login_attempt_id);

--
-- TOC entry 2855 (class 2606 OID 19576)
-- Name: permission permission_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permission
    ADD CONSTRAINT permission_pkey PRIMARY KEY (permission_id);

--
-- TOC entry 2859 (class 2606 OID 19592)
-- Name: role_permission role_permission_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permission
    ADD CONSTRAINT role_permission_pkey PRIMARY KEY (role_permission_id);

--
-- TOC entry 2861 (class 2606 OID 19594)
-- Name: role_permission role_permission_role_id_permission_id_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permission
    ADD CONSTRAINT role_permission_role_id_permission_id_key UNIQUE (role_id, permission_id);


--
-- TOC entry 2857 (class 2606 OID 19586)
-- Name: role role_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_pkey PRIMARY KEY (role_id);

--
-- TOC entry 2863 (class 2606 OID 19613)
-- Name: session session_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.session
    ADD CONSTRAINT session_pkey PRIMARY KEY (session_id);

--
-- TOC entry 2869 (class 2606 OID 19633)
-- Name: user_permission user_permission_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_permission
    ADD CONSTRAINT user_permission_pkey PRIMARY KEY (user_permission_id);

--
-- TOC entry 2871 (class 2606 OID 19635)
-- Name: user_permission user_permission_user_id_permission_id_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_permission
    ADD CONSTRAINT user_permission_user_id_permission_id_key UNIQUE (user_id, permission_id);


--
-- TOC entry 2865 (class 2606 OID 19625)
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (user_id);

--
-- TOC entry 2873 (class 2606 OID 19651)
-- Name: user_role user_role_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_role
    ADD CONSTRAINT user_role_pkey PRIMARY KEY (user_role_id);

--
-- TOC entry 2875 (class 2606 OID 19653)
-- Name: user_role user_role_user_id_role_id_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_role
    ADD CONSTRAINT user_role_user_id_role_id_key UNIQUE (user_id, role_id);


--
-- TOC entry 2867 (class 2606 OID 19627)
-- Name: user user_uuid_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_uuid_key UNIQUE (uuid);


--
-- TOC entry 2876 (class 2606 OID 19595)
-- Name: role_permission fk_role_permission_permission; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permission
    ADD CONSTRAINT fk_role_permission_permission FOREIGN KEY (permission_id) REFERENCES public.permission(permission_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2877 (class 2606 OID 19600)
-- Name: role_permission fk_role_permission_role; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permission
    ADD CONSTRAINT fk_role_permission_role FOREIGN KEY (role_id) REFERENCES public.role(role_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2878 (class 2606 OID 19636)
-- Name: user_permission fk_user_permission_permission; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_permission
    ADD CONSTRAINT fk_user_permission_permission FOREIGN KEY (permission_id) REFERENCES public.permission(permission_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2879 (class 2606 OID 19641)
-- Name: user_permission fk_user_permission_user; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_permission
    ADD CONSTRAINT fk_user_permission_user FOREIGN KEY (user_id) REFERENCES public."user"(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2880 (class 2606 OID 19654)
-- Name: user_role fk_user_role_role; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_role
    ADD CONSTRAINT fk_user_role_role FOREIGN KEY (role_id) REFERENCES public.role(role_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2881 (class 2606 OID 19659)
-- Name: user_role fk_user_role_user; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_role
    ADD CONSTRAINT fk_user_role_user FOREIGN KEY (user_id) REFERENCES public."user"(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


-- Completed on 2019-09-17 10:24:57 CEST

--
-- PostgreSQL database dump complete
--

