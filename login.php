<?php

/**
 * Mynt Studio
 */

date_default_timezone_set('Asia/Tokyo');

require_once "system/php/000.mynt.php";
$MYNT = new MYNT;

$MYNT->setDefine();
$MYNT->loadModulePHPs("system/php");
$MYNT->loadPlugins();
$MYNT->viewDesign($MYNT->getScriptFileName() . ".html");
