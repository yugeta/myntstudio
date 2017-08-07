<?php

class MYNT_GROUP{

	// グループコンフィグデータを取得
	public function getData($path = "data/options/group.json"){
		if(!is_file($path)){return;}
		return json_decode(file_get_contents($path) , true);
	}

	// グループデータ部分を配列で取得
	public function getLists(){
		$group = $this->getData();
		if(!isset($group["data"]) || !count($group["data"])){return;}
		return $group["data"];
	}

	// 名前の一覧を取得
	public function getArray_Names(){
		$group = $this->getLists();
		if(!count($group)){return;}
		$names = array();
		for($i=0,$c=count($group); $i<$c; $i++){
			$names[] = $group["name"];
		}
		return $names;
	}

	// key = value(name)で 連想配列を取得
	public function getAssociateArray(){
		$group = $this->getLists();
		if(!count($group)){return;}
		$data = array();
		for($i=0,$c=count($group); $i<$c; $i++){
			$data[$group["key"]] = $group["name"];
		}
		return $data;
	}

	public function getNamesHtml_option($value="",$arrtibute="",$class="",$style=""){
		$group = $this->getLists();
		if(!count($group)){return;}
		$html = "";
		for($i=0,$c=count($group); $i<$c; $i++){
			$selected = ($value == $group[$i]["id"])?"selected":"";
			$html .= "<option value='".$group[$i]["id"]."' ".$arrtibute." class='".$class."' style='".$style."' ".$selected.">";
			$html .= $group[$i]["name"];
			$html .= "</option>";
			$html .= PHP_EOL;
		}
		return $html;
	}
	public function getNamesHtml_links($arrtibute="",$class="",$style=""){
		$group = $this->getLists();
		if(!count($group)){return;}

		$MYNT_URL = new MYNT_URL;
		$defaultURL = $MYNT_URL->getUrl() ."?default=search&group=";

		$html = "";
		for($i=0,$c=count($group); $i<$c; $i++){
			$url = $defaultURL.$group[$i]["key"];
			$html .= "<p>";
			$html .= "<a href='".$url."' ".$arrtibute." class='".$class."' style='".$style."'>";
			$html .= $group[$i]["name"];
			$html .= "</a>";
			$html .= "</p>";
			$html .= PHP_EOL;
		}
		return $html;
	}
	public function getNamesHtml_li($arrtibute="",$class="",$style=""){
		$group = $this->getLists();
		if(!count($group)){return;}
		$html = "";
		for($i=0,$c=count($group); $i<$c; $i++){
			$html .= "<li class='".$class."' ".$arrtibute." style='".$style."'>";
			$html .= $group[$i]["name"];
			$html .= "</li>";
			$html .= PHP_EOL;
		}
		return $html;
	}
}
