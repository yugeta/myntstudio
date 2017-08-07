<?php

class MYNT_SEARCH{
  public function article_search($search_text = ""){
    $MYNT_BLOG = new MYNT_BLOG;

    $lists = $MYNT_BLOG->getArticleLists("release");

    // search-text
    $result = array();
    for($i=0,$c=count($lists); $i<$c; $i++){
      if($this->checkArticleString($lists[$i] , $search_text)){
        $result[] = $lists[$i];
      }
    }

    return $result;
  }
	public function group_search($group_text = ""){
		$MYNT_BLOG = new MYNT_BLOG;

    $lists = $MYNT_BLOG->getArticleLists("release");

    // search-text
    $result = array();
    for($i=0,$c=count($lists); $i<$c; $i++){
			$info = json_decode(file_get_contents("data/page/blog/".$lists[$i]),true);
      if($info["group"] == $group_text){
        $result[] = $lists[$i];
      }
    }

    return $result;
	}

  public function article_search_li($search_text = ""){
    $MYNT_BLOG = new MYNT_BLOG;

		$result = array();

		// text-search
		if($search_text !== "" || isset($_REQUEST["search"])){
			if(isset($_REQUEST["search"])){
	      $search_text = $_REQUEST["search"];
	    }

	    // search-text
	    $result = $this->article_search($search_text);
		}
		else if(isset($_REQUEST["group"])){
			// search-text
	    $result = $this->group_search($_REQUEST["group"]);
		}


    // make-html
    $html = "";
    $tmpSource = $MYNT_BLOG->getBlogSource();
		for($i=0,$c=count($result); $i<$c; $i++){
			$json = $MYNT_BLOG->getPageInfoFromPath($MYNT_BLOG->default_article_dir.$result[$i]);
			$html .= $MYNT_BLOG->setBlogSourceReplace($tmpSource, $json);
      // $html .= $MYNT_BLOG->default_article_dir.$result[$i]."<br>".PHP_EOL;
		}

		$MYNT_VIEW = new MYNT_VIEW;
		return $MYNT_VIEW->conv($html);
  }
  public function article_search_li_ajax($search_text = ""){
    if($search_text === "" && isset($_REQUEST["search"])){
      $search_text = $_REQUEST["search"];
    }
    echo $this->article_search_li($search_text);
    exit();
  }

  // public function getAllArticle($status){
  //   $MYNT_BLOG = new MYNT_BLOG;
  //   return $MYNT_BLOG->getArticleLists($status);
  // }

  public function checkArticleString($infoPath , $text){

    $text = $this->setExpText($text);

    $info = json_decode(file_get_contents("data/page/blog/".$infoPath),true);

    if(isset($info["title"]) && preg_match("/".$text."/is", $info["title"])){
      return true;
    }
    // else if(isset($info["group"]) && preg_match("/".$text."/is", $info["group"])){
    //   return true;
    // }
    else if(isset($info["tag"]) && preg_match("/".$text."/is", $info["tag"])){
      return true;
    }

    $htmlPath = preg_replace("/\.info$/",".html",$infoPath);

    if(is_file("data/page/blog/".$htmlPath)){
      $html = file_get_contents("data/page/blog/".$htmlPath);
      if($html && preg_match("/".$text."/is", $html)){
        return true;
      }
    }

    return false;
  }

  public function setExpText($text){
    return $text;
  }
}
