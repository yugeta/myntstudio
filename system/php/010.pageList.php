<?php

class MYNT_PAGE_LIST{

	public $default_dir  = "data/page/";
	public $system_dir   = "system/page/";
	public $default_top  = "system/page/";
	public $notlogin     = "system/page/login";

	public function getPageDir(){
		$pageDir = "blog";
		if(isset($_REQUEST["pageDir"]) && $_REQUEST["pageDir"] !== ""){
			$pageDir = $_REQUEST["pageDir"];
		}
		return $pageDir;
	}
	public function getPagePath($pageDir = ""){

		if($pageDir === ""){
			$pageDir = $this->getPageDir();
		}

		$path = "";
		if($pageDir === "system"){
			$path = "system/html/";
		}
		else if(is_dir("data/page/".$pageDir)){
			$path = "data/page/".$pageDir."/";
		}
		return $path;
	}
	public function getPageDirName($pageDir = ""){

		if($pageDir === ""){
			$pageDir = $this->getPageDir();
		}

		$types = $this->getPageCategoryLists("type");

		$res = "Blog";
		for($i=0, $c=count($types); $i<$c; $i++){
			if($types[$i]["key"] === $pageDir){
				$res = $types[$i]["value"];
				break;
			}
		}

		return $res;
	}

	// クエリを判別してページを表示（ない場合はエラーページ）
	function getSource($type = ""){

		// ログイン後に読み込みページが変わる場合の設定
		// $file = ($loginedFile !== "" && isset($_SESSION["login_id"]) && $_SESSION["login_id"])?$loginedFile:$notLoginFile;
		//

		// mode
		$mode = "";
		$file = "";



		// path-get
		$path = "";
		if(!isset($_REQUEST["m"])&& !$_REQUEST["m"] && !isset($_REQUEST["p"]) && !$_REQUEST["p"]){
			$path = "data/page/default/top.html";
		}
		else if(!isset($_REQUEST["m"])&& !$_REQUEST["m"] && isset($_REQUEST["p"]) && $_REQUEST["p"]){
			$path = "data/page/blog/".$_REQUEST["p"].".html";
		}
		else if(isset($_REQUEST["m"])&& $_REQUEST["m"] && isset($_REQUEST["p"]) && $_REQUEST["p"]){
			$path = "data/".$_REQUEST["m"]."/".$_REQUEST["p"].".html";
		}

		// 認証
		// if($type === "login" && !isset($_SESSION["login_id"])){
		// 	$path = $this->system_dir."login.html";
		// }
		//
		// // クエリにページ指定があるか確認
		// else if(isset($_REQUEST["p"]) && $_REQUEST["p"]){
		// 	if(is_file($this->default_dir.$_REQUEST["p"].".html")){
		// 		// $path = $this->default_dir.$_REQUEST["p"].".html";
		// 		$path = "design/".$GLOBALS["config"]["design"]["target"]."/html/blog.html";
		// 	}
		// }
		// else if(isset($_REQUEST["blog"]) && $_REQUEST["blog"]){
		// 	if(is_file("data/page/blog/".$_REQUEST["blog"].".html")){
		// 		// $path = $this->default_dir.$_REQUEST["p"].".html";
		// 		$path = "design/".$GLOBALS["config"]["design"]["target"]."/html/blog.html";
		// 	}
		// }


		// systemページ
		else if(isset($_REQUEST["system"]) && $_REQUEST["system"]){
			// $file = $_REQUEST["system"];
			if(is_file($this->system_dir.$_REQUEST["system"].".html")){
				$path = $this->system_dir.$_REQUEST["system"].".html";
			}
		}

		// ページ指定が無ければデフォルトページを設定
		else{
			$top = (isset($GLOBALS["config"]["page"]["top"]))?$GLOBALS["config"]["page"]["top"]:"top";
			// $path = "design/".$GLOBALS["config"]["design"]["target"]."/html/".$top.".html";
			$path = "data/page/default/".$top.".html";
		}

		if($path === "" || !is_file($path)){
			$path = $this->default_404().".html";
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

		$path="";

		$pageDir = $this->getPageDir();

		if(isset($_REQUEST["p"]) && $_REQUEST["p"]){
			$path = $this->default_dir.$pageDir."/";
			if(is_file($this->default_dir.$pageDir."/".$_REQUEST["p"].".info")){
				$path = $this->default_dir.$pageDir."/".$_REQUEST["p"].".info";
			}
			else{
				$path .= $this->default_404().".info";
			}
		}
		if(isset($_REQUEST["system"]) && $_REQUEST["system"]){
			$path = $this->default_dir.$pageDir."/";
			if(is_file($this->default_dir.$pageDir."/".$_REQUEST["system"].".info")){
				$path = $this->default_dir.$pageDir."/".$_REQUEST["system"].".info";
			}
			else{
				$path .= $this->default_404().".info";
			}
		}
		else{
			$top = $top = (isset($GLOBALS["config"]["page"]["top"]))?$GLOBALS["config"]["page"]["top"]:"top";
			$path = $this->default_top.$top.".info";
		}

		$json = array();
		if(is_file($path)){
			$source = file_get_contents($path);
			$json   = json_decode($source , true);
		}

		return $json;
	}

	//
	public function getFile($filePath){
		if(!is_file($filePath)){return;}
		$data = file_get_contents($filePath);
		$data = str_replace("<","&lt;",$data);
		$data = str_replace(">","&gt;",$data);
		return $data;
	}

	public function getFileLists($ext="html"){
		// if(!$type){return;}

		$path = "data/page/";
		if(!is_dir($path)){return;}

		$lists = array();
		$files = scandir($path);
		for($i=0,$c=count($files); $i<$c; $i++){
			if($files[$i]==="." || $files[$i]===".."){continue;}
			if($ext && !preg_match("/(.+?)\.".$ext."/",$files[$i],$match)){continue;}
			// $lists[] = $files[$i];
			$lists[] = $match[1];
		}
		// print_r($lists);
		return $lists;
	}
	public function getFileListsOptions($ext="html"){
		// if(!$type){return;}

		$fileNames = $this->getFileLists($ext);

		$options = array();
		for($i=0,$c=count($fileNames); $i<$c; $i++){
			// preg_match("/(.+?)\.(.+?)/",$files[$i] , $match);
			$selected = (isset($_REQUEST["file"]) && $_REQUEST["file"] === $fileNames[$i])?"selected":"";
			$viewTitle = $this->getPageInfoString($fileNames[$i],"title");
			if(!$viewTitle){$viewTitle = "* ".$fileNames[$i];}
			$options[] = "<option value='".$fileNames[$i]."' ".$selected.">".$viewTitle."</option>".PHP_EOL;
		}
		// print_r($options);
		return join("",$options);
	}

	// page-data-save
	public function setSystemPage(){
		// die("saveing");
		// die($_REQUEST["source"]);
		// die($_REQUEST["file"]." | ".$_REQUEST["type"]);

		$current_time = time();

		// file-name
		if(!isset($_REQUEST["file"]) || !$_REQUEST["file"]){
			$_REQUEST["file"] = $current_time;

		}
		if(!isset($_REQUEST["regist"]) || !$_REQUEST["regist"]){
			$_REQUEST["regist"] = $current_time;
		}

		// file-path
		$path_html = "data/page/".$_REQUEST["file"].".html";
		$path_info = "data/page/".$_REQUEST["file"].".info";
		$backupDir = "data/backup/page/".$_REQUEST["type"]."/";

		// backup-folder
		if(!is_dir($backupDir)){
			mkdir($backupDir , 0777 , true);
		}

		// backup
		if(is_file($path_html)){
			rename($path_html , $backupDir.$_REQUEST["file"].".html.".date(Ymdhis));
		}
		if(is_file($path_info)){
			rename($path_info , $backupDir.$_REQUEST["file"].".info.".date(Ymdhis));
		}

		// source-save
		file_put_contents($path_html , $_REQUEST["source"]);

		// info-save
		$info = array(
			"id"        => $_REQUEST["file"],
			"title"     => $_REQUEST["title"],
			"discription"=> $_REQUEST["source"],
			"eyecatch"  => $_REQUEST["eyecatch"],
			"type"      => $_REQUEST["type"],
			"status"    => $_REQUEST["status"],
			"schedule"  => $_REQUEST["schedule"],
			"tag"       => $_REQUEST["tag"],
			"group"     => $_REQUEST["group"],
			"category"  => $_REQUEST["category"],
			"regist"    => $_REQUEST["regist"],
			"update"    => $current_time
		);
		$json = json_encode($info);
		$json = preg_replace_callback('/\\\\u([0-9a-zA-Z]{4})/', function ($matches) {return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');},$json);
		file_put_contents($path_info , $json);


		//redirect
		$url = new MYNT_URL;
		header("Location: ". $url->getUrl()."?system=".$_REQUEST["system"]."&file=".$_REQUEST["file"]);

	}

	public function getPageInfoString($fileName = "" , $key = ""){
		if($key === "" || $fileName === ""){return;}

		$pageDir = $this->getPageDir();

		if(!is_file($this->default_dir.$pageDir."/".$fileName.".info")){return;}

		$json = json_decode(file_get_contents($this->default_dir.$fileName.".info"),true);

		if(!isset($json[$key])){return;}

		return $json[$key];
	}

	public function getPageCategoryLists($key=""){
		// if($key===""){return array();}
		// $path = "data/config/pageCategoryLists.json";
		// if(!is_file($path)){return array();}
		// $json = json_decode(file_get_contents($path),true);
		// if(!isset($json[$key])){return array();}
		// return $json[$key];
		if(isset($GLOBALS["config"]["pageCategoryLists"][$key])){
			return $GLOBALS["config"]["pageCategoryLists"][$key];
		}
		else{
			return array();
		}
	}
	public function getPageCategoryListsOptions($key=""){
		if($key===""){return "";}

		// configデータの取得
		$lists = $this->getPageCategoryLists($key);

		// 登録データの取得
		$val = "";
		if(isset($_REQUEST["file"])){
			$val = $this->getPageInfoString($_REQUEST["file"],$key);
		}

		// optionタグの作成
		$options = array();
		for($i=0,$c=count($lists); $i<$c; $i++){
			$selected = "";
			if($val !== "" && $val === $lists[$i]["key"]){$selected = " selected";}
			$options[] = "<option value='".$lists[$i]["key"]."'".$selected.">".$lists[$i]["value"]."</option>";
		}
		return join(PHP_EOL,$options);
	}


	public function getPageCategoryLists_li($key=""){

		// $pageDir = "blog";
		// if(isset($_REQUEST["pageDir"]) && $_REQUEST["pageDir"]!==""){
		// 	$pageDir = $_REQUEST["pageDir"];
		// }

		if($key===""){return "";}

		$pageDir = $this->getPageDir();

		// configデータの取得
		$lists = $this->getPageCategoryLists($key);

		// optionタグの作成
		$html = "";
		for($i=0,$c=count($lists); $i<$c; $i++){

			$MYNT_URL = new MYNT_URL;
			$link_url = $MYNT_URL->getUrl() ."?b=".$_REQUEST["b"]."&p=".$_REQUEST["p"]."&pageDir=".$pageDir;

			$active = "";
			if($lists[$i]["key"] === $_REQUEST["pageDir"]){$active = "active";}

			$html .= "<li role='presentation' class='".$active."'>";
			$html .= "<a class='dropdown-toggle' role='button' aria-haspopup='true' aria-expanded='false' href='".$link_url."'>".$lists[$i]["value"]." (".$this->getPageCount($lists[$i]["key"]).")</a>";
			$html .= "</li>";
			$html .= PHP_EOL;
		}
		return $html;
	}
	public function getPageTypeLists_li(){
		$pageDir = $this->getPageDir();

		// configデータの取得
		$lists = $this->getPageCategoryLists("type");

		// optionタグの作成
		$html = "";
		for($i=0,$c=count($lists); $i<$c; $i++){

			$MYNT_URL = new MYNT_URL;
			$link_url = $MYNT_URL->getUrl() ."?b=".$_REQUEST["b"]."&p=".$_REQUEST["p"]."&pageDir=".$lists[$i]["key"];

			$active = "";
			if($lists[$i]["key"] === $_REQUEST["status"]){$active = "active";}

			$html .= "<li role='presentation' class='".$active."'>";
			$html .= "<a href='".$link_url."'>".$lists[$i]["value"]."</a>";
			$html .= "</li>";
			$html .= PHP_EOL;
		}
		return $html;
	}


	function getFileSource(){
		if(isset($_REQUEST["filePath"]) && is_file($_REQUEST["filePath"])){
			echo file_get_contents($_REQUEST["filePath"]);
		}
		exit();
	}

	public function getTemplateFile(){
		if(!isset($_REQUEST["filePath"]) || !is_file($_REQUEST["filePath"])){return;}
		$temp = file_get_contents($_REQUEST["filePath"]);

		// $mode = "";
		// if(isset($_REQUEST["mode"]) && $_REQUEST["mode"]){
		// 	$mode = $_REQUEST["mode"];
		// }

		$MYNT_SOURCE = new MYNT_SOURCE;
		echo $MYNT_SOURCE->rep($temp);
		exit();
	}



	public function viewPageLists($status = ""){

		$pageDir = $this->getPageDir();
		$pagePath = $this->getPagePath($pageDir);

		$MYNT_DATE = new MYNT_DATE;

		$lists = $this->getPageLists($status);
		$html = "";
		for($i = 0,$c = count($lists); $i < $c; $i++){
			$infoFile = str_replace(".html",".info",$pagePath.$lists[$i]);
			$info   = $this->getPageInfoFromPath($infoFile);
			$title  = (isset($info["title"]))?$info["title"] : "<b class='string-blue'>File:</b> ".$lists[$i];
			$update = ($info["update"])?$info["update"]:filemtime($pagePath.$lists[$i]);

			$html .= "<tr class='titleList' onclick='location.href=\"?b=system&p=p_pageEdit&file=".$this->getFileName2ID($lists[$i])."&pageDir=".$pageDir."\"'>".PHP_EOL;
			$html .= "<th style='width:50px;'>".($i+1)."</th>".PHP_EOL;
			$html .= "<td>".$title."</td>".PHP_EOL;
			$html .= "<td>".$this->getKey2Value($GLOBALS["config"]["pageCategoryLists"]["status"] , $info["status"])."</td>".PHP_EOL;
			$html .= "<td>".$MYNT_DATE->format_ymdhis($update)."</td>".PHP_EOL;
			$html .= "<td>".$MYNT_DATE->format_ymdhis($info["release"])."</td>".PHP_EOL;
			$html .= "</tr>".PHP_EOL;
		}

		return $html;
	}

	public function getPageLists($status = ""){

		$pageDir = $this->getPageDir();
		$pagePath = $this->getPagePath($pageDir);

		$datas = array();

		if(!is_dir($pagePath)){return $datas;}

		$lists = scandir($pagePath);

		for($i=0,$c=count($lists); $i<$c; $i++){
			if($lists[$i]==="." || $lists[$i]===".."){continue;}
			if(preg_match("/^(.+?)\.html$/",$lists[$i],$m)){
				$json = $this->getPageInfoFromPath($pagePath.$lists[$i]);
				if($status !== "" && !isset($json["status"])){continue;}
				if($status !== "" && $status !== $json["status"]){continue;}
				$datas[] = $lists[$i];
			}
		}
		return $datas;
	}

	public function getPageInfoFromPath($path){
		$datas = array();
		if(is_file($path)){
			$datas = json_decode(file_get_contents($path),true);
		}
		return $datas;
	}

	// public function viewStatusLists(){
	// 	$html = "";
	// 	for($i=0; $i<count($GLOBALS["config"]["pageCategpryLists"]["status"]); $i++){
	// 		$html .= $GLOBALS["config"]["pageCategpryLists"]["status"][$i]."".PHP_EOL;
	// 	}
	// }

	public function getPageCount($status = ""){
		$lists = $this->getPageLists($status);
		return count($lists);
	}

	public function getKey2Value($data , $key){
		$res = "";
		for($i=0; $i<count($data); $i++){
			if($data[$i]["key"] === $key){
				$res = $data[$i]["value"];
				break;
			}
		}
		return $res;
	}
	public function getValue2Key($data , $value){
		$res = "";
		for($i=0; $i<count($data); $i++){
			if($data[$i]["value"] === $value){
				$res = $data[$i]["key"];
				break;
			}
		}
		return $res;
	}
	public function getFileName2ID($path){
		$sp0 = explode("/",$path);
		$sp1 = explode(".",$sp0[count($sp0)-1]);
		$sp2 = array_pop($sp1);
		return join(".",$sp1);
	}



}
