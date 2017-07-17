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

// class MYNT{
//   function start(){
//     // $this->checkValidation();
//     // $this->loadSystemConfigs("system/config");
//     $this->loadModulePHPs("system/php");
//
// 		$MYNT_LIB = new MYNT_LIB;
//     $MYNT_LIB->loadPlugins();
//     $MYNT_LIB->viewDesign();
//   }
//
//   function loadModulePHPs($dir){
//
//     if(!preg_match("/\/$/",$dir)){
//         $dir .= "/";
//     }
//
//     $files = scandir($dir);
//
//     for($i=0; $i<count($files); $i++){
//         if($files[$i] == "." || $files[$i] == ".." || !preg_match("/\.php$/",$files[$i])){continue;}
//         require_once $dir.$files[$i];
//     }
//
//   }
//
// }
//
// $MYNT = new MYNT;
// $MYNT->start();

// echo $_SERVER['SCRIPT_FILENAME'];
