<?php

class SYSTEM_COMMON{
	public function viewContents(){
		$source = "";

		$path = $GLOBALS["config"]["page"]["contents_default"];
		//
		for($i=0,$c=count($GLOBALS["config"]["pageCategoryLists"]["type"]); $i<$c; $i++){

			$key  = $GLOBALS["config"]["pageCategoryLists"]["type"][$i]["key"];
			if(!isset($_REQUEST[$key])){continue;}
			if($key === "default" && $_REQUEST[$key] === "default"){continue;}

			$path = "data/page/default/".$key.".html";
			if(!is_file($path)){continue;}

			$source = file_get_contents($path);
			$source = self::conv($source);

			break;
		}

		// source is blank
		if($source === ""){
			$path = "data/page/default/index.html";
			$source = file_get_contents($path);
			$source = self::conv($source);
		}

		return $source;
	}
}
