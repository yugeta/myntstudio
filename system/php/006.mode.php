<?php

class MYNT_MODE{

	// Check Query
	public function checkQuery(){

		// method [ class / function ]
		if(isset($_POST["method"]) && count(explode("/",$_POST["method"])) === 2){
			$sp = explode("/",$_POST["method"]);
			if(method_exists($sp[0],$sp[1])){
				$cls = new $sp[0];
				call_user_func_array(array($cls , $sp[1]),array());
			}
		}

		// mode
		else if(isset($_REQUEST["mode"]) && $_REQUEST["mode"]){
			$this->checkMode($_REQUEST["mode"]);
		}
	}

	//

	// Check Mode
	public function checkMode($mode){
		switch($mode){
			case "logout":
				if(class_exists(MYNT_PLUGIN_LOGIN)){
					$MYNT_LOGIN = new MYNT_PLUGIN_LOGIN;
					$MYNT_LOGIN->checkLogout();
				}
				break;
		}
	}

}
