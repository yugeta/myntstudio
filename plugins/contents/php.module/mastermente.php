<?php

class MasterMente extends fw_define{

    function checkQuery(){
        if(isset($_REQUEST['action']) && $_REQUEST['action']){
            if($_REQUEST['action']=="write"){
                $this->setWrite();

                $libUrl = new libUrl();
                $libUrl->setUrl($libUrl->getUrl."?menu=".$_REQUEST['menu']);
            }

        }
        // else{
        //     //$this->setGlobals();
        // }
        //mb_convert_encoding("test","UTF-8","UTF-16");
    }

    function setWrite(){
        $torisetsu = new torisetsu();

        $ymdhis = date("YmdHis");

        foreach($_REQUEST['data'] as $k1=>$v1){
            $filePath = "data/contents/".$k1;
            if(is_file($filePath)){
                $current_datas = explode("\n",file_get_contents($filePath));
                $current_data  = array();
                for($i=0;$i<count($current_datas);$i++){
                    if(!$current_datas[$i]){continue;}
                    $json = json_decode($current_datas[$i],true);
                    $current_data[$json['id']] = $json;
                }
            }

            $json="";

            foreach($v1 as $k2=>$v2){
                if(!isset($v2["name"]) || !$v2["name"]){continue;}
                if(isset($v2["flg"]) && $v2["flg"]==1){continue;}
                $entry = (isset($current_data[$k2]['entry']) && $current_data[$k2]['entry'])?$current_data[$k2]['entry']:$ymdhis;
                unset($data);
                $data = json_encode(array(
                    "flg"=>"",
                    "update"=>$ymdhis,
                    "entry"=>$entry,
                    "id"=>$k2,
                    "name"=>$v2["name"]
                ));
                $data = preg_replace_callback('/\\\\u([0-9a-zA-Z]{4})/',function($m){return mb_convert_encoding(pack('H*',$m[1]),'UTF-8','UTF-16');},$data);
                $json.= $data."\n";
            }
            //echo $k1."\n";
            //print_r($json);
            file_put_contents($filePath,$json);
        }
        //exit();
        //$item_id = $_REQUEST['items']['id'];
        /*
        //items.json
        //$itemsJson = $torisetsu->getOne("items.json",$id);
        $data=array(
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
        */
    }

    function getMasterLists($jsonFile){
        $file = "data/contents/".$jsonFile;
        if(!is_file($file)){return;}

        $datas = explode("\n",file_get_contents($file));
        $FLG=array();
        $res=array();
        for($i=(count($datas)-1);$i>=0;$i--){
            if(!$datas[$i]){continue;}
            $json = json_decode($datas[$i],true);
            if(isset($FLG[$json['id']])){continue;}
            $FLG[$json['id']]=1;
            if($json['flg'] || !isset($json['flg'])){continue;}

            $res[] = $json;
        }
        //array_reverse($res);
        return array_reverse($res);
    }
    function getMasterHTML($jsonFile){
        $datas = $this->getMasterLists($jsonFile);

        $tpl = file_get_contents("plugins/contents/template/mastermente.html");

        $html="";
        //$key = str_replace(".json","",$jsonFile);

        $num=0;
        //for($i=(count($datas)-1);$i>=0;$i--){
        for($i=0;$i<count($datas);$i++){
            $num++;
            $data = array(
                "id"=>$datas[$i]['id'],
                "num"=>$num,
                "file"=>$jsonFile,
                "name"=>$datas[$i]['name']
            );

            $html.= $this->setTemplate($tpl,$data);
            // $html.= '<div class="input-group">';
            // $html.= '<span class="input-group-addon">'.$num.'</span>';
            // $html.= '<input class="form-control" type="text" name="'.$jsonFile.'[name]['.$datas[$i]['id'].']" value="'.$datas[$i]['name'].'">';
            // $html.= '</div>';
        }

        $data = array(
            "id"=>uniqid(),
            "num"=>"+",
            "file"=>$jsonFile,
            "name"=>""
        );

        $html.= $this->setTemplate($tpl,$data);

        //new
        // $html.= '<div class="input-group">';
        // $html.= '<span class="input-group-addon">+</span>';
        // $html.= '<input class="form-control" type="text" name="'.$jsonFile.'[name]['.uniqid().']">';
        // $html.= '</div>';

        return $html;
    }

    function setTemplate($tpl,$data){
        foreach($data as $key=>$val){
            $tpl = str_replace("<%".$key."%>",$val,$tpl);
        }
        return $tpl;
    }
}
