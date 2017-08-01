<?php

class MYNT_PICTURE{

	public $default_page_dir = "data/page/";
	public $default_pic_dir = "data/picture/";

	public function getEyecatchFilePath($articleId = ""){
		if($articleId === ""){return;}

		$page_info_path = $this->default_page_dir.$articleId.".info";
		if(!is_file($page_info_path)){return;}

		$jsonPage = json_decode(file_get_contents($this->default_page_dir.$articleId.".info") , true);
		if(!isset($jsonPage["eyecatch"]) || !$jsonPage["eyecatch"]){return;}

		$pic_info_path = $this->default_pic_dir.$jsonPage["eyecatch"].".info";
		if(!is_file($pic_info_path)){return;}

		$jsonPic = json_decode(file_get_contents($pic_info_path) , true);
		if(!isset($jsonPic["extension"]) || !$jsonPic["extension"]){return;}

		$pic_file_path = $this->default_pic_dir.$jsonPage["eyecatch"].".".$jsonPic["extension"];
		if(!is_file($pic_file_path)){return;}

		return "<img class='' src='".$pic_file_path."' />";
	}
}
