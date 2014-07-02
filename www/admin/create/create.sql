
use vasilek;

----------------- OLD_SEF_URL: -----------------
drop table if exists OLD_SEF_URL;
create table OLD_SEF_URL (OLD_SEF_URL_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),SEF_SITE_URL_ID int(12) unsigned,DATE datetime,primary key(OLD_SEF_URL_ID));
-- OLD_SEF_URL_ID,NAME,SEF_SITE_URL_ID,DATE
---------

----------------- SEF_SITE_URL: -----------------
drop table if exists SEF_SITE_URL;
create table SEF_SITE_URL (SEF_SITE_URL_ID int(12) unsigned auto_increment NOT NULL,SEF_URL varchar(255),SITE_URL varchar(255),DATE datetime,primary key(SEF_SITE_URL_ID));
-- SEF_SITE_URL_ID,SEF_URL,SITE_URL,DATE
---------

----------------- TRANSLIT_RULE: -----------------
drop table if exists TRANSLIT_RULE;
create table TRANSLIT_RULE (TRANSLIT_RULE_ID int(12) unsigned auto_increment NOT NULL,SRC varchar(255),TRANSLIT varchar(255),primary key(TRANSLIT_RULE_ID));
-- TRANSLIT_RULE_ID,SRC,TRANSLIT
---------

----------------- CALLBACK_TIME: -----------------
drop table if exists CALLBACK_TIME;
create table CALLBACK_TIME (CALLBACK_TIME_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),ORDERING int(12) unsigned,primary key(CALLBACK_TIME_ID));
-- CALLBACK_TIME_ID,NAME,ORDERING
---------

----------------- CALLBACK: -----------------
drop table if exists CALLBACK;
create table CALLBACK (CALLBACK_ID int(12) unsigned auto_increment NOT NULL,CALLBACK_TIME_ID int(12) unsigned,NAME varchar(255),PHONE varchar(255),DESCRIPTION text,STATUS int(1) unsigned,primary key(CALLBACK_ID));
-- CALLBACK_ID,CALLBACK_TIME_ID,NAME,PHONE,DESCRIPTION,STATUS
---------

----------------- COMPLAIN: -----------------
drop table if exists COMPLAIN;
create table COMPLAIN (COMPLAIN_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),PHONE varchar(255),EMAIL varchar(255),DESCRIPTION text,STATUS int(1) unsigned,primary key(COMPLAIN_ID));
-- COMPLAIN_ID,NAME,PHONE,EMAIL,DESCRIPTION,STATUS
---------

----------------- HEADER: -----------------
drop table if exists HEADER;
create table HEADER (HEADER_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),URL varchar(255),IMAGE varchar(50),DESCRIPTION text,IMAGE1 varchar(50),STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(HEADER_ID));
-- HEADER_ID,NAME,URL,IMAGE,DESCRIPTION,IMAGE1,STATUS,ORDERING
---------

----------------- LEFT_BANNS_GROUP: -----------------
drop table if exists LEFT_BANNS_GROUP;
create table LEFT_BANNS_GROUP (LEFT_BANNS_GROUP_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(LEFT_BANNS_GROUP_ID));
-- LEFT_BANNS_GROUP_ID,NAME,STATUS,ORDERING
---------

----------------- LEFT_BANNS: -----------------
drop table if exists LEFT_BANNS;
create table LEFT_BANNS (LEFT_BANNS_ID int(12) unsigned auto_increment NOT NULL,LEFT_BANNS_GROUP_ID int(12) unsigned,NAME varchar(255),URL varchar(255),IMAGE varchar(50),DESCRIPTION text,IMAGE1 varchar(50),STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(LEFT_BANNS_ID));
-- LEFT_BANNS_ID,LEFT_BANNS_GROUP_ID,NAME,URL,IMAGE,DESCRIPTION,IMAGE1,STATUS,ORDERING
---------

----------------- ARTICLE_GROUP: -----------------
drop table if exists ARTICLE_GROUP;
create table ARTICLE_GROUP (ARTICLE_GROUP_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(ARTICLE_GROUP_ID));
-- ARTICLE_GROUP_ID,NAME,STATUS,ORDERING
---------

----------------- ARTICLE: -----------------
drop table if exists ARTICLE;
create table ARTICLE (ARTICLE_ID int(12) unsigned auto_increment NOT NULL,DATA datetime,NAME varchar(255),IMAGE1 varchar(50),DESCRIPTION text,URL varchar(255),SPECIAL_URL varchar(255),STATUS int(1) unsigned,primary key(ARTICLE_ID));
-- ARTICLE_ID,DATA,NAME,IMAGE1,DESCRIPTION,URL,SPECIAL_URL,STATUS
---------

----------------- NEWS_GROUP: -----------------
drop table if exists NEWS_GROUP;
create table NEWS_GROUP (NEWS_GROUP_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),RSS varchar(255),ORDERING int(12) unsigned,primary key(NEWS_GROUP_ID));
-- NEWS_GROUP_ID,NAME,RSS,ORDERING
---------

----------------- NEWS: -----------------
drop table if exists NEWS;
create table NEWS (NEWS_ID int(12) unsigned auto_increment NOT NULL,DATA datetime,NAME varchar(255),DESCRIPTION text,URL varchar(255),SPECIAL_URL varchar(255),IMAGE1 varchar(50),STATUS int(1) unsigned,primary key(NEWS_ID));
-- NEWS_ID,DATA,NAME,DESCRIPTION,URL,SPECIAL_URL,IMAGE1,STATUS
---------

----------------- SERVICES: -----------------
drop table if exists SERVICES;
create table SERVICES (SERVICES_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),DESCRIPTION text,DATA datetime,STATUS int(1) unsigned,primary key(SERVICES_ID));
-- SERVICES_ID,NAME,DESCRIPTION,DATA,STATUS
---------

----------------- SPECIAL_OFFERS: -----------------
drop table if exists SPECIAL_OFFERS;
create table SPECIAL_OFFERS (SPECIAL_OFFERS_ID int(12) unsigned auto_increment NOT NULL,DATA datetime,NAME varchar(255),DESCRIPTION text,URL varchar(255),STATUS int(1) unsigned,primary key(SPECIAL_OFFERS_ID));
-- SPECIAL_OFFERS_ID,DATA,NAME,DESCRIPTION,URL,STATUS
---------

----------------- GALLERY_GROUP: -----------------
drop table if exists GALLERY_GROUP;
create table GALLERY_GROUP (GALLERY_GROUP_ID int(12) unsigned auto_increment NOT NULL,PARENT_ID int(12) unsigned,NAME varchar(255),IMAGE1 varchar(50),STYLE int(12) unsigned,DESCRIPTION text,STATUS int(1) unsigned,REALSTATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(GALLERY_GROUP_ID));
-- GALLERY_GROUP_ID,PARENT_ID,NAME,IMAGE1,STYLE,DESCRIPTION,STATUS,REALSTATUS,ORDERING
---------

----------------- GALLERY: -----------------
drop table if exists GALLERY;
create table GALLERY (GALLERY_ID int(12) unsigned auto_increment NOT NULL,GALLERY_GROUP_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,IMAGE1 varchar(50),IMAGE2 varchar(50),STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(GALLERY_ID));
-- GALLERY_ID,GALLERY_GROUP_ID,NAME,DESCRIPTION,IMAGE1,IMAGE2,STATUS,ORDERING
---------

----------------- GALLERY_GROUP_VIDEO: -----------------
drop table if exists GALLERY_GROUP_VIDEO;
create table GALLERY_GROUP_VIDEO (GALLERY_GROUP_VIDEO_ID int(12) unsigned auto_increment NOT NULL,PARENT_ID int(12) unsigned,NAME varchar(255),IMAGE1 varchar(50),STYLE int(12) unsigned,DESCRIPTION text,STATUS int(1) unsigned,REALSTATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(GALLERY_GROUP_VIDEO_ID));
-- GALLERY_GROUP_VIDEO_ID,PARENT_ID,NAME,IMAGE1,STYLE,DESCRIPTION,STATUS,REALSTATUS,ORDERING
---------

----------------- GALLERY_VIDEO: -----------------
drop table if exists GALLERY_VIDEO;
create table GALLERY_VIDEO (GALLERY_VIDEO_ID int(12) unsigned auto_increment NOT NULL,GALLERY_GROUP_VIDEO_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,IMAGE1 varchar(50),IMAGE2 varchar(50),CODE_VIDEO text,STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(GALLERY_VIDEO_ID));
-- GALLERY_VIDEO_ID,GALLERY_GROUP_VIDEO_ID,NAME,DESCRIPTION,IMAGE1,IMAGE2,CODE_VIDEO,STATUS,ORDERING
---------

----------------- VOPROS: -----------------
drop table if exists VOPROS;
create table VOPROS (VOPROS_ID int(12) unsigned auto_increment NOT NULL,DATA_START datetime,DATA_STOP datetime,NAME text,COUNT_ int(12) unsigned,STATUS int(1) unsigned,primary key(VOPROS_ID));
-- VOPROS_ID,DATA_START,DATA_STOP,NAME,COUNT_,STATUS
---------

----------------- OTVETS: -----------------
drop table if exists OTVETS;
create table OTVETS (OTVETS_ID int(12) unsigned auto_increment NOT NULL,VOPROS_ID int(12) unsigned,NAME text,COUNT_ int(12) unsigned,ORDERING int(12) unsigned,primary key(OTVETS_ID));
-- OTVETS_ID,VOPROS_ID,NAME,COUNT_,ORDERING
---------

----------------- OTVETS_COMMENT: -----------------
drop table if exists OTVETS_COMMENT;
create table OTVETS_COMMENT (OTVETS_COMMENT_ID int(12) unsigned auto_increment NOT NULL,OTVETS_ID int(12) unsigned,NAME varchar(255),COUNT_ int(12) unsigned,primary key(OTVETS_COMMENT_ID));
-- OTVETS_COMMENT_ID,OTVETS_ID,NAME,COUNT_
---------

----------------- MESSAGES: -----------------
drop table if exists MESSAGES;
create table MESSAGES (MESSAGES_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),TEXT text,ACTION int(12) unsigned,STATE_ int(12) unsigned,ADMIN int(12) unsigned,USERS int(12) unsigned,primary key(MESSAGES_ID));
-- MESSAGES_ID,NAME,TEXT,ACTION,STATE_,ADMIN,USERS
---------

----------------- MESSAGES_CLIENTS: -----------------
drop table if exists MESSAGES_CLIENTS;
create table MESSAGES_CLIENTS (USER_ID int(12) unsigned,MESSAGES_ID int(12) unsigned);
-- USER_ID,MESSAGES_ID
---------

----------------- FILE_TYPES: -----------------
drop table if exists FILE_TYPES;
create table FILE_TYPES (FILE_TYPES_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),EXT varchar(255),IMAGE1 varchar(50),primary key(FILE_TYPES_ID));
-- FILE_TYPES_ID,NAME,EXT,IMAGE1
---------

----------------- COUNTRY: -----------------
drop table if exists COUNTRY;
create table COUNTRY (COUNTRY_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),STATUS int(1) unsigned,primary key(COUNTRY_ID));
-- COUNTRY_ID,NAME,STATUS
---------

----------------- SCOPE: -----------------
drop table if exists SCOPE;
create table SCOPE (SCOPE_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),STATUS int(1) unsigned,primary key(SCOPE_ID));
-- SCOPE_ID,NAME,STATUS
---------

----------------- PRODUCT_TYPE: -----------------
drop table if exists PRODUCT_TYPE;
create table PRODUCT_TYPE (PRODUCT_TYPE_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),STATUS int(1) unsigned,primary key(PRODUCT_TYPE_ID));
-- PRODUCT_TYPE_ID,NAME,STATUS
---------

----------------- CLIENT: -----------------
drop table if exists CLIENT;
create table CLIENT (CLIENT_ID int(12) unsigned auto_increment NOT NULL,COUNTRY_ID int(12) unsigned,SCOPE_ID int(12) unsigned,NAME varchar(255),EMAIL varchar(255),URL varchar(255),IMAGE1 varchar(50),DESCRIPTION text,STATUS_MAIN int(1) unsigned,STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(CLIENT_ID));
-- CLIENT_ID,COUNTRY_ID,SCOPE_ID,NAME,EMAIL,URL,IMAGE1,DESCRIPTION,STATUS_MAIN,STATUS,ORDERING
---------

----------------- CLIENT_VOPROS: -----------------
drop table if exists CLIENT_VOPROS;
create table CLIENT_VOPROS (CLIENT_VOPROS_ID int(12) unsigned auto_increment NOT NULL,DATA_START datetime,DATA_STOP datetime,NAME text,STATUS int(1) unsigned,USERS int(12) unsigned,primary key(CLIENT_VOPROS_ID));
-- CLIENT_VOPROS_ID,DATA_START,DATA_STOP,NAME,STATUS,USERS
---------

----------------- CLIENT_VOPROS_CLIENTS: -----------------
drop table if exists CLIENT_VOPROS_CLIENTS;
create table CLIENT_VOPROS_CLIENTS (CLIENT_ID int(12) unsigned,CLIENT_VOPROS_ID int(12) unsigned,CLIENT_HASH varchar(255),STATUS int(1) unsigned);
-- CLIENT_ID,CLIENT_VOPROS_ID,CLIENT_HASH,STATUS
---------

----------------- CLIENT_OTVETS: -----------------
drop table if exists CLIENT_OTVETS;
create table CLIENT_OTVETS (CLIENT_OTVETS_ID int(12) unsigned auto_increment NOT NULL,CLIENT_VOPROS_ID int(12) unsigned,NAME text,ORDERING int(12) unsigned,primary key(CLIENT_OTVETS_ID));
-- CLIENT_OTVETS_ID,CLIENT_VOPROS_ID,NAME,ORDERING
---------

----------------- ANNOUNCEMENT_RUBRICS: -----------------
drop table if exists ANNOUNCEMENT_RUBRICS;
create table ANNOUNCEMENT_RUBRICS (ANNOUNCEMENT_RUBRICS_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),STATUS int(1) unsigned,primary key(ANNOUNCEMENT_RUBRICS_ID));
-- ANNOUNCEMENT_RUBRICS_ID,NAME,STATUS
---------

----------------- ANNOUNCEMENT_TYPES: -----------------
drop table if exists ANNOUNCEMENT_TYPES;
create table ANNOUNCEMENT_TYPES (ANNOUNCEMENT_TYPES_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),STATUS int(1) unsigned,primary key(ANNOUNCEMENT_TYPES_ID));
-- ANNOUNCEMENT_TYPES_ID,NAME,STATUS
---------

----------------- ANNOUNCEMENT: -----------------
drop table if exists ANNOUNCEMENT;
create table ANNOUNCEMENT (ANNOUNCEMENT_ID int(12) unsigned auto_increment NOT NULL,ANNOUNCEMENT_RUBRICS_ID int(12) unsigned,ANNOUNCEMENT_TYPES_ID int(12) unsigned,TITLE varchar(255),TEXT text,ORGANIZATION varchar(255),COUNTRY varchar(255),CITY varchar(255),NAME varchar(255),PHONE varchar(255),FAX varchar(255),EMAIL varchar(255),DATE datetime,STATUS int(1) unsigned,primary key(ANNOUNCEMENT_ID));
-- ANNOUNCEMENT_ID,ANNOUNCEMENT_RUBRICS_ID,ANNOUNCEMENT_TYPES_ID,TITLE,TEXT,ORGANIZATION,COUNTRY,CITY,NAME,PHONE,FAX,EMAIL,DATE,STATUS
---------

----------------- QUESTION_GROUP: -----------------
drop table if exists QUESTION_GROUP;
create table QUESTION_GROUP (QUESTION_GROUP_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(QUESTION_GROUP_ID));
-- QUESTION_GROUP_ID,NAME,STATUS,ORDERING
---------

----------------- QUESTION: -----------------
drop table if exists QUESTION;
create table QUESTION (QUESTION_ID int(12) unsigned auto_increment NOT NULL,QUESTION_GROUP_ID int(12) unsigned,QUESTION text,ANSWER text,STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(QUESTION_ID));
-- QUESTION_ID,QUESTION_GROUP_ID,QUESTION,ANSWER,STATUS,ORDERING
---------

----------------- TEXTES: -----------------
drop table if exists TEXTES;
create table TEXTES (TEXTES_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),SYS_NAME varchar(255),DESCRIPTION text,IMAGE varchar(50),primary key(TEXTES_ID));
-- TEXTES_ID,NAME,SYS_NAME,DESCRIPTION,IMAGE
---------

----------------- ALIGN: -----------------
drop table if exists ALIGN;
create table ALIGN (ALIGN_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),primary key(ALIGN_ID));
-- ALIGN_ID,NAME
---------

----------------- BANN_SECTION: -----------------
drop table if exists BANN_SECTION;
create table BANN_SECTION (BANN_SECTION_ID int(12) unsigned NOT NULL,NAME varchar(255),primary key(BANN_SECTION_ID));
-- BANN_SECTION_ID,NAME
---------

----------------- CATALOGUE: -----------------
drop table if exists CATALOGUE;
create table CATALOGUE (CATALOGUE_ID int(12) unsigned auto_increment NOT NULL,PARENT_ID int(12),NAME varchar(255),CATNAME varchar(255),REALCATNAME varchar(255),URL varchar(255),SPECIAL_URL varchar(255),TITLE varchar(255),DESCRIPTION text,COLOR_STYLE int(12) unsigned,IMAGE1 varchar(50),IMAGE2 varchar(50),COUNT_ int(12) unsigned,STATUS int(1) unsigned,STATUS_MAIN int(1) unsigned,TO_PARENT int(1) unsigned,ITEM_IS_DESCR int(1) unsigned,IN_MENU int(1) unsigned,HTML_KEYWORDS text,HTML_DESCRIPTION text,REALSTATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(CATALOGUE_ID));
-- CATALOGUE_ID,PARENT_ID,NAME,CATNAME,REALCATNAME,URL,SPECIAL_URL,TITLE,DESCRIPTION,COLOR_STYLE,IMAGE1,IMAGE2,COUNT_,STATUS,STATUS_MAIN,TO_PARENT,ITEM_IS_DESCR,IN_MENU,HTML_KEYWORDS,HTML_DESCRIPTION,REALSTATUS,ORDERING
---------

----------------- ITEM: -----------------
drop table if exists ITEM;
create table ITEM (ITEM_ID int(12) unsigned auto_increment NOT NULL,CATALOGUE_ID int(12) unsigned,NAME varchar(255),SPECIAL_URL varchar(255),IMAGE varchar(50),IMAGE1 varchar(50),IMAGE2 varchar(50),POP_IMAGE_TEXT text,UNDER_IMAGE_TEXT text,DESCRIPTION text,CODE_MAP_AREA text,HTML_TITLE varchar(255),HTML_KEYWORDS text,HTML_DESCRIPTION text,IS_FORM int(1) unsigned,STATUS_MAIN int(1) unsigned,STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(ITEM_ID));
-- ITEM_ID,CATALOGUE_ID,NAME,SPECIAL_URL,IMAGE,IMAGE1,IMAGE2,POP_IMAGE_TEXT,UNDER_IMAGE_TEXT,DESCRIPTION,CODE_MAP_AREA,HTML_TITLE,HTML_KEYWORDS,HTML_DESCRIPTION,IS_FORM,STATUS_MAIN,STATUS,ORDERING
---------

----------------- ITEM_ELEMENTS: -----------------
drop table if exists ITEM_ELEMENTS;
create table ITEM_ELEMENTS (ITEM_ELEMENTS_ID int(12) unsigned auto_increment NOT NULL,ITEM_ID int(12) unsigned,NAME varchar(255),NAME_NUM varchar(255),IMAGE1 varchar(50),DESCRIPTION text,primary key(ITEM_ELEMENTS_ID));
-- ITEM_ELEMENTS_ID,ITEM_ID,NAME,NAME_NUM,IMAGE1,DESCRIPTION
---------

----------------- SITE_PARAM: -----------------
drop table if exists SITE_PARAM;
create table SITE_PARAM (PARAM_ID int(12) unsigned auto_increment NOT NULL,SYSNAME varchar(255),NAME varchar(255),VALUE varchar(255),STATUS int(1) unsigned,primary key(PARAM_ID));
-- PARAM_ID,SYSNAME,NAME,VALUE,STATUS
---------

----------------- MANAGERS: -----------------
drop table if exists MANAGERS;
create table MANAGERS (MANAGER_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),EMAIL varchar(255),EMAIL_STATUS int(1) unsigned,STATUS int(1) unsigned,primary key(MANAGER_ID));
-- MANAGER_ID,NAME,EMAIL,EMAIL_STATUS,STATUS
---------

----------------- ANOTHER_PAGES: -----------------
drop table if exists ANOTHER_PAGES;
create table ANOTHER_PAGES (ANOTHER_PAGES_ID int(12) unsigned auto_increment NOT NULL,PARENT_ID int(12) unsigned,NAME varchar(255),IMAGE1 varchar(50),CATNAME varchar(255),REALCATNAME varchar(255),URL varchar(255),SPECIAL_URL varchar(255),MENU_WIDTH varchar(255),TITLE text,DESCRIPTION text,KEYWORDS text,IS_NODE int(1) unsigned,VIA_JS int(1) unsigned,IS_NEW_WIN int(1) unsigned,STATUS int(1) unsigned,REALSTATUS int(1) unsigned,ORDER_ int(12) unsigned,primary key(ANOTHER_PAGES_ID));
-- ANOTHER_PAGES_ID,PARENT_ID,NAME,IMAGE1,CATNAME,REALCATNAME,URL,SPECIAL_URL,MENU_WIDTH,TITLE,DESCRIPTION,KEYWORDS,IS_NODE,VIA_JS,IS_NEW_WIN,STATUS,REALSTATUS,ORDER_
---------

----------------- XMLS: -----------------
drop table if exists XMLS;
create table XMLS (XMLS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,TYPE int(12) unsigned NOT NULL,XML text,primary key(XMLS_ID,TYPE));
-- XMLS_ID,CMF_LANG_ID,TYPE,XML
---------

----------------- SETINGS: -----------------
drop table if exists SETINGS;
create table SETINGS (SETINGS_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),SYSTEM_NAME varchar(255),VALUE text,IMAGE varchar(50),primary key(SETINGS_ID));
-- SETINGS_ID,NAME,SYSTEM_NAME,VALUE,IMAGE
---------

----------------- SEQUENCES: -----------------
drop table if exists SEQUENCES;
create table SEQUENCES (SEQUENCES_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),ID int(12) unsigned,primary key(SEQUENCES_ID));
-- SEQUENCES_ID,NAME,ID
---------

----------------- CMF_USER: -----------------
drop table if exists CMF_USER;
create table CMF_USER (CMF_USER_ID int(12) unsigned auto_increment NOT NULL,MD5_ varchar(255),NAME varchar(255),LOGIN varchar(255),PASS_ varchar(255),URL varchar(255),STATUS int(1) unsigned,primary key(CMF_USER_ID));
-- CMF_USER_ID,MD5_,NAME,LOGIN,PASS_,URL,STATUS
---------

----------------- CMF_GROUP: -----------------
drop table if exists CMF_GROUP;
create table CMF_GROUP (CMF_GROUP_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),URL varchar(255),primary key(CMF_GROUP_ID));
-- CMF_GROUP_ID,NAME,URL
---------

----------------- CMF_SCRIPT: -----------------
drop table if exists CMF_SCRIPT;
create table CMF_SCRIPT (CMF_SCRIPT_ID int(12) unsigned auto_increment NOT NULL,PARENT_ID int(12) unsigned,ARTICLE varchar(255),NAME varchar(255),URL varchar(255),DESCRIPTION text,IMAGE varchar(50),BACKGROUND varchar(255),TYPE int(12) unsigned,STATUS int(1) unsigned,REALSTATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(CMF_SCRIPT_ID));
-- CMF_SCRIPT_ID,PARENT_ID,ARTICLE,NAME,URL,DESCRIPTION,IMAGE,BACKGROUND,TYPE,STATUS,REALSTATUS,ORDERING
---------

----------------- CMF_XMLS_ARTICLE: -----------------
drop table if exists CMF_XMLS_ARTICLE;
create table CMF_XMLS_ARTICLE (TYPE int(12) unsigned NOT NULL,ARTICLE varchar(255),EDIT varchar(255),VIEW varchar(255),primary key(TYPE));
-- TYPE,ARTICLE,EDIT,VIEW
---------

----------------- CMF_BUG: -----------------
drop table if exists CMF_BUG;
create table CMF_BUG (CMF_BUG_ID int(12) unsigned auto_increment NOT NULL,CMF_USER_ID int(12) unsigned,DATA datetime,URL varchar(255),DESCRIPTION text,STATUS int(12) unsigned,primary key(CMF_BUG_ID));
-- CMF_BUG_ID,CMF_USER_ID,DATA,URL,DESCRIPTION,STATUS
---------

----------------- CMF_FIELDS: -----------------
drop table if exists CMF_FIELDS;
create table CMF_FIELDS (CMF_FIELDS_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),TITLE varchar(255),TYPE int(12) unsigned,VALUE_ varchar(255),STATUS int(1) unsigned,IS_MANDATORY int(1) unsigned,ORDERING int(12) unsigned,primary key(CMF_FIELDS_ID));
-- CMF_FIELDS_ID,NAME,TITLE,TYPE,VALUE_,STATUS,IS_MANDATORY,ORDERING
---------

----------------- CMF_FIELDS_LIST: -----------------
drop table if exists CMF_FIELDS_LIST;
create table CMF_FIELDS_LIST (CMF_FIELDS_LIST_ID int(12) unsigned auto_increment NOT NULL,CMF_FIELDS_ID int(12) unsigned,NAME varchar(255),STATUS int(1) unsigned,primary key(CMF_FIELDS_LIST_ID));
-- CMF_FIELDS_LIST_ID,CMF_FIELDS_ID,NAME,STATUS
---------

----------------- CMF_LANG: -----------------
drop table if exists CMF_LANG;
create table CMF_LANG (CMF_LANG_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),SYSTEM_NAME varchar(255),ORDERING int(12) unsigned,IS_DEFAULT int(1) unsigned,COMMENT_ text,STATUS int(1) unsigned,primary key(CMF_LANG_ID));
-- CMF_LANG_ID,NAME,SYSTEM_NAME,ORDERING,IS_DEFAULT,COMMENT_,STATUS
---------

----------------- CMF_ERRORS_GROUP: -----------------
drop table if exists CMF_ERRORS_GROUP;
create table CMF_ERRORS_GROUP (CMF_ERRORS_GROUP_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),ORDERING int(12) unsigned,STATUS int(1) unsigned,primary key(CMF_ERRORS_GROUP_ID));
-- CMF_ERRORS_GROUP_ID,NAME,ORDERING,STATUS
---------

----------------- CMF_ERRORS: -----------------
drop table if exists CMF_ERRORS;
create table CMF_ERRORS (CMF_ERRORS_ID int(12) unsigned auto_increment NOT NULL,CMF_ERRORS_GROUP_ID int(12) unsigned,SYSTEM_NAME varchar(255),NAME varchar(255),STATUS int(1) unsigned,primary key(CMF_ERRORS_ID));
-- CMF_ERRORS_ID,CMF_ERRORS_GROUP_ID,SYSTEM_NAME,NAME,STATUS
---------

----------------- ZAKAZSTATUS: -----------------
drop table if exists ZAKAZSTATUS;
create table ZAKAZSTATUS (ZAKAZSTATUS_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),ORDERING int(12) unsigned,primary key(ZAKAZSTATUS_ID));
-- ZAKAZSTATUS_ID,NAME,ORDERING
---------

----------------- ZAKAZ: -----------------
drop table if exists ZAKAZ;
create table ZAKAZ (ZAKAZ_ID int(12) unsigned auto_increment NOT NULL,DATA datetime,NAME varchar(255),EMAIL varchar(255),TELMOB varchar(255),CITY varchar(255),COMPANY varchar(255),DESCRIPTION text,STATUS int(12) unsigned,primary key(ZAKAZ_ID));
-- ZAKAZ_ID,DATA,NAME,EMAIL,TELMOB,CITY,COMPANY,DESCRIPTION,STATUS
---------

----------------- PROJECTS: -----------------
drop table if exists PROJECTS;
create table PROJECTS (PROJECTS_ID int(12) unsigned auto_increment NOT NULL,DATA datetime,NAME varchar(255),DESCRIPTION text,SPECIAL_URL varchar(255),IMAGE1 varchar(50),STATUS int(1) unsigned,primary key(PROJECTS_ID));
-- PROJECTS_ID,DATA,NAME,DESCRIPTION,SPECIAL_URL,IMAGE1,STATUS
---------

----------------- REFERER_PHONES: -----------------
drop table if exists REFERER_PHONES;
create table REFERER_PHONES (REFERER_PHONES_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),PHONE varchar(255),DOMENS varchar(255),CRITERIA varchar(255),primary key(REFERER_PHONES_ID));
-- REFERER_PHONES_ID,NAME,PHONE,DOMENS,CRITERIA
---------

----------------- BOOKLETS: -----------------
drop table if exists BOOKLETS;
create table BOOKLETS (BOOKLETS_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),IMAGE_NAME varchar(50),FILE_NAME varchar(50),PATH_FILE varchar(255),STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(BOOKLETS_ID));
-- BOOKLETS_ID,NAME,IMAGE_NAME,FILE_NAME,PATH_FILE,STATUS,ORDERING
---------

----------------- SOKOBAN: -----------------
drop table if exists SOKOBAN;
create table SOKOBAN (SOKOBAN_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),LEVEL int(12) unsigned,LEVEL_CODE text,STATUS int(1) unsigned,primary key(SOKOBAN_ID));
-- SOKOBAN_ID,NAME,LEVEL,LEVEL_CODE,STATUS
---------

----------------- CALCULATOR: -----------------
drop table if exists CALCULATOR;
create table CALCULATOR (CALCULATOR_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),IMAGE1 varchar(50),INDENT varchar(255),STATUS int(1) unsigned,ORDER_ int(12) unsigned,primary key(CALCULATOR_ID));
-- CALCULATOR_ID,NAME,IMAGE1,INDENT,STATUS,ORDER_
---------

----------------- joined CALLBACK_TIME_LANG: -----------------
drop table if exists CALLBACK_TIME_LANG;
create table CALLBACK_TIME_LANG (
CALLBACK_TIME_ID int(12) unsigned auto_increment NOT NULL,
CALLBACK_TIME_LANG_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),ORDERING int(12) unsigned,primary key(CALLBACK_TIME_ID,CALLBACK_TIME_LANG_ID));
-- CALLBACK_TIME_LANG_ID,CMF_LANG_ID,NAME,ORDERING
---------

----------------- joined HEADER_CMF_LANG: -----------------
drop table if exists HEADER_CMF_LANG;
create table HEADER_CMF_LANG (
HEADER_ID int(12) unsigned auto_increment NOT NULL,
HEADER_CMF_LANG_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,IMAGE varchar(50),URL varchar(255),DESCRIPTION text,IMAGE1 varchar(50),primary key(HEADER_ID,HEADER_CMF_LANG_ID));
-- HEADER_CMF_LANG_ID,CMF_LANG_ID,IMAGE,URL,DESCRIPTION,IMAGE1
---------

----------------- joined LEFT_BANNS_CMF_LANG: -----------------
drop table if exists LEFT_BANNS_CMF_LANG;
create table LEFT_BANNS_CMF_LANG (
LEFT_BANNS_ID int(12) unsigned auto_increment NOT NULL,
LEFT_BANNS_CMF_LANG_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,IMAGE varchar(50),URL varchar(255),DESCRIPTION text,IMAGE1 varchar(50),primary key(LEFT_BANNS_ID,LEFT_BANNS_CMF_LANG_ID));
-- LEFT_BANNS_CMF_LANG_ID,CMF_LANG_ID,IMAGE,URL,DESCRIPTION,IMAGE1
---------

----------------- joined ARTICLE_GROUP_LANGS: -----------------
drop table if exists ARTICLE_GROUP_LANGS;
create table ARTICLE_GROUP_LANGS (
ARTICLE_GROUP_ID int(12) unsigned auto_increment NOT NULL,
ARTICLE_GROUP_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),primary key(ARTICLE_GROUP_ID,ARTICLE_GROUP_LANGS_ID));
-- ARTICLE_GROUP_LANGS_ID,CMF_LANG_ID,NAME
---------

----------------- joined ARTICLE_LANGS: -----------------
drop table if exists ARTICLE_LANGS;
create table ARTICLE_LANGS (
ARTICLE_ID int(12) unsigned auto_increment NOT NULL,
ARTICLE_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(ARTICLE_ID,ARTICLE_LANGS_ID));
-- ARTICLE_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined NEWS_GROUP_LANGS: -----------------
drop table if exists NEWS_GROUP_LANGS;
create table NEWS_GROUP_LANGS (
NEWS_GROUP_ID int(12) unsigned auto_increment NOT NULL,
NEWS_GROUP_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),primary key(NEWS_GROUP_ID,NEWS_GROUP_LANGS_ID));
-- NEWS_GROUP_LANGS_ID,CMF_LANG_ID,NAME
---------

----------------- joined NEWS_LANGS: -----------------
drop table if exists NEWS_LANGS;
create table NEWS_LANGS (
NEWS_ID int(12) unsigned auto_increment NOT NULL,
NEWS_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(NEWS_ID,NEWS_LANGS_ID));
-- NEWS_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined SERVICES_LANGS: -----------------
drop table if exists SERVICES_LANGS;
create table SERVICES_LANGS (
SERVICES_ID int(12) unsigned auto_increment NOT NULL,
SERVICES_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(SERVICES_ID,SERVICES_LANGS_ID));
-- SERVICES_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined SPECIAL_OFFERS_LANGS: -----------------
drop table if exists SPECIAL_OFFERS_LANGS;
create table SPECIAL_OFFERS_LANGS (
SPECIAL_OFFERS_ID int(12) unsigned auto_increment NOT NULL,
SPECIAL_OFFERS_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(SPECIAL_OFFERS_ID,SPECIAL_OFFERS_LANGS_ID));
-- SPECIAL_OFFERS_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined GALLERY_GROUP_LANGS: -----------------
drop table if exists GALLERY_GROUP_LANGS;
create table GALLERY_GROUP_LANGS (
GALLERY_GROUP_ID int(12) unsigned auto_increment NOT NULL,
GALLERY_GROUP_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(GALLERY_GROUP_ID,GALLERY_GROUP_LANGS_ID));
-- GALLERY_GROUP_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined GALLERY_LANGS: -----------------
drop table if exists GALLERY_LANGS;
create table GALLERY_LANGS (
GALLERY_ID int(12) unsigned auto_increment NOT NULL,
GALLERY_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(GALLERY_ID,GALLERY_LANGS_ID));
-- GALLERY_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined GALLERY_GROUP_VIDEO_LANGS: -----------------
drop table if exists GALLERY_GROUP_VIDEO_LANGS;
create table GALLERY_GROUP_VIDEO_LANGS (
GALLERY_GROUP_VIDEO_ID int(12) unsigned auto_increment NOT NULL,
GALLERY_GROUP_VIDEO_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(GALLERY_GROUP_VIDEO_ID,GALLERY_GROUP_VIDEO_LANGS_ID));
-- GALLERY_GROUP_VIDEO_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined GALLERY_VIDEO_LANGS: -----------------
drop table if exists GALLERY_VIDEO_LANGS;
create table GALLERY_VIDEO_LANGS (
GALLERY_VIDEO_ID int(12) unsigned auto_increment NOT NULL,
GALLERY_VIDEO_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(GALLERY_VIDEO_ID,GALLERY_VIDEO_LANGS_ID));
-- GALLERY_VIDEO_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined VOPROS_LANGS: -----------------
drop table if exists VOPROS_LANGS;
create table VOPROS_LANGS (
VOPROS_ID int(12) unsigned auto_increment NOT NULL,
VOPROS_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),primary key(VOPROS_ID,VOPROS_LANGS_ID));
-- VOPROS_LANGS_ID,CMF_LANG_ID,NAME
---------

----------------- joined OTVETS_LANGS: -----------------
drop table if exists OTVETS_LANGS;
create table OTVETS_LANGS (
OTVETS_ID int(12) unsigned auto_increment NOT NULL,
OTVETS_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),primary key(OTVETS_ID,OTVETS_LANGS_ID));
-- OTVETS_LANGS_ID,CMF_LANG_ID,NAME
---------

----------------- joined CLIENT_PRODUCT_TYPE: -----------------
drop table if exists CLIENT_PRODUCT_TYPE;
create table CLIENT_PRODUCT_TYPE (
CLIENT_ID int(12) unsigned auto_increment NOT NULL,
CLIENT_PRODUCT_TYPE_ID int(12) unsigned auto_increment NOT NULL,PRODUCT_TYPE_ID int(12) unsigned,primary key(CLIENT_ID,CLIENT_PRODUCT_TYPE_ID));
-- CLIENT_PRODUCT_TYPE_ID,PRODUCT_TYPE_ID
---------

----------------- joined ANNOUNCEMENT_RUBRICS_LANGS: -----------------
drop table if exists ANNOUNCEMENT_RUBRICS_LANGS;
create table ANNOUNCEMENT_RUBRICS_LANGS (
ANNOUNCEMENT_RUBRICS_ID int(12) unsigned auto_increment NOT NULL,
ANNOUNCEMENT_RUBRICS_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),primary key(ANNOUNCEMENT_RUBRICS_ID,ANNOUNCEMENT_RUBRICS_LANGS_ID));
-- ANNOUNCEMENT_RUBRICS_LANGS_ID,CMF_LANG_ID,NAME
---------

----------------- joined ANNOUNCEMENT_TYPES_LANGS: -----------------
drop table if exists ANNOUNCEMENT_TYPES_LANGS;
create table ANNOUNCEMENT_TYPES_LANGS (
ANNOUNCEMENT_TYPES_ID int(12) unsigned auto_increment NOT NULL,
ANNOUNCEMENT_TYPES_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),primary key(ANNOUNCEMENT_TYPES_ID,ANNOUNCEMENT_TYPES_LANGS_ID));
-- ANNOUNCEMENT_TYPES_LANGS_ID,CMF_LANG_ID,NAME
---------

----------------- joined QUESTION_GROUP_LANGS: -----------------
drop table if exists QUESTION_GROUP_LANGS;
create table QUESTION_GROUP_LANGS (
QUESTION_GROUP_ID int(12) unsigned auto_increment NOT NULL,
QUESTION_GROUP_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME text,ORDERING int(12) unsigned,primary key(QUESTION_GROUP_ID,QUESTION_GROUP_LANGS_ID));
-- QUESTION_GROUP_LANGS_ID,CMF_LANG_ID,NAME,ORDERING
---------

----------------- joined QUESTION_LANGS: -----------------
drop table if exists QUESTION_LANGS;
create table QUESTION_LANGS (
QUESTION_ID int(12) unsigned auto_increment NOT NULL,
QUESTION_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,QUESTION text,ANSWER text,ORDERING int(12) unsigned,primary key(QUESTION_ID,QUESTION_LANGS_ID));
-- QUESTION_LANGS_ID,CMF_LANG_ID,QUESTION,ANSWER,ORDERING
---------

----------------- joined TEXTES_LANGS: -----------------
drop table if exists TEXTES_LANGS;
create table TEXTES_LANGS (
TEXTES_ID int(12) unsigned auto_increment NOT NULL,
TEXTES_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,DESCRIPTION text,IMAGE varchar(50),primary key(TEXTES_ID,TEXTES_LANGS_ID));
-- TEXTES_LANGS_ID,CMF_LANG_ID,DESCRIPTION,IMAGE
---------

----------------- joined SECTION_ALIGN: -----------------
drop table if exists SECTION_ALIGN;
create table SECTION_ALIGN (
BANN_SECTION_ID int(12) unsigned NOT NULL,
SECTION_ALIGN_ID int(12) unsigned auto_increment NOT NULL,ALIGN_ID int(12) unsigned,IMAGE1 varchar(50),ALT varchar(255),DESCRIPTION text,BANNER_CODE text,TYPE int(12) unsigned,URL varchar(255),NEWWIN int(1) unsigned,STATUS int(1) unsigned,primary key(BANN_SECTION_ID,SECTION_ALIGN_ID));
-- SECTION_ALIGN_ID,ALIGN_ID,IMAGE1,ALT,DESCRIPTION,BANNER_CODE,TYPE,URL,NEWWIN,STATUS
---------

----------------- joined CATALOGUE_LANGS: -----------------
drop table if exists CATALOGUE_LANGS;
create table CATALOGUE_LANGS (
CATALOGUE_ID int(12) unsigned auto_increment NOT NULL,
CATALOGUE_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,TYPENAME varchar(255),NAME varchar(255),TITLE text,DESCRIPTION text,HTML_KEYWORDS text,HTML_DESCRIPTION text,primary key(CATALOGUE_ID,CATALOGUE_LANGS_ID));
-- CATALOGUE_LANGS_ID,CMF_LANG_ID,TYPENAME,NAME,TITLE,DESCRIPTION,HTML_KEYWORDS,HTML_DESCRIPTION
---------

----------------- joined CATALOGUE_ARTICLE_GROUP: -----------------
drop table if exists CATALOGUE_ARTICLE_GROUP;
create table CATALOGUE_ARTICLE_GROUP (
CATALOGUE_ID int(12) unsigned auto_increment NOT NULL,
CATALOGUE_ARTICLE_GROUP_ID int(12) unsigned NOT NULL,ARTICLE_GROUP_ID int(12) unsigned,primary key(CATALOGUE_ID,CATALOGUE_ARTICLE_GROUP_ID));
-- CATALOGUE_ARTICLE_GROUP_ID,ARTICLE_GROUP_ID
---------

----------------- joined ITEM_LANGS: -----------------
drop table if exists ITEM_LANGS;
create table ITEM_LANGS (
ITEM_ID int(12) unsigned auto_increment NOT NULL,
ITEM_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),POP_IMAGE_TEXT text,UNDER_IMAGE_TEXT text,DESCRIPTION text,HTML_TITLE varchar(255),HTML_KEYWORDS text,HTML_DESCRIPTION text,primary key(ITEM_ID,ITEM_LANGS_ID));
-- ITEM_LANGS_ID,CMF_LANG_ID,NAME,POP_IMAGE_TEXT,UNDER_IMAGE_TEXT,DESCRIPTION,HTML_TITLE,HTML_KEYWORDS,HTML_DESCRIPTION
---------

----------------- joined ITEM_PHOTO: -----------------
drop table if exists ITEM_PHOTO;
create table ITEM_PHOTO (
ITEM_ID int(12) unsigned auto_increment NOT NULL,
ITEM_PHOTO_ID int(12) unsigned auto_increment NOT NULL,GALLERY_ID int(12) unsigned,ORDERING_ int(12) unsigned,primary key(ITEM_ID,ITEM_PHOTO_ID));
-- ITEM_PHOTO_ID,GALLERY_ID,ORDERING_
---------

----------------- joined ITEM_ELEMENTS_LANGS: -----------------
drop table if exists ITEM_ELEMENTS_LANGS;
create table ITEM_ELEMENTS_LANGS (
ITEM_ELEMENTS_ID int(12) unsigned auto_increment NOT NULL,
ITEM_ELEMENTS_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(ITEM_ELEMENTS_ID,ITEM_ELEMENTS_LANGS_ID));
-- ITEM_ELEMENTS_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined ANOTHER_PAGES_LANGS: -----------------
drop table if exists ANOTHER_PAGES_LANGS;
create table ANOTHER_PAGES_LANGS (
ANOTHER_PAGES_ID int(12) unsigned auto_increment NOT NULL,
ANOTHER_PAGES_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),TITLE text,DESCRIPTION text,KEYWORDS text,primary key(ANOTHER_PAGES_ID,ANOTHER_PAGES_LANGS_ID));
-- ANOTHER_PAGES_LANGS_ID,CMF_LANG_ID,NAME,TITLE,DESCRIPTION,KEYWORDS
---------

----------------- joined CMF_USER_GROUP: -----------------
drop table if exists CMF_USER_GROUP;
create table CMF_USER_GROUP (
CMF_USER_ID int(12) unsigned auto_increment NOT NULL,
CMF_GROUP_ID int(12) unsigned NOT NULL,primary key(CMF_USER_ID,CMF_GROUP_ID));
-- CMF_GROUP_ID
---------

----------------- joined CMF_SCRIPT_USER: -----------------
drop table if exists CMF_SCRIPT_USER;
create table CMF_SCRIPT_USER (
CMF_SCRIPT_ID int(12) unsigned auto_increment NOT NULL,
CMF_USER_ID int(12) unsigned NOT NULL,R int(1) unsigned,W int(1) unsigned,D int(1) unsigned,primary key(CMF_SCRIPT_ID,CMF_USER_ID));
-- CMF_USER_ID,R,W,D
---------

----------------- joined CMF_SCRIPT_GROUP: -----------------
drop table if exists CMF_SCRIPT_GROUP;
create table CMF_SCRIPT_GROUP (
CMF_SCRIPT_ID int(12) unsigned auto_increment NOT NULL,
CMF_GROUP_ID int(12) unsigned NOT NULL,R int(1) unsigned,W int(1) unsigned,D int(1) unsigned,primary key(CMF_SCRIPT_ID,CMF_GROUP_ID));
-- CMF_GROUP_ID,R,W,D
---------

----------------- joined CMF_ERRORS_LANGS: -----------------
drop table if exists CMF_ERRORS_LANGS;
create table CMF_ERRORS_LANGS (
CMF_ERRORS_ID int(12) unsigned auto_increment NOT NULL,
CMF_ERRORS_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),primary key(CMF_ERRORS_ID,CMF_ERRORS_LANGS_ID));
-- CMF_ERRORS_LANGS_ID,CMF_LANG_ID,NAME
---------

----------------- joined ZAKAZ_ITEM: -----------------
drop table if exists ZAKAZ_ITEM;
create table ZAKAZ_ITEM (
ZAKAZ_ID int(12) unsigned auto_increment NOT NULL,
ZAKAZ_ITEM_ID int(12) unsigned NOT NULL,ITEM_ID int(12) unsigned,CATALOGUE_ID int(12) unsigned,NAME varchar(255),primary key(ZAKAZ_ID,ZAKAZ_ITEM_ID));
-- ZAKAZ_ITEM_ID,ITEM_ID,CATALOGUE_ID,NAME
---------

----------------- joined PROJECTS_LANGS: -----------------
drop table if exists PROJECTS_LANGS;
create table PROJECTS_LANGS (
PROJECTS_ID int(12) unsigned auto_increment NOT NULL,
PROJECTS_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,NAME varchar(255),DESCRIPTION text,primary key(PROJECTS_ID,PROJECTS_LANGS_ID));
-- PROJECTS_LANGS_ID,CMF_LANG_ID,NAME,DESCRIPTION
---------

----------------- joined BOOKLETS_PAGES: -----------------
drop table if exists BOOKLETS_PAGES;
create table BOOKLETS_PAGES (
BOOKLETS_ID int(12) unsigned auto_increment NOT NULL,
BOOKLETS_PAGES_ID int(12) unsigned auto_increment NOT NULL,NAME varchar(255),DESCRIPTION text,STATUS int(1) unsigned,ORDERING int(12) unsigned,primary key(BOOKLETS_ID,BOOKLETS_PAGES_ID));
-- BOOKLETS_PAGES_ID,NAME,DESCRIPTION,STATUS,ORDERING
---------

----------------- joined SECTION_ALIGN_LANGS: -----------------
drop table if exists SECTION_ALIGN_LANGS;
create table SECTION_ALIGN_LANGS (
SECTION_ALIGN_ID int(12) unsigned auto_increment NOT NULL,
SECTION_ALIGN_LANGS_ID int(12) unsigned auto_increment NOT NULL,CMF_LANG_ID int(12) unsigned,IMAGE1 varchar(50),ALT varchar(255),DESCRIPTION text,primary key(SECTION_ALIGN_ID,SECTION_ALIGN_LANGS_ID));
-- SECTION_ALIGN_LANGS_ID,CMF_LANG_ID,IMAGE1,ALT,DESCRIPTION
---------
create  index idx_CALLBACK_2  on CALLBACK (CALLBACK_TIME_ID);
create  index idx_LEFT_BANNS_2  on LEFT_BANNS (LEFT_BANNS_GROUP_ID);
create  index idx_ARTICLE_1  on ARTICLE (DATA);
create  index idx_NEWS_1  on NEWS (DATA);
create  index idx_SERVICES_1  on SERVICES (DATA);
create  index idx_SPECIAL_OFFERS_1  on SPECIAL_OFFERS (DATA);
create  index idx_GALLERY_GROUP_1  on GALLERY_GROUP (PARENT_ID);
create  index idx_GALLERY_GROUP_VIDEO_1  on GALLERY_GROUP_VIDEO (PARENT_ID);
create  index idx_GALLERY_VIDEO_1  on GALLERY_VIDEO (GALLERY_GROUP_VIDEO_ID);
create  index idx_MESSAGES_1  on MESSAGES (USERS);
create  index idx_MESSAGES_CLIENTS_1  on MESSAGES_CLIENTS (MESSAGES_ID);
create  index idx_FILE_TYPES_1  on FILE_TYPES (NAME);
create  index idx_COUNTRY_1  on COUNTRY (NAME);
create  index idx_SCOPE_1  on SCOPE (NAME);
create  index idx_PRODUCT_TYPE_1  on PRODUCT_TYPE (NAME);
create  index idx_CLIENT_1  on CLIENT (NAME);
create  index idx_CLIENT_VOPROS_1  on CLIENT_VOPROS (USERS);
create  index idx_CLIENT_VOPROS_CLIENTS_1  on CLIENT_VOPROS_CLIENTS (CLIENT_VOPROS_ID);
create  index idx_ANNOUNCEMENT_2  on ANNOUNCEMENT (ANNOUNCEMENT_RUBRICS_ID);
create  index idx_ANNOUNCEMENT_1  on ANNOUNCEMENT (ANNOUNCEMENT_TYPES_ID);
create  index idx_CATALOGUE_1  on CATALOGUE (PARENT_ID);
create  index idx_ITEM_1  on ITEM (CATALOGUE_ID);
create  index idx_ANOTHER_PAGES_1  on ANOTHER_PAGES (PARENT_ID);
create  index idx_XMLS_1  on XMLS (CMF_LANG_ID);
create UNIQUE index idx_SETINGS_1  on SETINGS (SYSTEM_NAME);
create UNIQUE index idx_SEQUENCES_1  on SEQUENCES (NAME);
create  index idx_CMF_USER_1  on CMF_USER (MD5_);
create  index idx_CMF_USER_2  on CMF_USER (LOGIN);
create  index idx_CMF_SCRIPT_1  on CMF_SCRIPT (PARENT_ID);
create  index idx_CMF_SCRIPT_2  on CMF_SCRIPT (ARTICLE);
create  index idx_CMF_BUG_1  on CMF_BUG (DATA);
create  index idx_CMF_FIELDS_LIST_1  on CMF_FIELDS_LIST (CMF_FIELDS_ID,CMF_FIELDS_LIST_ID);
create UNIQUE index idx_CMF_LANG_1  on CMF_LANG (SYSTEM_NAME);
create UNIQUE index idx_CMF_ERRORS_1  on CMF_ERRORS (CMF_ERRORS_GROUP_ID,SYSTEM_NAME);
create  index idx_ZAKAZSTATUS_1  on ZAKAZSTATUS (ZAKAZSTATUS_ID);
create  index idx_ZAKAZ_2  on ZAKAZ (DATA);
create  index idx_PROJECTS_1  on PROJECTS (DATA);
create UNIQUE index idx_SOKOBAN_1  on SOKOBAN (LEVEL);
create  index idx_CALLBACK_TIME_LANG_1  on CALLBACK_TIME_LANG (CMF_LANG_ID);
create  index idx_HEADER_CMF_LANG_1  on HEADER_CMF_LANG (CMF_LANG_ID);
create  index idx_LEFT_BANNS_CMF_LANG_1  on LEFT_BANNS_CMF_LANG (CMF_LANG_ID);
create  index idx_ARTICLE_GROUP_LANGS_1  on ARTICLE_GROUP_LANGS (CMF_LANG_ID);
create  index idx_ARTICLE_LANGS_1  on ARTICLE_LANGS (CMF_LANG_ID);
create  index idx_NEWS_GROUP_LANGS_1  on NEWS_GROUP_LANGS (CMF_LANG_ID);
create  index idx_NEWS_LANGS_1  on NEWS_LANGS (CMF_LANG_ID);
create  index idx_SERVICES_LANGS_1  on SERVICES_LANGS (CMF_LANG_ID);
create  index idx_SPECIAL_OFFERS_LANGS_1  on SPECIAL_OFFERS_LANGS (CMF_LANG_ID);
create  index idx_GALLERY_GROUP_LANGS_1  on GALLERY_GROUP_LANGS (CMF_LANG_ID);
create  index idx_GALLERY_LANGS_1  on GALLERY_LANGS (CMF_LANG_ID);
create  index idx_GALLERY_GROUP_VIDEO_LANGS_1  on GALLERY_GROUP_VIDEO_LANGS (CMF_LANG_ID);
create  index idx_GALLERY_VIDEO_LANGS_1  on GALLERY_VIDEO_LANGS (CMF_LANG_ID);
create  index idx_VOPROS_LANGS_1  on VOPROS_LANGS (CMF_LANG_ID);
create  index idx_OTVETS_LANGS_1  on OTVETS_LANGS (CMF_LANG_ID);
create  index idx_CLIENT_PRODUCT_TYPE_1  on CLIENT_PRODUCT_TYPE (PRODUCT_TYPE_ID);
create  index idx_ANNOUNCEMENT_RUBRICS_LANGS_1  on ANNOUNCEMENT_RUBRICS_LANGS (CMF_LANG_ID);
create  index idx_ANNOUNCEMENT_TYPES_LANGS_1  on ANNOUNCEMENT_TYPES_LANGS (CMF_LANG_ID);
create  index idx_QUESTION_GROUP_LANGS_1  on QUESTION_GROUP_LANGS (CMF_LANG_ID);
create  index idx_QUESTION_LANGS_1  on QUESTION_LANGS (CMF_LANG_ID);
create  index idx_TEXTES_LANGS_1  on TEXTES_LANGS (CMF_LANG_ID);
create  index idx_SECTION_ALIGN_1  on SECTION_ALIGN (ALIGN_ID);
create  index idx_CATALOGUE_LANGS_1  on CATALOGUE_LANGS (CMF_LANG_ID);
create  index idx_CATALOGUE_ARTICLE_GROUP_1  on CATALOGUE_ARTICLE_GROUP (ARTICLE_GROUP_ID);
create  index idx_ITEM_LANGS_1  on ITEM_LANGS (CMF_LANG_ID);
create  index idx_ITEM_PHOTO_1  on ITEM_PHOTO (GALLERY_ID);
create  index idx_ITEM_ELEMENTS_LANGS_1  on ITEM_ELEMENTS_LANGS (CMF_LANG_ID);
create  index idx_ANOTHER_PAGES_LANGS_1  on ANOTHER_PAGES_LANGS (CMF_LANG_ID);
create  index idx_CMF_ERRORS_LANGS_1  on CMF_ERRORS_LANGS (CMF_LANG_ID);
create  index idx_PROJECTS_LANGS_1  on PROJECTS_LANGS (CMF_LANG_ID);
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (0,'ANOTHER_PAGES','ANOTHER_PAGES.php?e=ED&id=%id%&pid=%pid%&r=%r%');
-- 0 -- ANOTHER_PAGES
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (1,'NEWS','NEWS.php?e=ED&id=%id%&p=%p%');
-- 1 -- NEWS
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (10,'GALLERY_GROUP','GALLERY_GROUP.php?e=ED&id=%id%&pid=%pid%&r=%r%');
-- 10 -- GALLERY_GROUP
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (10,'GALLERY_GROUP_VIDEO','GALLERY_GROUP_VIDEO.php?e=ED&id=%id%&pid=%pid%&r=%r%');
-- 10 -- GALLERY_GROUP_VIDEO
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (11,'PROJECTS','PROJECTS.php?e=ED&id=%id%&p=%p%');
-- 11 -- PROJECTS
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (12,'CALCULATOR','CALCULATOR.php?e=ED&id=%id%');
-- 12 -- CALCULATOR
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (2,'CATALOGUE','CATALOGUE.php?e=ED&id=%id%&pid=%pid%&r=%r%');
-- 2 -- CATALOGUE
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (3,'CATALOGUE','ITEM.php?e=ED&id=%id%&pid=%pid%&p=%p%');
-- 3 -- ITEM
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (5,'ARTICLE','ARTICLE.php?e=ED&id=%id%&p=%p%');
-- 5 -- ARTICLE
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (6,'SPECIAL_OFFERS','SPECIAL_OFFERS.php?e=ED&id=%id%&p=%p%');
-- 6 -- SPECIAL_OFFERS
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (7,'ZAKAZ','ZAKAZ.php?e=ED&id=%id%&p=%p%');
-- 7 -- ZAKAZ
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (8,'CLIENT','CLIENT.php?e=ED&id=%id%');
-- 8 -- CLIENT
INSERT into CMF_XMLS_ARTICLE (TYPE,ARTICLE,EDIT) VALUES (9,'SERVICES','SERVICES.php?e=ED&id=%id%&p=%p%');
-- 9 -- SERVICES

INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (1,0,'INDEX','Главный скрипт','index.php','',NULL,NULL,0,1,1,1);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (2,1,'ADMIN','Админ','','',NULL,NULL,0,1,1,1);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (3,2,'SYSTEM','Системные функции','','','3.gif#28#26','#336699',0,1,1,1);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (4,2,'RIGHTS','Управление правами','','','4.gif#28#26','#70B1E4',0,1,1,1);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (5,3,'CMF_SCRIPT','Скрипты','CMF_SCRIPT.php','Управление Админским меню',NULL,NULL,0,1,1,2);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (6,3,'SEQUENCES','Последовательности','SEQUENCES.php','Аналог сиквенсов в Оракле',NULL,NULL,0,1,1,3);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (7,3,'MYSQLEDITOR','Mysql Editor','sql_edit.php','Редактор MySQL для ручного управления базой',NULL,NULL,0,1,1,4);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (20,3,'CMF_XMLS_ARTICLE','Типы для XML редактора','CMF_XMLS_ARTICLE.php','','','',0,1,1,5);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (24,3,'CMF_BUG','Отчет о багах','CMF_BUG.php','',NULL,NULL,0,1,1,6);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (25,3,'CMF_LANG','Языки CMF','CMF_LANG.php','',NULL,NULL,0,1,1,7);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (11,1,'fdg','РЕДАКТОР','','','','',0,1,1,3);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (12,11,'for','Сайт','','','12.gif#30#26','#339900',0,1,1,1);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (23,11,'for','Каталог','','','','#339900',0,1,1,1);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (13,12,'ANOTHER_PAGES','Страницы сайта','ANOTHER_PAGES.php','','','',0,1,1,1);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (14,4,'CMF_USER','Пользователи системы','CMF_USER.php','Удаление и добавление пользователей системы','','',0,1,1,1);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (15,4,'CMF_GROUP','Группы системы','CMF_GROUP.php','Удаление и добавление системных групп','','',0,1,1,2);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (16,23,'ATTRIBUT_GROUP','Группы атрибутов','ATTRIBUT_GROUP.php','Управление группами атрибутов каталога','','',0,1,1,3);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (19,12,'EDITER','Редактор XML','EDITER.php','','','',0,0,0,4);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (21,12,'NEWS','Новости','NEWS.php','Редактирование новостей','','',0,1,1,2);
INSERT INTO `CMF_SCRIPT` (`CMF_SCRIPT_ID`,`PARENT_ID`,`ARTICLE`,`NAME`,`URL`,`DESCRIPTION`,`IMAGE`,`BACKGROUND`,`TYPE`,`STATUS`,`REALSTATUS`,`ORDERING`) VALUES (22,4,'CMF_USER_RIGHTS','Ваши права','user_rights.php','Матрица ваших прав','','',0,1,1,3);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (1,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (1,2,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (2,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (3,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (4,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (5,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (6,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (7,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (11,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (11,2,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (12,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (12,2,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (13,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (13,2,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (14,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (15,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (16,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (16,2,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (19,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (20,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (21,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (21,2,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (22,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (22,2,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (23,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (23,2,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (24,1,1,1,1);
INSERT INTO `CMF_SCRIPT_GROUP` (`CMF_SCRIPT_ID`,`CMF_GROUP_ID`,`R`,`W`,`D`) VALUES (25,1,1,1,1);

INSERT INTO `CMF_USER` (`CMF_USER_ID`,`MD5_`,`NAME`,`LOGIN`,`PASS_`,`STATUS`,`URL`) VALUES (1,'246fee6c6775fbe7057e2fa971f90e6d','Главный администратор системы','admin','adm',1,'ANOTHER_PAGES.php');
INSERT INTO `CMF_USER` (`CMF_USER_ID`,`MD5_`,`NAME`,`LOGIN`,`PASS_`,`STATUS`,`URL`) VALUES (2,'E1CD90B205DFBA3B6508ABBD163468218140E980','Редактор','test','test',1,'ANOTHER_PAGES.php');
INSERT INTO `CMF_USER_GROUP` (`CMF_USER_ID`,`CMF_GROUP_ID`) VALUES (1,1);
INSERT INTO `CMF_USER_GROUP` (`CMF_USER_ID`,`CMF_GROUP_ID`) VALUES (2,2);
INSERT INTO `CMF_GROUP` (`CMF_GROUP_ID`,`NAME`) VALUES (1,'Админская группа');
INSERT INTO `CMF_GROUP` (`CMF_GROUP_ID`,`NAME`) VALUES (2,'Редакторы');
INSERT INTO `SEQUENCES` (`SEQUENCES_ID`,`NAME`,`ID`) VALUES (6,'CMF_SCRIPT',100);
INSERT INTO `CMF_LANG` (`CMF_LANG_ID`, `NAME`, `ORDERING`, `STATUS`) VALUES (1,'Russian',1,1);
--INSERT INTO `CMF_LANG` (`CMF_LANG_ID`, `NAME`, `ORDERING`, `STATUS`) VALUES (2,'English',2,1);
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('OLD_SEF_URL','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SEF_SITE_URL','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('TRANSLIT_RULE','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CALLBACK_TIME','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CALLBACK_TIME_LANG','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CALLBACK','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('COMPLAIN','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('HEADER','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('HEADER_CMF_LANG','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('LEFT_BANNS_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('LEFT_BANNS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('LEFT_BANNS_CMF_LANG','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ARTICLE_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ARTICLE_GROUP_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ARTICLE','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ARTICLE_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('NEWS_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('NEWS_GROUP_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('NEWS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('NEWS_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SERVICES','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SERVICES_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SPECIAL_OFFERS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SPECIAL_OFFERS_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('GALLERY_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('GALLERY_GROUP_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('GALLERY','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('GALLERY_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('GALLERY_GROUP_VIDEO','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('GALLERY_GROUP_VIDEO_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('GALLERY_VIDEO','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('GALLERY_VIDEO_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('VOPROS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('VOPROS_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('OTVETS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('OTVETS_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('OTVETS_COMMENT','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('MESSAGES','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('MESSAGES_CLIENTS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('FILE_TYPES','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('COUNTRY','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SCOPE','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('PRODUCT_TYPE','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CLIENT','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CLIENT_PRODUCT_TYPE','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CLIENT_VOPROS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CLIENT_VOPROS_CLIENTS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CLIENT_OTVETS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ANNOUNCEMENT_RUBRICS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ANNOUNCEMENT_RUBRICS_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ANNOUNCEMENT_TYPES','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ANNOUNCEMENT_TYPES_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ANNOUNCEMENT','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('QUESTION_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('QUESTION_GROUP_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('QUESTION','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('QUESTION_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('TEXTES','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('TEXTES_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ALIGN','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('BANN_SECTION','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SECTION_ALIGN','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CATALOGUE','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CATALOGUE_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CATALOGUE_ARTICLE_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ITEM','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ITEM_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ITEM_PHOTO','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ITEM_ELEMENTS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ITEM_ELEMENTS_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SITE_PARAM','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('MANAGERS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ANOTHER_PAGES','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ANOTHER_PAGES_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('XMLS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SETINGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SEQUENCES','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_USER','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_USER_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_SCRIPT','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_SCRIPT_USER','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_SCRIPT_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_XMLS_ARTICLE','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_BUG','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_FIELDS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_FIELDS_LIST','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_LANG','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_ERRORS_GROUP','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_ERRORS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CMF_ERRORS_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ZAKAZSTATUS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ZAKAZ','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('ZAKAZ_ITEM','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('PROJECTS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('PROJECTS_LANGS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('REFERER_PHONES','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('BOOKLETS','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('BOOKLETS_PAGES','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('SOKOBAN','0');
INSERT IGNORE INTO SEQUENCES (NAME,ID) VALUES ('CALCULATOR','0');


-- MultiLanguage tables (multilanguage='y') --
-- use vasilek;

-- /MultiLanguage tables (multilanguage='y') --

