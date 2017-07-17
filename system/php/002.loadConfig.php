<?php


class LoadConfig{
	function __construct(){
		$dir = "system/config";

		// if(!is_dir($dir)){
		// 	$this->viewError("Not found directory : loadConfig [ ".$dir." ]");
		// }

		if(!preg_match("/\/$/",$dir)){
			$dir .= "/";
		}

		$files = scandir($dir);

		for($i=0; $i<count($files); $i++){
			if($files[$i] == "." || $files[$i] == ".." || !preg_match("/\.json$/",$files[$i])){continue;}
			$key = str_replace(".json","",$files[$i]);
			$GLOBALS["config"][$key] = json_decode(file_get_contents($dir.$files[$i]),true);
		}
	}
}

new LoadConfig;
