<?php

/**
 * Mynt Studio
 */


date_default_timezone_set('Asia/Tokyo');

// //IE iframe 3rd party cookie 対応
// header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

require_once "system/php/000.mynt.php";
$MYNT = new MYNT;

$MYNT->loadModulePHPs("system/php");

// $MYNT->setDefine();
$MYNT->loadConfig();

session_name($GLOBALS["config"]["define"]["session_name"]);
session_start();

$MYNT->loadPlugins();

// Mode-check
$MYNT_MODE = new MYNT_MODE;
$MYNT_MODE->checkQuery();

$MYNT->viewDesign($MYNT->getScriptFileName() . ".html");
