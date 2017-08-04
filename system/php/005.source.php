<?php

/**
* RepTag
* Replacement-Tag : mynt-format
* ex) <**:**>
*/

class MYNT_SOURCE{

	public function viewFile($path){
		$html = "";
		if(is_file($path)){
			$html = $this->rep(file_get_contents($path));
		}
		return $html;
	}

	public function rep($source=""){
    if($source===""){return;}
    return $this->pattern($source);
  }

	public function pattern($source){

		$source = $this->pattern1($source);
		$source = $this->pattern2($source);
		$source = $this->pattern3($source);
		// $source = $this->pattern_variable($source);
		// $source = $this->pattern_echo($source);
		$source = $this->pattern_if($source);
		$source = $this->pattern_for($source);
		return $source;
	}

	public function pattern1($source){

		$keys    = array("post","get","request","globals","define","session","server");
		$ptn = '<('.join('|',$keys).')\:(.+?)>';
		preg_match_all("/".$ptn."/is" , $source  , $match);

		if(!count($match[1])){
			return $source;
		}

		for($i=0, $c=count($match[1]); $i<$c; $i++){
			if($match[0][$i]===""){continue;}
			$res = $this->getValue($match[1][$i],$match[2][$i]);
			$source = str_replace($match[0][$i],$res,$source);
		}

		return $source;
	}
	public function pattern2($source){

		$keys = array("class","function");
		$ptn = '<('.join('|',$keys).')\:(.+?)\((.*?)\)>';
		preg_match_all("/".$ptn."/is" , $source  , $match);

		if(count($match[1])){
			for($i=0, $c=count($match[1]); $i<$c; $i++){
				if($match[0][$i]===""){continue;}
				$res = $this->getProcs($match[1][$i],$match[2][$i],$match[3][$i]);
				$source = str_replace($match[0][$i],$res,$source);
			}
		}
		// else{
		// 	$ptn = '<class\:(.+)\/(.+)>';
		// 	preg_match_all("/".$ptn."/is" , $source  , $match2);
		// 	if(count($match2[1])){echo $match2[1][0];
		// 		for($i=0, $c=count($match2[1]); $i<$c; $i++){echo $match2[2][$i];
		// 			$cls = new $match2[1][$i];
		// 			$source = call_user_func_array(array($cls , $match2[2][$i]));
		// 		}
		// 	}
		// }

		return $source;
	}
	public function pattern3($source){

		$keys = array("eval","file");
		$ptn = '<('.join('|',$keys).')\:\"(.+?)\">';
		preg_match_all("/".$ptn."/is" , $source  , $match);

		if(!count($match[1])){
			return $source;
		}
		// print_r($match);

		for($i=0, $c=count($match[1]); $i<$c; $i++){
			if($match[0][$i]===""){continue;}
			$res = $this->getCodes($match[1][$i],$match[2][$i]);
			$source = str_replace($match[0][$i],$res,$source);
		}

		return $source;
	}

	public function pattern_if($source){

		$ptn = '<if\((.+?)\)>(.+?)<if\-end>';
		preg_match_all("/".$ptn."/is" , $source  , $match);

		if(!count($match[1])){
			return $source;
		}

		for($i=0, $c=count($match[1]); $i<$c; $i++){
			if($match[0][$i]===""){continue;}

			// else-check
			$val_else = "";
			$val_then = $match[2][$i];

			$ptn_else = '(.*)<else>(.*)';
			preg_match_all("/".$ptn_else."/is" , $val_then , $match_else);

			if(count($match_else[0])){
				$val_else = $match_else[2][0];
				$val_then = $match_else[1][0];
			}

			// if-else
			if(!preg_match("/<elif\(.+?\)>/",$val_then)){
				$evalStr = "if(".$match[1][$i]."){return '".$val_then."';}";
				if($val_else !== ""){
					$evalStr .= "else{return '".$val_else."';}";
				}
				$res = eval($evalStr);
			}

			// if-elseif-else
			else{
				$ptn2 = '<elif\((.+?)\)>';
				$str = $val_then;
				$str = str_replace("\n","",$str);
				$str = str_replace("\r","",$str);
				preg_match_all("/".$ptn2."/is" , $str  , $elifs);

				$elif = "";
				for($j=0; $j<count($elifs[0]); $j++){
					$elif .= "<elif\(.+?\)>(.+?)";
				}
				$ptn3 = '<if\(.+?\)>(.+?)'.$elif.'<else>.+?<if\-end>';
				preg_match_all("/".$ptn3."/is" , $match[0][$i]  , $elifs2);

				$evalStr = "if(".$match[1][$i]."){return '".$elifs2[1][0]."';}";
				for($j=2; $j<count($elifs2); $j++){
					$evalStr .= "elseif(".$elifs[1][$j-2]."){return '".$elifs2[$j][0]."';}";
				}
				$evalStr .= "else{return '".$val_else."';}";

				$res = eval($evalStr);
			}

			$source = str_replace($match[0][$i],$res,$source);
		}
		return $source;
	}

	public function pattern_if_bak($source){

		//
		// $ptn = '<if\((.+?)\)>(.+?)<else>(.+?)<if\-end>';
		$ptn = '<if\((.+?)\)>(.+?)<if\-end>';
		preg_match_all("/".$ptn."/is" , $source  , $match);

		if(!count($match[1])){
			return $source;
		}

		for($i=0, $c=count($match[1]); $i<$c; $i++){
			if($match[0][$i]===""){continue;}

			// else-check
			$val_else = "";
			$val_then = $match[2][$i];

			$ptn_else = '(.*)<else>(.*)';
			preg_match_all("/".$ptn_else."/is" , $val_then , $match_else);

			if(count($match_else[0])){
				$val_else = $match_else[2][0];
				$val_then = $match_else[1][0];
			}

			// if-else
			if(!preg_match("/<elif\(.+?\)>/",$val_then)){
				$evalStr = "if(".$match[1][$i]."){return '".$val_then."';}";
				if($val_else !== ""){
					$evalStr .= "else{return '".$val_else."';}";
				}
				$res = eval($evalStr);
			}

			// if-elseif-else
			else{
				$ptn2 = '<elif\((.+?)\)>';
				$str = $val_then;
				$str = str_replace("\n","",$str);
				$str = str_replace("\r","",$str);
				preg_match_all("/".$ptn2."/is" , $str  , $elifs);

				$elif = "";
				for($j=0; $j<count($elifs[0]); $j++){
					$elif .= "<elif\(.+?\)>(.+?)";
				}
				$ptn3 = '<if\(.+?\)>(.+?)'.$elif.'<else>.+?<if\-end>';
				preg_match_all("/".$ptn3."/is" , $match[0][$i]  , $elifs2);

				$evalStr = "if(".$match[1][$i]."){return '".$elifs2[1][0]."';}";
				for($j=2; $j<count($elifs2); $j++){
					$evalStr .= "elseif(".$elifs[1][$j-2]."){return '".$elifs2[$j][0]."';}";
				}
				$evalStr .= "else{return '".$val_else."';}";

				$res = eval($evalStr);
			}

			$source = str_replace($match[0][$i],$res,$source);
		}
		return $source;
	}

	public function pattern_for($source){
		$ptn = '<for\((.*?)\.\.(.*?)\)>(.+?)<for\-end>';
		preg_match_all("/".$ptn."/is" , $source  , $match);

		if(!count($match[1])){
			return $source;
		}

		for($i=0, $c=count($match[1]); $i<$c; $i++){
			if($match[0][$i]===""){continue;}
			$str = $match[3][$i];
			$str = str_replace('"','\"',$str);
			$str = str_replace("\n",'',$str);
			$match[2][$i] = ($match[2][$i] === "")?$match[1][$i]:$match[2][$i];
			$evalStr = '$s=""; for($j='.$match[1][$i].'; $j<='.$match[2][$i].'; $j++){$s.= str_replace("##",$j,"'.$str.'");} return $s;';
			$res = eval($evalStr);
			$source = str_replace($match[0][$i],$res,$source);
		}
		return $source;
	}

	public function getValue($key,$val){
		$res = "";
		$key = strtoupper($key);
		switch($key){
			case "POST":
				$res = $this->getArrayValue($_POST,$val);
				break;
			case "GET":
				$res = $this->getArrayValue($_GET,$val);
				break;
			case "REQUEST":
				$res = $this->getArrayValue($_REQUEST,$val);
				break;
			case "GLOBALS":
				$res = $this->getArrayValue($GLOBALS,$val);
				break;
			case "DEFINE":
				$res = constant($val);
				break;
			case "SESSION":
				$res = $this->getArrayValue($_SESSION,$val);
				break;
			case "SERVER":
				$res = $this->getArrayValue($_SERVER,$val);
				break;
			default:
				break;
		}
		return $res;
	}
	public function getArrayValue($datas,$key=""){
		if($key===""){
			return "";
		}

		$keys = explode("/",$key);

		if(count($keys) === 1){
			if(isset($datas[$key])){
				return $datas[$key];
			}
			else{
				return "";
			}
		}

		$first_key = array_shift($keys);

		return $this->getArrayValue($datas[$first_key] , join("/",$keys));
	}

	public function getProcs($key,$proc,$val){
		$res = "";
		$key = strtoupper($key);
		switch($key){
			case "CLASS":
				$res = $this->getProcs_class($proc,$val);
				break;
			case "FUNCTION":
				$res = $this->getProcs_function($proc,$val);
				break;
		}
		return $res;
	}

	public function getCodes($key,$val){
		$res = "";
		$key = strtoupper($key);
		switch($key){
			case "EVAL":
				$res = $this->getCodes_code($val);
				break;
			case "CODE":
				$res = $this->getCodes_code($val);
				break;
			case "FILE":
				$res = $this->getCodes_file($val);
				break;
		}
		return $res;
	}

  public function getProcs_class($func,$val){
    $data = explode("/" , $func);

    if(count($data)!==2 || !class_exists($data[0])){return "";}

    $query = ($val=="")?array():explode(",",$val);

		for($i=0,$c=count($query); $i<$c; $i++){
			$query[$i] = str_replace('"','',$query[$i]);
			$query[$i] = str_replace("'","",$query[$i]);
		}

    if(!method_exists($data[0],$data[1])){return;}
		$cls = new $data[0];

		return call_user_func_array(array($cls , $data[1]) , $query);
  }

	public function getProcs_function($func,$val){
    if(!function_exists($func)){return "";}

    $query = ($val=="")?array():explode(",",$val);

		for($i=0,$c=count($query); $i<$c; $i++){
			$query[$i] = str_replace('"','',$query[$i]);
			$query[$i] = str_replace("'","",$query[$i]);
		}

		return call_user_func_array($func , $query);
  }

  public function getData_FOR($val){
    preg_match("/^(.*?),(.*?),(.*?):(.*?)$/s" , $val , $match);
    //preg_match("/^([0-9]+),([0-9]+),([0-9]+):(.*?)$/s" , $val , $match);
    if(count($match)!==5){return $val;}

    $val1 = $this->getPattern_Lite($match[1]);
    $val2 = $this->getPattern_Lite($match[2]);
    $val3 = $this->getPattern_Lite($match[3]);

    $value="";
    for($i=$val1; $i<=$val2; $i=$i+$val3){
      $str = $match[4];
      $str = str_replace("%num%" , $i , $str);
      $value.= $str;
    }
    $value = $this->getPattern_Lite($value);
    return $value;
  }

	public function getCodes_code($val){
    if(!$val){return;}
    return eval($val);
  }

  public function getCodes_file($path){
    if(!is_file($path)){return;}
    $source = file_get_contents($path);
    $source = $this->rep($source);
    return $source;
  }

  public function getData_IF($val){
		$sp = explode(":",$val);
		if($sp[0]){
			return $sp[1];
		}
		else{
			return $sp[2];
		}
  }
}
