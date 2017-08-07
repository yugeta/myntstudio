<?php

class MYNT_PLUGIN_MENU{
	public function viewMenu($pos="right"){

		if(!isset($GLOBALS["plugin"]["menu"][$pos])){return;}
		$lists = $GLOBALS["plugin"]["menu"][$pos];

		if(count($lists)===0){return;}

		$MYNT_VIEW = new MYNT_VIEW;

		$html = "";
		for($i=0,$c=count($lists); $i<$c; $i++){
			if(!is_file($lists[$i]["file"])){continue;}
			$html.= $MYNT_VIEW->conv(file_get_contents($lists[$i]["file"]));
		}
		return $html;
	}
}
