<?php

class MYNT_PLUGIN_pageAxcel{
	public function set(){
		if(!isset($_REQUEST["b"]) || !$_REQUEST["b"]){
			// $v = file_get_contents("plugin/axcel/config/version");
			$json = json_decode(file_get_contents("plugin/pageAxcel/config/default.json"),true);
			return "<script type='text/javascript' src='plugin/pageAxcel/js/axcel.js?".$json["version"]."'></script>";
		}
		else{
			return "";
		}
	}
}
