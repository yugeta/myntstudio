<?php

class Maintenance extends fw_define{

    function checkQuery(){
        if(isset($_REQUEST['action']) && $_REQUEST['action']){
            if($_REQUEST['action']=="write"){
                die("write");
            }
        }
        else{
            $this->setGlobals();
        }
    }


    function setGlobals(){
        if(!isset($_REQUEST['id'])){return;}
        $id = $_REQUEST['id'];

        $torisetsu = new torisetsu();

        //items
        $items = $torisetsu->getJson("items.json");
        if(isset($items[$id])){
            $GLOBALS['items'] = $items[$id];
        }

        //$GLOBALS['groups'] = $torisetsu->getJson("group.json");
        $GLOBALS['brands'] = $torisetsu->getJson("brands.json");
        $images = $torisetsu->getJson("images.json");
        unset($GLOBALS['images']);
        if(count($images)){
            foreach($images as $key=>$val){//echo print_r($val);
                if($val['items.json']!=$id){continue;}
                $GLOBALS['images'] = $val;
                break;
            }
        }
        //print_r($GLOBALS['images']);


        $GLOBALS['sites']  = $torisetsu->getJson("sites.json");
        $GLOBALS['types']  = $torisetsu->getJson("types.json");
        $GLOBALS['tags']   = $torisetsu->getJson("tags.json");
    }

    function getOptions($json_file){
        $torisetsu = new torisetsu();
        $groups = $torisetsu->getJson($json_file);
        if(!count($groups)){return;}
        $html="";
        foreach($groups as $key=>$val){
            $sel = ($key==$GLOBALS['items'][$json_file])?"selected":"";
            $html.= "<option value='".$key."' ".$sel.">".$val['name']."</option>"."\n";
        }
        return $html;
    }

    function getLinks(){
        $torisetsu = new torisetsu();
        $sites = $torisetsu->getJson("sites.json");
        $html="";
        foreach($sites as $key=>$val){
            if($val['items.json']!=$GLOBALS['items']['id']){continue;}
            $html.= "<div>".$key.":".$val['name']."</div>"."\n";
        }
        return $html;
    }
    function getManuals(){
        $torisetsu = new torisetsu();
        $manuals = $torisetsu->getJson("manuals.json");
        $html="";
        foreach($manuals as $key=>$val){
            if($val['items.json']!=$GLOBALS['items']['id']){continue;}

            $tpl = $this->getManualLine($val);

            $html.= $tpl."\n";
        }
        return $html;
    }
    function getManualLine($data=array()){
        if(!isset($this->tpl_manual)){
            $this->tpl_manual = file_get_contents("plugins/".$_REQUEST['plugins']."/template/manuals-list.html");
        }
        if(!isset($data['key'])){$data['key']="";}
        $tpl = $this->tpl_manual;
        $tpl = str_replace("<%id%>",$data['key'],$tpl);
        $tpl = str_replace("<%name%>",$data['name'],$tpl);
        $tpl = str_replace("<%type%>",$data['type'],$tpl);
        $tpl = str_replace("<%url%>",$data['url'],$tpl);
        return $tpl;
    }
}
