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

//$MYNT->viewDesign($MYNT->getScriptFileName() . ".html");
$data = file_get_contents("design/sample/test.html");
$MYNT_SOURCE = new MYNT_SOURCE;
echo $MYNT_SOURCE->rep($data);



class TEST{
	function hoge(){
		return "hoge-hoge";
	}
	function hero($a){
		return "hero-hero-".$a;
	}
}

function aaa($b=""){
	return "func-aaa-".$b;
}
