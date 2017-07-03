<?php

/**
 * Mynt Studio
 */
 
date_default_timezone_set('Asia/Tokyo');

class MYNT{
    function __construct(){
        $this->checkValidation();
        $this->loadSystemConfigs("system/config");
        $this->loadModulePHPs("system/php");
        $this->loadPlugins();
        
//         echo isset($GLOBALS["config"]["defines"]["a"]);
//         echo $GLOBALS["config"]["design"]["target"];
        
    }
    
    function checkValidation(){
        if(!is_dir("./design")){
            $this->viewError("Not found directory [ design/ ]");
        }
        if(!is_dir("./library")){
            $this->viewError("Not found directory [ library/ ]");
        }
        if(!is_dir("./plugin")){
            $this->viewError("Not found directory [ plugin/ ]");
        }
        if(!is_dir("./system")){
            $this->viewError("Not found directory [ system/ ]");
        }
    }
    
    // set $GLOBALS
    function loadSystemConfigs($dir){
        if(!is_dir($dir)){
            $this->viewError("Not found directory [ ".$dir." ]");
        }
        
        if(!preg_match("/\/$/",$dir)){
             $dir .= "/";
        }
        
        $files = scandir($dir);
        
         for($i=0; $i<count($files); $i++){
                if($files[$i] == "." || $files[$i] == ".." || !preg_match("/\.json$/",$files[$i])){continue;}
                $key = str_replace(".json","",$files[$i]);
                $GLOBALS["config"][$key] = json_decode(file_get_contents($dir.$files[$i]),true);
            }
    }
    
    function loadModulePHPs($dir){
        if(!is_dir($dir)){
          $this->viewError("Not found directory [ ".$dir." ]");
        }
        
        if(!preg_match("/\/$/",$dir)){
            $dir .= "/";
        }
        
        $files = scandir($dir);
        
        for($i=0; $i<count($files); $i++){
            if($files[$i] == "." || $files[$i] == ".." || !preg_match("/\.php$/",$files[$i])){continue;}
            require_once $dir.$files[$i];
        }
        
    }
    
    function loadPlugins(){
      $dir = $GLOBALS["config"]["define"]["plugin"];
      
      if(!is_dir($dir)){
        $this->viewError("Not found directory [ ".$dir." ]");
      }
      if(!preg_match("/\/$/",$dir)){
        $dir .= "/";
      }
      
      if(!isset($GLOBALS["config"]["plugins"]) || !count($GLOBALS["config"]["plugins"])){
        return;
      }
      
      
      for($i=0; $i<count($GLOBALS["config"]["plugins"]); $i++){
        $path = $dir . $GLOBALS["config"]["plugins"][$i] ."/module/";
        if(!is_dir($path)){continue;}
        $this->loadModulePHPs($path);
      }
      
    }
    
    function viewError($msg){
        echo "<h1>".$msg."</h1>";
        exit();
    }
}

new MYNT;
