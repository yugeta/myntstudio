<?php

date_default_timezone_set('Asia/Tokyo');

// //IE iframe 3rd party cookie 対応
// header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class MYNT{

	function setDefine(){
		define(DIR_DESIGN		,"design"	);
		define(DIR_PLUGIN		,"plugin"	);
		define(DIR_LIBRARY	,"library");
		define(DIR_SYSTEM		,"system");
		define(DIR_DATA			,"data"		);
	}

	// Config
	function loadConfig(){
		$GLOBALS["config"] = $this->getConfig();
	}
	function getConfig(){
		$dir = "data/config/";

		$files = scandir($dir);
		$data = array();

		for($i=0; $i<count($files); $i++){
			if($files[$i] == "." || $files[$i] == ".." || !preg_match("/\.json$/",$files[$i])){continue;}
			$key = str_replace(".json","",$files[$i]);
			$data[$key] = json_decode(file_get_contents($dir.$files[$i]),true);
		}
		return $data;
	}

	function loadModulePHPs($dir){
		if(!preg_match("/\/$/",$dir)){
			$dir .= "/";
		}

		$files = scandir($dir);

		for($i=0; $i<count($files); $i++){
			if($files[$i] == "." || $files[$i] == ".." || !preg_match("/\.php$/",$files[$i])){continue;}
			require_once $dir.$files[$i];
		}
	}

	function loadPlugins($dir = ""){
		$dir = $GLOBALS["config"]["define"]["plugin"];

		if(!$dir || !is_dir($dir)){
			$this->viewError("Not found directory [loadPlugins] [ ".$dir." ]");
		}

		if(!preg_match("/\/$/",$dir)){
			$dir .= "/";
		}

		if(!isset($GLOBALS["config"]["plugins"]) || !count($GLOBALS["config"]["plugins"])){
			return;
		}

		for($i=0; $i<count($GLOBALS["config"]["plugins"]); $i++){
			$path = $dir . $GLOBALS["config"]["plugins"][$i] ."/php/lib/";
			// echo $path."<br>".PHP_EOL;
			if(!is_dir($path)){continue;}
			$this->loadModulePHPs($path);
		}
	}

	function viewDesign($htmlFile="index.html"){
		if(!isset($GLOBALS["config"]["design"]["target"])){
			$this->viewError("Not setting Design.");
		}

		$design = $GLOBALS["config"]["design"]["target"];

		if(!$design || !is_dir("design/".$design)){
			$this->viewError("Not found Design [ ".$design." ]");
		}

		// Load - HTML
		if(isset($_REQUEST["h"]) && is_file("design/".$design."/".$_REQUEST["h"].".html")){
			$htmlFile = $_REQUEST["h"].".html";
		}
    $source = file_get_contents("design/".$design."/".$htmlFile);

		if($source){
			// $RepTag = new RepTag;
			// echo $RepTag->setSource($source);
			$MYNT_SOURCE = new MYNT_SOURCE;
			echo $MYNT_SOURCE->rep($source);
		}
		else{
			$this->viewError("Not Source");
		}
  }

	function checkInit(){
		if(!is_dir("./design")){
			$this->viewError("Not found directory [ design/ ]");
		}
		if(!is_dir("./library")){
			$this->viewError("Not found directory [ library/ ]");
		}
		if(!is_dir("./plugin")){
			$this->viewError("Not found directory [ plugin/ ]");
		}
		if(!is_dir("./system")){
			$this->viewError("Not found directory [ system/ ]");
		}
	}

	function getScriptFileName(){
		$baseName = $_SERVER['SCRIPT_FILENAME'];
		$sp1 = explode("/" , $baseName);
		$sp2 = explode(".php" , $sp1[(count($sp1)-1)]);
		return $sp2[0];
	}

	function viewError($msg){
		echo "<h1>".$msg."</h1>";
		exit();
	}

	// ローカルパス [ design/***/ ]
	function getDesignTarget(){
		return "design/".$GLOBALS["config"]["design"]["target"]."/";
	}

	function currentTime(){
		return date("YmdHis");
	}

}
