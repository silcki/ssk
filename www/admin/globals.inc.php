<?php
date_default_timezone_set('Europe/Kiev');

ini_set("display_errors",1);
ini_set("display_startup_errors",1);
error_reporting(E_ALL &~E_DEPRECATED);

@setlocale(LC_ALL, 'ru_RU.UTF8');
@setlocale(LC_NUMERIC,"en_US");

Define("Hostname",      "localhost");
Define("DBName",        "ssk");
Define("Username",      "root");
Define("Password",      "");




     //"fonts/FEEDBI__.TTF",
                   //"fonts/frizzed.ttf",
                   //"fonts/hrom.ttf",

$V_G_FONTS = array(
           "fonts/FEEDBI__.TTF",
                   "fonts/frizzed.ttf",
                 );