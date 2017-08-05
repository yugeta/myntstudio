<?php

/**
 * Mynt Studio
 */

require_once "system/php/000.mynt.php";
$MYNT = new MYNT;

// init-proc

$MYNT->loadModulePHPs("system/php");
$MYNT->loadConfig();

session_name($GLOBALS["config"]["define"]["session_name"]);
session_start();

$MYNT->loadPlugins();
$MYNT->loadModulePHPs("design/".$GLOBALS["config"]["design"]["target"]."/php");


// Mode-check
$MYNT_MODE = new MYNT_MODE;
$MYNT_MODE->checkQuery();


$base = (isset($_REQUEST["b"]) && $_REQUEST["b"]!=="")?$_REQUEST["b"]:$GLOBALS["config"]["page"]["base"];

// Default-source-load
$MYNT_VIEW = new MYNT_VIEW;
$MYNT_VIEW->viewDesign($base);
