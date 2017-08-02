<?php

class MYNT_BLOG{

	public $default_article_dir = "data/page/";
	public $blogSource = "";

	// all article page lists for top-page
	public function viewArticleLists_li(){

		$tmpSource = $this->getBlogSource();

		$lists = $this->getArticleLists();
		$html = "";
		for($i=0,$c=count($lists); $i<$c; $i++){
			$json = $this->getPageInfoFromPath($this->default_article_dir.$lists[$i]);
			$html .= $this->setBlogSourceReplace($tmpSource, $json);
		}
		$rep = new MYNT_SOURCE;

		return $rep->rep($html);
	}

	public function getBlogSource(){

		$tmpSource = "";

		if($this->blogSource === ""){
			$tmpPath = "system/page/top_article.html";
			$tmpSource = file_get_contents($tmpPath);
		}
		else{
			$tmpSource = $this->blogSource;
		}
		return $tmpSource;
	}

	public function setBlogSourceReplace($tmpSource, $jsonData){
		preg_match_all("/<blog:(.+?)>/",$tmpSource,$match);
		// $tmpSource = preg_replace("<blog:title>",$json["title"],$tmpSource);
		for($i=0,$c=count($match[0]); $i<$c; $i++){
			$key = $match[1][$i];
			if(isset($jsonData[$key])){
				$repData = $this->setBlogSourceReplace_parsonal($key , $jsonData[$key]);
				$tmpSource = str_replace("<blog:".$key.">", $repData, $tmpSource);
			}
		}
		return $tmpSource;
	}
	public function setBlogSourceReplace_parsonal($key , $data){
		if($key === "eyecatch"){
			$info = $this->getPicId2Info($data);
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

	public function getPageEyecatch_img($page_id){

	}
	public function getPageTitle($page_id){

	}
	public function getPageDiscription($page_id){

	}
	public function getPageReleaseDate($page_id){

	}

	public function getPicId2Info($pageID){
		$infoPath = "data/picture/".$pageID.".info";
		if(!is_file($infoPath)){return;}

		return json_decode(file_get_contents($infoPath), true);
		// $jsonData = json_decode(file_get_contents($infoPath), true);

		// if($key !== ""){
		// 	if(){
		// 		return $jsonData[$key];
		// 	}
		// 	else{
		// 		return ""
		// 	}
		//
		// }
		// else{
		// 	return $jsonData;
		// }
	}

	//
	public function getArticleLists($status = "release"){
		if(!is_dir($this->default_article_dir)){return array();}

		$lists = scandir($this->default_article_dir);

		$datas = array();

		for($i=0,$c=count($lists); $i<$c; $i++){

			if($lists[$i]==="." || $lists[$i]===".."){continue;}

			if(preg_match("/^(.+?)\.info$/",$lists[$i],$m)){

				$json = $this->getPageInfoFromPath($this->default_article_dir.$lists[$i]);

				if($status !== "" && !isset($json["status"])){continue;}
				if($status !== "" && $status !== $json["status"]){continue;}

				$datas[] = $lists[$i];
			}
		}
		return $datas;
	}

	public function getPageInfoFromPath($path){
		if(!is_file($path)){return;}

		$datas = array();
		if(is_file($path)){
			$datas = json_decode(file_get_contents($path),true);
		}
		return $datas;
	}
}
