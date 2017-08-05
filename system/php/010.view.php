<?php

class MYNT_VIEW{

	public function viewDesign($base){

		$path = "design/".$GLOBALS["config"]["design"]["target"]."/html/".$base.".html";

		// check
		if(!is_file($path)){
			$path = "design/".$GLOBALS["config"]["design"]["target"]."/html/"."/404.html";
		}

		$source = file_get_contents($path);

		if($source){
			echo $this->conv($source);
		}
		else{
			$this->viewError("Not Source");
		}
	}

	public function getSource($base = "",$page = ""){

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
				$path = "data/".$base."/".$page.".html";
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
