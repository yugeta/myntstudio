<?php

/**
 * Mynt Studio
 */

require_once "system/php/000.mynt.php";
$MYNT = new MYNT;

// init-proc

// system-module
$MYNT->loadModulePHPs("system/php");

//config
$MYNT->loadConfig();

session_name($GLOBALS["config"]["define"]["session_name"]);
session_start();

// plugins-module
$MYNT->loadPlugins();

// design-module
$MYNT->loadModulePHPs("design/".$GLOBALS["config"]["design"]["target"]."/php");


// Mode-check
$MYNT_MODE = new MYNT_MODE;
$MYNT_MODE->checkQuery();

// Auth-check
if(class_exists("MYNT_PLUGIN_LOGIN")){
  $MYNT_PLUGIN_LOGIN = new MYNT_PLUGIN_LOGIN;
  $MYNT_PLUGIN_LOGIN->checkSystemBase();
}


$base = (isset($_REQUEST["b"]) && $_REQUEST["b"]!=="")?$_REQUEST["b"]:$GLOBALS["config"]["page"]["base"];

// Default-source-load
$MYNT_VIEW = new MYNT_VIEW;
$MYNT_VIEW->viewDesign($base);
