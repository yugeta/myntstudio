<?php

/**
 * Mynt Studio
 */
 
date_default_timezone_set('Asia/Tokyo');

class MYNT{
    function __construct(){
        $this->checkValidation();
        $this->loadConfig("system/config");
        $this->loadPlugin("system/php");
        
//         echo isset($GLOBALS["config"]["defines"]["a"]);
//         echo $GLOBALS["config"]["design"]["target"];
        
    }
    
    function checkValidation(){
        if(!is_dir("./design")){
            $this->viewError("Not found directory [ design/ ]");
        }
        if(!is_dir("./plugin")){
            $this->viewError("Not found directory [ plugin/ ]");
        }
        if(!is_dir("./system")){
            $this->viewError("Not found directory [ system/ ]");
        }
        if(!is_dir("./library")){
            $this->viewError("Not found directory [ library/ ]");
        }
        
    }
    
    function loadConfig($dir){
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
    
    function loadPlugin($dir){
        if(is_dir($dir)){
            
            if(!preg_match("/\/$/",$dir)){
                $dir .= "/";
            }
            
            $files = scandir($dir);
            
            for($i=0; $i<count($files); $i++){
                if($files[$i] == "." || $files[$i] == ".." || !preg_match("/\.php$/",$files[$i])){continue;}
                require_once $dir.$files[$i];
            }
            
        }
        else{
            $this->viewError("Not found directory [ ".$dir." ]");
        }
    }
    
    function viewError($msg){
        echo "<h1>".$msg."</h1>";
        exit();
    }
}

new MYNT;
