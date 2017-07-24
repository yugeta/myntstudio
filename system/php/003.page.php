<?php

class MYNT_PAGE{

	public $default_dir  = "data/page/";
	public $default_file = "top";
	public $default_404  = "404";

	// クエリを判別してページを表示（ない場合はエラーページ）
	function getSource($file = ""){
		if(isset($_REQUEST["p"]) && $_REQUEST["p"]){
			$file = $_REQUEST["p"];
		}
		else if($file === ""){
			$file = $this->default_file;
		}

		$path = $this->default_dir;
		if(is_file($path.$file.".html")){
			$path .= $file.".html";
		}
		else{
			$path .= $this->default_404.".html";
		}

		$source = file_get_contents($path);

		$MYNT_SOURCE = new MYNT_SOURCE;
		return $MYNT_SOURCE->rep($source);
	}

	// infoデータからタイトルを取得
	function getTitle($file = ""){
		$info = $this->getPageInfo($file);

		if(!isset($info["title"])){
			return "";
		}

		$MYNT_SOURCE = new MYNT_SOURCE;
		return $MYNT_SOURCE->rep($info["title"]);
	}

	function getPageInfo($file = ""){
		if(isset($_REQUEST["p"]) && $_REQUEST["p"]){
			$file = $_REQUEST["p"];
		}
		else if($file === ""){
			$file = $this->default_file;
		}

		$path = $this->default_dir;
		if(is_file($path.$file.".info")){
			$path .= $file.".info";
		}
		else{
			$path .= $this->default_404.".info";
		}

		$source = file_get_contents($path);

		return json_decode($source , true);
	}
	//
	// function getPageDat($root = "html-info/top"){
	// 	$source = "";
	//
	// 	$err404 = "data/page/html-info/404.dat";
	//
	// 	if(isset($_REQUEST["s"]) && $_REQUEST["s"] !== ""){
	// 		$path = "data/page/s/".$_REQUEST["s"].".html";
	// 		if(is_file($path)){
	// 			$source = file_get_contents($path);
	// 		}
	// 		else{
	// 			$source = file_get_contents($err404);
	// 		}
	// 	}
	// 	else if(isset($_REQUEST["html"]) && $_REQUEST["html"] !== ""){
	// 		$path = "data/page/html/".$_REQUEST["html"].".html";
	// 		if(is_file($path)){
	// 			// $source = $this->getJsonData("",file_get_contents("data/page/html/".$_REQUEST["html"].".html"));
	// 			$source = file_get_contents($path);
	// 		}
	// 		else{
	// 			$source = file_get_contents($err404);
	// 		}
	// 	}
	// 	else{
	// 		$path = "data/page/".$root.".html";
	// 		if(is_file($path)){
	// 			$source = file_get_contents($path);
	// 		}
	// 		else{
	// 			$source = file_get_contents($err404);
	// 		}
	// 	}
	//
	// 	return $source;
	// }

	// function getJsonData($title,$source){
	// 	$jsonArr = array("title"=>$title , "source"=>$source);
	// 	$jsonStr = json_encode($jsonArr);
	// 	$jsonStr = preg_replace_callback('/\\\\u([0-9a-zA-Z]{4})/', function ($matches) {return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');},$jsonStr);
	// 	return $jsonStr;
	// }



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

	//
	public function getFile($filePath){
		if(!is_file($filePath)){return;}
		$data = file_get_contents($filePath);
		$data = str_replace("<","&lt;",$data);
		$data = str_replace(">","&gt;",$data);
		return $data;
	}

	public function getFileLists($type){
		if(!$type){return;}

		$path = "data/page/".$type."/";
		if(!is_dir($path)){return;}

		$lists = array();
		$files = scandir($path);
		for($i=0,$c=count($files); $i<$c; $i++){
			if($files[$i]==="." || $files[$i]===".."){continue;}
			// if(!preg_match("/\.dat$/",$files[$i])){continue;}
			$lists[] = $files[$i];
		}
		// print_r($lists);
		return $lists;
	}
	public function getFileListsOptions($type){
		if(!$type){return;}

		$files = $this->getFileLists($type);

		$options = array();
		for($i=0,$c=count($files); $i<$c; $i++){
			preg_match("/(.+?)\.(.+?)/",$files[$i] , $match);
			$options[] = "<option value='".$match[1]."'>".$match[1]."</option>".PHP_EOL;
		}
		// print_r($options);
		return join("",$options);
	}

	// page-data-save
	public function setSystemPage(){
		// die("saveing");
		// die($_REQUEST["source"]);
		// die($_REQUEST["file"]." | ".$_REQUEST["type"]);

		// file^path
		$path = "data/page/".$_REQUEST["type"]."/".$_REQUEST["file"].".html";
		$backupDir = "data/backup/page/".$_REQUEST["type"]."/";

		// backup-folder
		if(!is_dir($backupDir)){
			mkdir($backupDir , 0777 , true);
		}

		// backup
		if(is_file($path)){
			rename($path , $backupDir.$_REQUEST["file"].".html.".date(Ymdhis));
		}

		// source-save
		file_put_contents($path , $_REQUEST["source"]);

		//redirect
		$url = new MYNT_URL;
		header("Location: ". $url->getUrl()."?html=".$_REQUEST["html"]."&file=".$_REQUEST["file"]."&type=".$_REQUEST["type"]);

	}
}