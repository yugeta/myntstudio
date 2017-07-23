<?php

/**
 * Mynt Studio
 */

require_once "system/php/000.mynt.php";
$MYNT = new MYNT;

$MYNT->loadModulePHPs("system/php");
$MYNT->loadConfig();

session_name($GLOBALS["config"]["define"]["session_name"]);
session_start();

$MYNT->loadPlugins();

// Mode-check
$MYNT_MODE = new MYNT_MODE;
$MYNT_MODE->checkQuery();

$MYNT->viewDesign($MYNT->getScriptFileName() . ".html");
