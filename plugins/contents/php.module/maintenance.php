<?php

class Maintenance extends fw_define{

    function checkQuery(){
        if(isset($_REQUEST['action']) && $_REQUEST['action']){
            if($_REQUEST['action']=="write"){
                //die("write");
                $this->setWrite();

                $libUrl = new libUrl();
                $libUrl->setUrl($libUrl->getUrl."?menu=".$_REQUEST['menu']."&id=".$_REQUEST['id']);
            }

        }
        else{
            $this->setGlobals();
        }
    }

    function setWrite(){
        $torisetsu = new torisetsu();
        $ymdhis = date("YmdHis");
        $item_id = $_REQUEST['items']['id'];

        //items.json
        $itemsJson = $torisetsu->getOne("items.json",$id);
        $items=array(
            "flg"         => $_REQUEST['items']['flg'],
            "update"      => $ymdhis,
            "entry"       => ($itemsJson['entry'])?$itemsJson['entry']:$ymdhis,
            "id"          => $item_id,
            "name"        => $_REQUEST['items']['name'],
            "groups.json" => $_REQUEST['items']['groups.json'],
            //"tags.json"   => array($_REQUEST['items']['tags.json']),
            "brands.json" => $_REQUEST['items']['brands.json'],
            "stock_number"=> $_REQUEST['items']['stock_number'],
            "url"         => $_REQUEST['items']['url'],
            "img"         => $_REQUEST['items']['img']
        );
        $items = $this->setJsonNull($items);
        $data = json_encode($items);
        $data = preg_replace_callback('/\\\\u([0-9a-zA-Z]{4})/', function ($matches) {return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');},$data);
        $data = str_replace('\\/', '/' ,$data);
        file_put_contents("data/contents/items.json",$data."\n",FILE_APPEND);
        //print_r($data);exit();

        //manuals.json
        foreach($_REQUEST['manual'] as $id=>$req){
            if(!$id){continue;}
            $manuals=array(
                "flg"           => $req['flg'],
                "update"        => $ymdhis,
                "entry"         => ($itemsJson['entry'])?$itemsJson['entry']:$ymdhis,

                "id"            => $id,
                "items.json"    => $item_id,
                "name"          => $req['name'],
                "type"          => $req['type'],
                "url"           => $req['url']
            );
            $manuals = $this->setJsonNull($manuals);
            $data = json_encode($manuals);
            $data = preg_replace_callback('/\\\\u([0-9a-zA-Z]{4})/', function ($matches) {return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');},$data);
            $data = str_replace('\\/', '/' ,$data);
            file_put_contents("data/contents/manuals.json",$data."\n",FILE_APPEND);
            //print_r($data);exit();
        }

    }

    function setJsonNull($data){
        foreach($data as $key=>$val){
            if($val==null){
                $data[$key]="";
            }
        }
        return $data;
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
        // $images = $torisetsu->getJson("images.json");
        // unset($GLOBALS['images']);
        // if(count($images)){
        //     foreach($images as $key=>$val){//echo print_r($val);
        //         if($val['items.json']!=$id){continue;}
        //         $GLOBALS['images'] = $val;
        //         break;
        //     }
        // }
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
        if(!isset($GLOBALS['items']) || !isset($GLOBALS['items']['id'])){$GLOBALS['items']['id']=uniqid();}
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
        //if(!isset($data['key'])){$data['key']="";}

        if(!isset($data['name'])){$data['name']="";}
        if(!isset($data['type'])){$data['type']="";}
        if(!isset($data['url'])) {$data['url']="";}

        $torisetsu = new torisetsu();
        $id = (isset($data['id']) && $data['id'])?$data['id']:$torisetsu->getID();
        $tpl = $this->tpl_manual;
        $tpl = str_replace("<%id%>",$id,$tpl);
        $tpl = str_replace("<%name%>",$data['name'],$tpl);
        $tpl = str_replace("<%type%>",$data['type'],$tpl);
        $tpl = str_replace("<%url%>",$data['url'],$tpl);
        return $tpl;
    }
}
