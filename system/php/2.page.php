<?php

class PAGE{

	// クエリを判別してページを表示（ない場合はエラーページ）
	function viewPage(){
		$source = "";
		if(isset($_REQUEST["p"])){
			$source = $this->getPageSource($_REQUEST["p"]);
		}
		else{
			$source = $this->getPageSource("top");
		}
		return $this->changePageLine($source);
	}

	function viewTitle(){
		if(isset($_REQUEST["p"])){
			$source = $this->getPageTitle($_REQUEST["p"]);
			$source = str_replace("\n","",$source);
			$source = str_replace("\r","",$source);
			return $this->changePageSource($source);
		}
	}

	//
	function getPageSource($pageID){
		$source = "";
		if(is_file("data/page/source/".$pageID.".dat")){
			$source = file_get_contents("data/page/source/".$pageID.".dat");
		}
		else{

		}
		$RepTag = new RepTag;
		return $RepTag->setSource($source);
	}
	function getPageTitle($pageID){
		if(is_file("data/page/title/".$pageID.".dat")){
			return file_get_contents("data/page/title/".$pageID.".dat");
		}
		else{

		}
	}

	// 
	function changePageSource($source){
		$sources = explode("\n",$source);
		$new_source = "";
		for($i=0; $i<count($sources); $i++){
			$new_source .= $sources[$i];
		}
		return $new_source;
	}
	function changePageNewline($source){
		$sources = explode("\n",$source);
		$new_source = "";
		for($i=0; $i<count($sources); $i++){
			$new_source .= $sources[$i]."<br>".PHP_EOL;
		}
		return $new_source;
	}
	function changePageLine($source){
		$sources = explode("\n",$source);
		$new_source = "";
		for($i=0; $i<count($sources); $i++){
			$new_source .= "<p>".$sources[$i]."</p>".PHP_EOL;
		}
		return $new_source;
	}

}
