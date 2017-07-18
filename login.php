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
$MYNT->loadPlugins();

$MYNT_LOGIN = new MYNT_LOGIN;
$MYNT_LOGIN->setLogin();
// if(isset($_REQUEST["mode"]) && $_REQUEST["mode"]==="login"){
//
// 	// echo $_REQUEST["login_id"]." / ".$_REQUEST["login_pw"];
// 	$flgLogin = $MYNT_LOGIN->checkLogin($_REQUEST["login_id"] , $_REQUEST["login_pw"]);
// 	// echo $_REQUEST["login_pw"]."/".$flgLogin."/".md5($_REQUEST["login_pw"]);
// 	// exit();
// }
// else if($auth){
// 	echo "session_id : ".$_SESSION["login_id"];
// }
//
// // view login-form
// else{
// 	$MYNT->viewDesign($MYNT->getScriptFileName() . ".html");
// }

// if($MYNT_LOGIN->setLogin()){
// 	echo "/session_id : ".$_SESSION["login_id"];
// }
// else{
// 	$MYNT->viewDesign($MYNT->getScriptFileName() . ".html");
// }

$MYNT->viewDesign($MYNT->getScriptFileName() . ".html");
