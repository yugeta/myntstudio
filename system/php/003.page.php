<?php

class MYNT_PAGE{

	// クエリを判別してページを表示（ない場合はエラーページ）
	function viewPage(){
		$source = "";
		if(isset($_REQUEST["p"])){
			$source = $this->getPageSource("data/page/source/".$_REQUEST["p"].".dat");
		}
		else{
			$source = $this->getPageSource("data/page/source/top.dat");
		}
		return $this->changePageNewLine($source);
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
	function getPageSource($target){
		$source = "";
		if(is_file($target)){
			$source = file_get_contents($target);
		}
		else{

		}
		// $RepTag = new RepTag;
		// return $RepTag->setSource($source);
		$MYNT_SOURCE = new MYNT_SOURCE;
		return $MYNT_SOURCE->rep($source);
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
			$new_source .= $sources[$i].PHP_EOL;
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
