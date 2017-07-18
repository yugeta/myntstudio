<?php

/**
 * Mynt Studio
 */

date_default_timezone_set('Asia/Tokyo');

// //IE iframe 3rd party cookie 対応
// header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

require_once "system/php/000.mynt.php";
$MYNT = new MYNT;

$MYNT->setDefine();

$GLOBALS["config"] = $MYNT->loadConfig();
$MYNT->loadModulePHPs("system/php");

session_name($GLOBALS["config"]["define"]["session_name"]);
session_start();

$MYNT->loadPlugins();

$MYNT->viewDesign($MYNT->getScriptFileName() . ".html");
