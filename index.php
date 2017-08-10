<?php
/**
 * Mynt Studio
 * Auther @ Yugeta Koji (MYNT Inc.)
 * WebSiteFrameWork (WSFW)
 */

date_default_timezone_set('Asia/Tokyo');
// //IE iframe 3rd party cookie 対応
// header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');


// Load-PHP-system-module
MYNT::loadPHPs("system/php");

// Load-Config
MYNT::loadConfig();

// Session-Start
MYNT::startSession();

// Load-PHP-Plugins-module
MYNT::loadPlugins();

// Load-PHP-design-module
MYNT::loadPHPs("design/".$GLOBALS["config"]["design"]["target"]."/php");

// Check-Query (system-process)
MYNT::checkMethod();

// Check-Auth
MYNT_PLUGIN_LOGIN::checkSystemBase();

// Load-HTML-Default-source
MYNT::viewTemplate();






class MYNT{

	// Load-PHP-Module
	public static function loadPHPs($dir=""){

    // Check
		if($dir==="" || !is_dir($dir)){return;}

    // Check-Directory-last-string
		if(!preg_match("/\/$/",$dir)){
			$dir .= "/";
		}

    // Load-Directory-inner-files
		$files = scandir($dir);
		for($i=0; $i<count($files); $i++){
			if($files[$i] == "." || $files[$i] == ".." || !preg_match("/\.php$/",$files[$i])){continue;}
			require_once $dir.$files[$i];
		}
	}

	// Load-Config
	public static function loadConfig(){
		// system-config
		$GLOBALS["config"] = self::getSystemConfig("data/config/");

		// plugin-config
		$GLOBALS["plugin"] = self::getPluginConfig("plugin/");
	}

  // Start-Session
  public static function startSession(){
    session_name($GLOBALS["config"]["define"]["session_name"]);
    session_start();
  }

  // Load-System-Configs [data/config]
  public static function getSystemConfig($dir){
		$files = scandir($dir);
		$data = array();
    // Check-config-data
		for($i=0; $i<count($files); $i++){
			if($files[$i] == "." || $files[$i] == ".." || !preg_match("/(.+?)\.json$/", $files[$i], $match)){continue;}
			$data[$match[1]] = json_decode(file_get_contents($dir.$files[$i]),true);
		}
		return $data;
	}

  // Load-Plugin-Configs [plugin/***/config/default.conf]
  public static function getPluginConfig($dir){
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

  // Load-Plugins
  public static function loadPlugins($dir = "plugin"){

    // Check-Config-Data
		if(!isset($GLOBALS["config"]["plugin"])
    || !isset($GLOBALS["config"]["plugin"]["target"])
    || !count($GLOBALS["config"]["plugin"]["target"])){return;}

    // Check-Directory-exists
		if(!$dir || !is_dir($dir)){
			self::viewError("Not found directory [loadPlugins] [ ".$dir." ]");
		}

    // Check-Directory-last-string
    if(!preg_match("/\/$/",$dir)){
			$dir .= "/";
		}

    // flg
		$plugins = $GLOBALS["config"]["plugin"]["target"];

    // Load-Plugin-PHP-modules
		for($i=0; $i<count($plugins); $i++){
			$path = $dir . $plugins[$i] ."/php";
			if(!is_dir($path)){continue;}
			self::loadPHPs($path);
		}
	}

  // View-Error
  public static function viewError($msg){
		echo "<h1>".$msg."</h1>";
		exit();
	}

  // View-Base (query-check -> get-base)
	public static function viewTemplate($templatePath=""){
		if($templatePath === ""){
			$templatePath = self::getTemplatePath();
		}

		// $default_design = $GLOBALS["config"]["design"]["target"];

		// $path = "design/".$default_design."/html/".$base;

		// check
		if(!is_file($templatePath)){
			$templatePath = "design/".$default_design."/html/"."/404.html";
		}

		$source = file_get_contents($templatePath);
		echo self::conv($source);
	}

  // get-base-file-name
  public static function getTemplatePath(){

		// default
		$base = "design/".$GLOBALS["config"]["design"]["target"]."/html/".$GLOBALS["config"]["page"]["base"];

		// 強制モード (templateFile=)
		if(isset($_REQUEST["templateFile"]) && is_file("design/".$GLOBALS["config"]["design"]["target"]."/html/".$_REQUEST["templateFile"].".html")){
			$base = "design/".$GLOBALS["config"]["design"]["target"]."/html/".$_REQUEST["templateFile"].".html";
		}

		// 通常モード
		else{
			$type = $GLOBALS["config"]["pageCategoryLists"]["type"];

			for($i=0,$c=count($type); $i<$c; $i++){
				$key = $type[$i]["key"];
				// $dir = $type[$i]["dir"];
				$baseFile = $type[$i]["baseFile"];
				$path = "design/".$GLOBALS["config"]["design"]["target"]."/html/".$baseFile;
				if(isset($_REQUEST[$key]) && is_file($path)){
					$base = $path;
					break;
				}
			}
		}

		return $base;
	}

  // Convert-HTML-Source
  public static function conv($source = ""){
		// $MYNT_SOURCE = new MYNT_SOURCE;
		return MYNT_SOURCE::rep($source);
    // echo $source;
	}

  /**
	* Contents
	* 1. ? blog=** / default=** / system=** / etc=**
	* 2. ?b=**&p=** (data/page/base/page.html)
	*/
	public function viewContents(){
		$source = "";

		// 強制モード (contentsPath=)
		if(isset($_REQUEST["contentsPath"]) && is_file($_REQUEST["contentsPath"])){
			$source = file_get_contents($_REQUEST["contentsPath"]);
			$source = self::conv($source);
		}

		else{
			$path = $GLOBALS["config"]["page"]["contents_default"];
			$type = $GLOBALS["config"]["pageCategoryLists"]["type"];

			//
			for($i=0,$c=count($type); $i<$c; $i++){

				$key = $type[$i]["key"];
				$dir = $type[$i]["dir"];

				if(!isset($_REQUEST[$key])){continue;}
				// if($key === "default" && $_REQUEST[$key] === "default"){continue;}
				//if($_REQUEST[$key] === $key){continue;}

				// $file = $dir.$key.".html";
				$file = $dir.$_REQUEST[$key].".html";
				if(!is_file($file)){continue;}

				$source = file_get_contents($file);
				$source = self::conv($source);

				break;
			}

		}

		// source is blank
		if($source === ""){
			$path = "data/page/default/index.html";
			$source = file_get_contents($path);
			$source = self::conv($source);
		}

		return $source;
	}

  // query -> getContents-path
  public function getContents($path = "data/default/"){

  }

  public function getSource($base="" , $page=""){

		$base = (isset($_REQUEST["b"]))?$_REQUEST["b"]:"";
		$page = (isset($_REQUEST["p"]))?$_REQUEST["p"]:"";

		// path-get
		$path = "";
		if($base === "" && $page === ""){
			$path = $GLOBALS["config"]["page"]["contents_default"];
		}
		else if($base === "" && $page !== ""){
			$path = "data/page/blog/".$page.".html";
		}
		else if($base !== "" && $page !== ""){
			if($base === "system"){
				$path = "system/html/".$page.".html";
			}
			else{
				$path = "data/page/".$base."/".$page.".html";
			}
		}


		if($path === "" || !is_file($path)){
			$path = "data/default/404.html";
		}

		$source = file_get_contents($path);

		return self::conv($source);
	}


	// public function getScriptFileName(){
  //
	// 	// base
	// 	$mode = (isset($_REQUEST["b"]) && $_REQUEST["b"]!=="")?$_REQUEST["b"] : "index";
  //
	// 	return $mode;
	// }
  //
	// function setDefine(){
	// 	define(DIR_DESIGN		,"design"	);
	// 	define(DIR_PLUGIN		,"plugin"	);
	// 	define(DIR_LIBRARY	,"library");
	// 	define(DIR_SYSTEM		,"system");
	// 	define(DIR_DATA			,"data"		);
	// }

  // Check-Mode ------

  // // Check Query -> set config data($GLOBALS["set"])
  // public static function checkQuery(){
  //   $GLOBALS["set"] = array();
  //
  //   // getTemplate
  //   $GLOBALS["set"]["base"] = self::getBase();
  //
  //   //
  // }

  // Check Method
	public function checkMethod(){

		// method [ class / function ] *POST only
		if(isset($_POST["method"]) && count(explode("/",$_POST["method"])) === 2){
			$sp = explode("/",$_POST["method"]);
			if(method_exists($sp[0],$sp[1])){
				$cls = new $sp[0];
				call_user_func_array(array($cls , $sp[1]),array());
			}
		}

		// mode
		else if(isset($_REQUEST["mode"]) && $_REQUEST["mode"]){
			self::checkMode($_REQUEST["mode"]);
		}
	}

	// Check Mode
	public function checkMode($mode){
		switch($mode){
			case "login":
				if(class_exists("MYNT_PLUGIN_LOGIN")){
					MYNT_PLUGIN_LOGIN::viewLogin();
				}
				break;
			case "logout":
				if(class_exists("MYNT_PLUGIN_LOGIN")){
					MYNT_PLUGIN_LOGIN::checkLogout();
				}
				break;
			case "add_account":
				if(class_exists("MYNT_PLUGIN_LOGIN")){
					MYNT_PLUGIN_LOGIN::checkAccountAdd();
				}
				break;

		}
	}







  //
	// function checkInit(){
	// 	if(!is_dir("./design")){
	// 		$this->viewError("Not found directory [ design/ ]");
	// 	}
	// 	if(!is_dir("./library")){
	// 		$this->viewError("Not found directory [ library/ ]");
	// 	}
	// 	if(!is_dir("./plugin")){
	// 		$this->viewError("Not found directory [ plugin/ ]");
	// 	}
	// 	if(!is_dir("./system")){
	// 		$this->viewError("Not found directory [ system/ ]");
	// 	}
	// }
  //
  //
  //
  //
  //
	// // ローカルパス [ design/***/ ]
	// function getDesignTarget(){
	// 	return "design/".$GLOBALS["config"]["design"]["target"]."/";
	// }
  //

  // browser-cache-guard
	function currentTime(){
		return date("YmdHis");
	}


}
