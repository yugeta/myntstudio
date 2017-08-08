<?php

class MYNT_VIEW{



	public static function getTitle($file = ""){
		// $MYNT_PAGE = new MYNT_PAGE;
		$info = MYNT_PAGE::getPageInfo($file);

		if(!isset($info["title"])){
			return "";
		}

		// $MYNT_SOURCE = new MYNT_SOURCE;
		return MYN::conv($info["title"]);
	}

	/**
	* Contents
	* 1. ? blog=** / default=** / system=** / etc=**
	* 2. ?b=**&p=** (data/page/base/page.html)
	*/
	public static function getContents(){

		$path = $GLOBALS["config"]["page"]["contents_default"];

		// mode=1
		for($i=0,$c=count($GLOBALS["config"]["pageCategoryLists"]["type"]); $i<$c; $i++){
			$key = $GLOBALS["config"]["pageCategoryLists"]["type"][$i]["key"];
			$dir = $GLOBALS["config"]["pageCategoryLists"]["type"][$i]["dir"];
			// $baseFile = $GLOBALS["config"]["pageCategoryLists"]["type"][$i]["baseFile"];
			if(isset($_REQUEST[$key]) && $key
			&& is_file($dir.$_REQUEST[$key].".html")){
				// return $dir.$_REQUEST[$key].".html";
				$source = file_get_contents($dir.$_REQUEST[$key].".html");
				return MYNT::conv($source);
			}
		}

		// // mode=2
		// $base = (isset($_REQUEST["b"]))?$_REQUEST["b"]:"";
		// $page = (isset($_REQUEST["p"]))?$_REQUEST["p"]:"";
		//
		// // path-get
		// $path = "";
		// if($base === "" && $page === ""){
		// 	$path = $GLOBALS["config"]["page"]["contents_default"];
		// }
		// else if($base === "" && $page !== ""){
		// 	$path = "data/page/blog/".$page.".html";
		// }
		// else if($base !== "" && $page !== ""){
		// 	if($base === "system"){
		// 		$path = "system/html/".$page.".html";
		// 	}
		// 	else{
		// 		$path = "data/page/".$base."/".$page.".html";
		// 	}
		// }
		//
		//
		// if($path === "" || !is_file($path)){
		// 	$path = "data/default/404.html";
		// }
		//
		// $source = file_get_contents($path);
		//
		// $MYNT_VIEW = new MYNT_VIEW;
		// return $MYNT_VIEW->conv($source);

		return self::getSource();
	}



	public static function getSource($base="" , $page=""){

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

		// $MYNT_VIEW = new MYNT_VIEW;
		return MYNT::conv($source);
	}





	public static function viewError($msg){
		echo "<h1>".$msg."</h1>";
		exit();
	}

	public static function root(){
		return $_SERVER['SCRIPT_NAME'];
	}
}
