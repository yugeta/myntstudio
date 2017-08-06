<?php

class MYNT_VIEW{

	public function viewDesign($base=""){
		if($base === ""){
			$base = $this->getBaseFile();
		}

		$default_design = $GLOBALS["config"]["design"]["target"];
		$path = "design/".$default_design."/html/".$base.".html";

		// check
		if(!is_file($path)){
			$path = "design/".$default_design."/html/"."/404.html";
		}

		$source = file_get_contents($path);
		echo $this->conv($source);
	}

	/**
	* Contents
	* 1. ? blog=** / default=** / system=** / etc=**
	* 2. ?b=**&p=** (data/page/base/page.html)
	*/
	public function getContents(){

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
				return $this->conv($source);
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

		return $this->getSource();
	}

	public function getBaseFile(){
		for($i=0,$c=count($GLOBALS["config"]["pageCategoryLists"]["type"]); $i<$c; $i++){
			$key = $GLOBALS["config"]["pageCategoryLists"]["type"][$i]["key"];
			// $dir = $GLOBALS["config"]["pageCategoryLists"]["type"][$i]["dir"];
			$baseFile = $GLOBALS["config"]["pageCategoryLists"]["type"][$i]["baseFile"];
			if(isset($_REQUEST[$key]) && $key
			&& is_file($dir.$_REQUEST[$key].".html")){
				return $baseFile;
			}
		}
		return (isset($_REQUEST["b"]) && $_REQUEST["b"]!=="")?$_REQUEST["b"]:$GLOBALS["config"]["page"]["base"];
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

		$MYNT_VIEW = new MYNT_VIEW;
		return $MYNT_VIEW->conv($source);
	}



	public function conv($source = ""){
		$MYNT_SOURCE = new MYNT_SOURCE;
		return $MYNT_SOURCE->rep($source);
	}

	public function viewError($msg){
		echo "<h1>".$msg."</h1>";
		exit();
	}

	public function root(){
		return $_SERVER['SCRIPT_NAME'];
	}
}
