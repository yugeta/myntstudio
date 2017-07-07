<?php

/**
* RepTag
* Replacement-Tag : mynt-format
* ex) <%**:**:**%>
*/

class RepTag{

  public function setSource($source=""){
    if($source===""){return;}
    return $this->getPattern($source);
  }

  // public function getFile($targetFile){
  //   if(!is_file(THEME_DIR."/html/".$targetFile)){return;}
  //   $source = file_get_contents(THEME_DIR."/html/".$targetFile);
  //   return $this->getPattern($source);
  // }

  /**
  * Pattern-split
  * [format]
  * default : %%key:value%%
  * GLOBALS : hash%
  * REQUEST : %req:*key-name*:req%
  * POST    : %post:*key-name*:post%
  * GET     : %get:*key-name*:get%
  * function: %class:*class-name*:*function-name()*class%
  * function: %func:*function-name()*func%
  * [return]
  * $array[**][1];
  */
  public function getPattern($source=""){
    if($source===""){return;}

    $source = $this->getPatternMatch($source , "FOR");
    $source = $this->getPattern_Lite($source);
		// $source = $this->getPattern_Auto($source);

    return $source;
  }

	public function getPattern_Auto($source=""){
		preg_match_all('/<%(.+?):(.+?):(.+?)%>/s' , $source  , $match);
		for($i=0; $i<count($match[1]); $i++){
			if($match[1][$i] != $match[3][$i]){continue;}
			$source = $this->setTemplate_local($match[1][$i] , $match[2][$i] , $match[0][$i] , $source);
		}
    return $source;
  }

  public function getPattern_Lite($source=""){
    $source = $this->getPatternMatch($source , "IF");
    $source = $this->getPatternMatch($source , "GLOBALS");
		$source = $this->getPatternMatch($source , "CONFIG");
    $source = $this->getPatternMatch($source , "REQUEST");
    $source = $this->getPatternMatch($source , "POST");
    $source = $this->getPatternMatch($source , "GET");
    $source = $this->getPatternMatch($source , "DEFINE");
    $source = $this->getPatternMatch($source , "SESSION");
    $source = $this->getPatternMatch($source , "CLASS");
    $source = $this->getPatternMatch($source , "FUNCTION");
    $source = $this->getPatternMatch($source , "EVAL");
    $source = $this->getPatternMatch($source , "FILE");
    return $source;
  }


  public function getPatternMatch($source , $key){
    preg_match_all('/<%'.$key.':'.'(.+?)'.':'.$key.'%>/s' , $source  , $match);
		if(count($match[1])){
			$source = $this->setTemplate($key , $match , $source);
		}
    return $source;
  }

  public function setTemplate($key , $matches , $source){
    for($i=0; $i<count($matches[1]); $i++){
			$source = $this->setTemplate_local($key , $matches[1][$i] , $matches[0][$i] , $source);
    }
    return $source;
  }

	public function setTemplate_local($key , $val , $basic , $source){
    if($key === "CLASS"){
      $value = $this->getData_Class($val);
    }
    else if($key === "FOR"){
      $value = $this->getData_FOR($val);
    }
    else if($key === "IF"){
      $value = $this->getData_IF($val);
    }
    else if($key === "FILE"){
      $value = $this->getData_FILE($val);
    }
		else if($key === "CONFIG"){
      $value = $this->getData_CONFIG($val);
    }
    else{
      $value = $this->getData($key , $val);
    }
    return str_replace($basic , $value , $source);
  }



  public function getData($key , $val){
    $value = "";

    switch($key){
      case "EVAL":
        if($val){
          $value = eval($val);
        }
        else{
          $value = "";
        }
        break;
      case "GLOBALS":
				$value = $this->getArrayValue($GLOBALS , $val);
				break;
      case "REQUEST":
        if(isset($_REQUEST[$val])){
          $value = $_REQUEST[$val];
        }
        break;
      case "GET":
        if(isset($_GET[$val])){
          $value = $_GET[$val];
        }
        break;
      case "POST":
        if(isset($_POST[$val])){
          $value = $_POST[$val];
        }
        break;
      case "SESSION":
        if(isset($SESSION[$val])){
          $value = $_SESSION[$val];
        }
        break;
      case "DEFINE":
        if($val){
          $value = constant($val);
          //$value = $val;
        }
        break;
    }
    return $value;
  }

	public function getArrayValue($arr , $key){
		$keys = explode("/",$key);

		if(count($keys) == 1){
			return $arr[$key];
		}

		$first_key = array_shift($keys);

		return $this->getArrayValue($arr[$first_key] , join("/",$keys));
	}

	public function getData_CONFIG($val){//return $GLOBALS["config"]["page"]["title"];
    return $this->getArrayValue($GLOBALS["config"] , $val);
  }


  public function getData_Class($val){
    $data = explode(":",$val);
    if(count($data)!==2 || !class_exists($data[0])){return "";}
    preg_match("/(.*?)\((.*?)\)/",$data[1],$match);
    $method_name = (count($match)>2)?$match[1]:$data[1];
    $query = (count($match)>2)?explode(",",$match[2]):array();
    if(!method_exists($data[0],$method_name)){return;}
    // return call_user_func_array(array($data[0],$method_name) , $query);
		// class-call
		// return __NAMESPACE__."\\".$data[0]."::".$method_name;
		//return call_user_func_array(__NAMESPACE__."\\".$data[0]."::".$method_name , $query);
		// return call_user_func_array("\\".$data[0]."::".$method_name , $query);
		// return call_user_func_array(array($this , $data[0]."::".$method_name) , $query);
		// $a = new BookData;
		// return $a->{"book_lists"}();
		//return call_user_func_array($data[0]."::".$method_name , $query);
		// return {$data[0]}::{$method_name}($query);
		$cls = new $data[0];
		// return $cls->$method_name($query);
		return call_user_func_array(array($cls , $method_name) , $query);
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
  public function getData_FILE($val){
    $path = THEME_DIR ."/".$val;
    if(!is_file($path)){return;}
    $source = file_get_contents($path);
    $source = $this->setSource($source);
    return $source;
  }
  public function getData_IF($val){
    // $path = THEME_DIR ."/".$val;
    // if(!is_file($path)){return;}
    // $source = file_get_contents($path);
    // $source = $this->setSource($source);
    // return $source;
  }
}
