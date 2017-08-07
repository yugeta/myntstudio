<?php

class MYNT_BLOG{

	public static $default_article_dir = "data/page/blog/";
	public static $blogSource = "";

	// all article page lists for top-page
	public static function viewArticleLists_li(){

		$tmpSource = self::getBlogSource();

		$lists = self::getArticleLists();

		$html = "";
		for($i=0,$c=count($lists); $i<$c; $i++){
			$json = self::getPageInfoFromPath(self::$default_article_dir.$lists[$i]);
			$html .= self::setBlogSourceReplace($tmpSource, $json);
		}
		// $MYNT_VIEW = new MYNT_VIEW;

		return MYNT::conv($html);
	}

	public static function getBlogSource(){

		$tmpSource = "";

		if(self::$blogSource === ""){
			// $tmpPath = "system/html/top_article.html";
			$tmpPath = "data/page/default/top_article.html";
			$tmpSource = file_get_contents($tmpPath);
		}
		else{
			$tmpSource = self::$blogSource;
		}
		return $tmpSource;
	}

	public static function setBlogSourceReplace($tmpSource, $jsonData){
		preg_match_all("/<blog:(.+?)>/",$tmpSource,$match);
		// $tmpSource = preg_replace("<blog:title>",$json["title"],$tmpSource);
		for($i=0,$c=count($match[0]); $i<$c; $i++){
			$key = $match[1][$i];
			if(isset($jsonData[$key])){
				$repData = self::setBlogSourceReplace_parsonal($key , $jsonData[$key]);
				$tmpSource = str_replace("<blog:".$key.">", $repData, $tmpSource);
			}
		}
		return $tmpSource;
	}
	public static function setBlogSourceReplace_parsonal($key , $data){
		if($key === "eyecatch"){
			$info = self::getPicId2Info($data);
			if($data !== "" && is_file("data/picture/".$data.".".$info["extension"])){
				$data = "<img class='eyecatch' src='data/picture/".$data.".".$info["extension"]."' />";
			}
			else{
				$data = "<img class='eyecatch' src='system/img/no-image.png' />";
			}

		}
		else{

		}
		return $data;
	}

	public static function getPageEyecatch_img($page_id){

	}
	public static function getPageTitle($page_id){

	}
	public static function getPageDiscription($page_id){

	}
	public static function getPageReleaseDate($page_id){

	}

	public static function getPicId2Info($pageID){
		$infoPath = "data/picture/".$pageID.".info";
		if(!is_file($infoPath)){return;}

		return json_decode(file_get_contents($infoPath), true);
	}

	//
	public static function getArticleLists($status = "release"){
		if(!is_dir(self::$default_article_dir)){return array();}

		$lists = scandir(self::$default_article_dir);

		$datas = array();

		for($i=0,$c=count($lists); $i<$c; $i++){

			if($lists[$i]==="." || $lists[$i]===".."){continue;}

			if(preg_match("/^(.+?)\.info$/",$lists[$i],$m)){

				$json = self::getPageInfoFromPath(self::$default_article_dir.$lists[$i]);

				if($status !== "" && !isset($json["status"])){continue;}
				if($status !== "" && $status !== $json["status"]){continue;}

				$datas[] = $lists[$i];
			}
		}
		return $datas;
	}

	public static function getPageInfoFromPath($path){
		if(!is_file($path)){return;}

		$datas = array();
		if(is_file($path)){
			$datas = json_decode(file_get_contents($path),true);
		}
		return $datas;
	}
}
