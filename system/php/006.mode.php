<?php

class MYNT_MODE{

	// Check Query
	public function checkQuery(){

		// method [ class / function ]
		if(isset($_REQUEST["method"]) && count(explode("/",$_REQUEST["method"])) === 2){
			$sp = explode("/",$_REQUEST["method"]);
			$cls = new $sp[0];
			call_user_func_array(array($cls , $sp[1]),array());
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
				// call_user_func_array(array("MYNT_LOGIN" , "checkLogout"),array());
				$MYNT_LOGIN = new MYNT_LOGIN;
				$MYNT_LOGIN->checkLogout();
				break;
		}
	}

}
