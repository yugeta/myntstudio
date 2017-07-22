<?php

class MYNT_PAGE{

	// クエリを判別してページを表示（ない場合はエラーページ）
	function viewPage(){
		$file = $this->getPageData();
		$json = json_decode($file,true);
		$MYNT_SOURCE = new MYNT_SOURCE;
		return $MYNT_SOURCE->rep($json["source"]);
	}
	function getPageData(){
		$source = "";
		if(isset($_REQUEST["p"]) && $_REQUEST["p"] !== ""){
			if(is_file("data/page/p/".$_REQUEST["p"].".dat")){
				$source = file_get_contents("data/page/p/".$_REQUEST["p"].".dat");
			}
			else{
				$source = file_get_contents("data/page/p/404.dat");
			}
		}
		else if(isset($_REQUEST["s"]) && $_REQUEST["s"] !== ""){
			if(is_file("data/page/s/".$_REQUEST["s"].".dat")){
				$source = file_get_contents("data/page/s/".$_REQUEST["s"].".dat");
			}
			else{
				$source = file_get_contents("data/page/p/404.dat");
			}
		}
		else if(is_file("data/page/s/top.dat")){
			$source = file_get_contents("data/page/s/top.dat");
		}
		else{
			$source = file_get_contents("data/page/p/404.dat");
		}
		return $source;
	}

	function viewTitle(){
		// if(isset($_REQUEST["p"])){
		// 	$source = $this->getPageTitle($_REQUEST["p"]);
		// 	$source = str_replace("\n","",$source);
		// 	$source = str_replace("\r","",$source);
		// 	return $this->changePageSource($source);
		// }
		$file = $this->getPageData();
		$json = json_decode($file,true);
		$MYNT_SOURCE = new MYNT_SOURCE;
		return $MYNT_SOURCE->rep($json["title"]);
	}

	// function getPageTitle($pageID){
	// 	if(is_file("data/page/title/".$pageID.".dat")){
	// 		return file_get_contents("data/page/title/".$pageID.".dat");
	// 	}
	// 	else{
	//
	// 	}
	// }

	//
	// function changePageSource($source){
	// 	$sources = explode("\n",$source);
	// 	$new_source = "";
	// 	for($i=0; $i<count($sources); $i++){
	// 		$new_source .= $sources[$i];
	// 	}
	// 	return $new_source;
	// }
	// function changePageNewline($source){
	// 	$sources = explode("\n",$source);
	// 	$new_source = "";
	// 	for($i=0; $i<count($sources); $i++){
	// 		$new_source .= $sources[$i].PHP_EOL;
	// 	}
	// 	return $new_source;
	// }
	// function changePageLine($source){
	// 	$sources = explode("\n",$source);
	// 	$new_source = "";
	// 	for($i=0; $i<count($sources); $i++){
	// 		$new_source .= "<p>".$sources[$i]."</p>".PHP_EOL;
	// 	}
	// 	return $new_source;
	// }

}
