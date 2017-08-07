<?php

date_default_timezone_set('Asia/Tokyo');

// //IE iframe 3rd party cookie 対応
// header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class MYNT{

	function getScriptFileName(){

		// base
		$mode = (isset($_REQUEST["b"]) && $_REQUEST["b"]!=="")?$_REQUEST["b"] : "index";

		return $mode;
	}

	function setDefine(){
		define(DIR_DESIGN		,"design"	);
		define(DIR_PLUGIN		,"plugin"	);
		define(DIR_LIBRARY	,"library");
		define(DIR_SYSTEM		,"system");
		define(DIR_DATA			,"data"		);
	}

	// Config
	function loadConfig(){

		// system-config
		$GLOBALS["config"] = $this->getSystemConfig("data/config/");

		// plugin-config
		$GLOBALS["plugin"] = $this->getPluginConfig("plugin/");
	}
	function getSystemConfig($dir = "data/config/"){
		$files = scandir($dir);
		$data = array();

		for($i=0; $i<count($files); $i++){
			if($files[$i] == "." || $files[$i] == ".." || !preg_match("/(.+?)\.json$/", $files[$i], $match)){continue;}
			$data[$match[1]] = json_decode(file_get_contents($dir.$files[$i]),true);
		}
		return $data;
	}
	function getPluginConfig($dir = "plugin/"){
		$data = array();
		// search-plugins
		for($i=0,$c=count($GLOBALS["config"]["plugin"]["target"]); $i<$c; $i++){
			$pluginName = $GLOBALS["config"]["plugin"]["target"][$i];
			if(!is_dir($dir.$pluginName)){continue;}
			if(!is_file($dir.$pluginName."/config/default.json")){continue;}
			$data[$pluginName] = json_decode(file_get_contents($dir.$pluginName."/config/default.json"),true);
		}
		return $data;
	}

	function loadModulePHPs($dir){
		if(!is_dir($dir)){return;}

		if(!preg_match("/\/$/",$dir)){
			$dir .= "/";
		}

		$files = scandir($dir);

		for($i=0; $i<count($files); $i++){
			if($files[$i] == "." || $files[$i] == ".." || !preg_match("/\.php$/",$files[$i])){continue;}
			require_once $dir.$files[$i];
		}
	}

	function loadPlugins($dir = "plugin"){

		if(!isset($GLOBALS["config"]["plugin"]) || !isset($GLOBALS["config"]["plugin"]["target"]) || !count($GLOBALS["config"]["plugin"]["target"])){
			return;
		}

		$plugins = $GLOBALS["config"]["plugin"]["target"];

		if(!$dir || !is_dir($dir)){
			$this->viewError("Not found directory [loadPlugins] [ ".$dir." ]");
		}

		if(!preg_match("/\/$/",$dir)){
			$dir .= "/";
		}

		for($i=0; $i<count($plugins); $i++){
			$path = $dir . $plugins[$i] ."/php";
			if(!is_dir($path)){continue;}
			$this->loadModulePHPs($path);
		}
	}

	function viewDesign($htmlFile = "index"){

		$path = "";

		// check
		if(is_file("design/".$GLOBALS["config"]["design"]["target"]."/html/".$htmlFile.".html")){
			$path = "design/".$GLOBALS["config"]["design"]["target"]."/html/".$htmlFile.".html";
		}
		else{
			$path = "design/".$GLOBALS["config"]["design"]["target"]."/html/"."/404.html";
		}

		$source = file_get_contents($path);

		if($source){

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
