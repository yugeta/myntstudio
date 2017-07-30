(function(){
	var $$ = function(){
		this.setEvent(window , "DOMContentLoaded" , $$.prototype.set);
	};

	$$.prototype.set = function(){
		var fileNameElm = document.getElementById("fileName");
		if(fileNameElm === null){return;}


		// // select-value
		// var urlData = $$.prototype.urlinfo();
		// if(typeof(urlData.query.file) !== -1 || urlData.query.file){
		// // 	document.getElementById("fileNameArea").style.setProperty("display","block","");
		// // }
		// // else{
		// 	fileNameElm.value = urlData.query.file;
		// }

		// url-change
		fileNameElm.onchange = $$.prototype.changeSelect;

		// // img-button
		// $$.prototype.setImageButton();

		// add-tag
		$$.prototype.setEvent_addTag();
	};

	$$.prototype.changeSelect = function(event){console.log(+new Date())
		var target = event.target;
		var urlData = $$.prototype.urlinfo();
		var url = urlData.url+"?system="+urlData.query.system+"&file="+target.value;
		// console.log(url);
		// alert(url);
		location.href = url;
	};

	$$.prototype.urlinfo=function(uri){
		if(!uri){uri = location.href;}
		var data={};
		//URLとクエリ分離分解;
		var query=[];
		if(uri.indexOf("?")!=-1){query = uri.split("?")}
		else if(uri.indexOf(";")!=-1){query = uri.split(";")}
		else{
			query[0] = uri;
			query[1] = '';
		}
		//基本情報取得;
		var sp = query[0].split("/");
		var data={
			url:query[0],
			dir:$$.prototype.pathinfo(uri).dirname,
			domain:sp[2],
			protocol:sp[0].replace(":",""),
			query:(query[1])?(function(q){
				var data=[];
				var sp = q.split("&");
				for(var i=0;i<sp .length;i++){
					var kv = sp[i].split("=");
					if(!kv[0]){continue}
					data[kv[0]]=kv[1];
				}
				return data;
			})(query[1]):[],
		};
		return data;
	};
	$$.prototype.pathinfo = function(p){
		var basename="",
		    dirname=[],
				filename=[],
				ext="";
		var p2 = p.split("?");
		var urls = p2[0].split("/");
		for(var i=0; i<urls.length-1; i++){
			dirname.push(urls[i]);
		}
		basename = urls[urls.length-1];
		var basenames = basename.split(".");
		for(var i=0;i<basenames.length-1;i++){
			filename.push(basenames[i]);
		}
		ext = basenames[basenames.length-1];
		return {
			"hostname":urls[2],
			"basename":basename,
			"dirname":dirname.join("/"),
			"filename":filename.join("."),
			"extension":ext,
			"query":(p2[1])?p2[1]:"",
			"path":p2[0]
		};
	};

	$$.prototype.setEvent = function(target, mode, func){
		//other Browser
		if (target.addEventListener){target.addEventListener(mode, func, false)}
		else{target.attachEvent('on' + mode, function(){func.call(target , window.event)})}
	};

	$$.prototype.setImageButton = function(){
		$$ajax.prototype.set({
			url:$$.prototype.pathinfo(location.href).path,
			query:{
				method   : "MYNT_PAGE/getTemplateFile",
				filePath : "system/page/pageEdit_getImage.html"
			},
			method:"POST",
			async:true,
			onSuccess:$$.prototype.setImageDialog_temp
		});
	};

	$$.prototype.setImageDialog_temp = function(res){
		var bg = document.createElement("div");
		bg.className = "ImageDialog-bg";
		document.body.appendChild(bg);
		bg.innerHTML = res;
		// bg.onclick = function(){
		// 	var prop_bg = document.getElementsByClassName("ImageDialog-bg");
		// 	if(prop_bg.length > 0){
		// 		prop_bg[0].parentNode.removeChild(prop_bg[0]);
		// 	}
		// };

		// close-button
		var closeDialog = document.getElementById("closeDialog");
		closeDialog.onclick = $$.prototype.setEvent_removeImageDialog;

		// images-click-proc
		var pics = document.getElementsByClassName("pictures");

		for(var i=0; i<pics.length; i++){
			pics[i].onclick = $$.prototype.setEvent_picsClick;
		}
	};
	$$.prototype.setEvent_removeImageDialog = function(){
		var prop_bg = document.getElementsByClassName("ImageDialog-bg");
		if(prop_bg.length > 0){
			prop_bg[0].parentNode.removeChild(prop_bg[0]);
		}
	};
	$$.prototype.setEvent_picsClick = function(event){
		var target = event.target;
		if(!target){return}
// console.log(target.className);
		var img;
		if(target.tagName === "IMG"){
			img = target;
		}
		else if(target.tagName === "DIV"){
			var imgs = target.getElementsByTagName("img");
			if(!imgs.length){return}
			img = imgs[0];
		}
		else{
			return;
		}

		// hidden dialog
		$$.prototype.setEvent_removeImageDialog();

		var id  = img.getAttribute("data-id");
		var ext = img.getAttribute("data-ext");
		$$.prototype.setEvent_selectImage(id,ext);
	};

	$$.prototype.setEvent_selectImage = function(id,ext){

		var word = "<img src='data/picture/"+id+"."+ext+"' data-id='"+id+"' alt='' />";

		var textarea = document.getElementById('source');

		// add-textarea
		var sentence = textarea.value;//全部文字
		var len      = sentence.length;//文字全体のサイズ
		var pos      = textarea.selectionStart;//選択している最初の位置

		var before   = sentence.substr(0, pos);
		// var word     = '挿入したい文字列';
		var after    = sentence.substr(pos, len);

		sentence = before + word + after;

		textarea.value = sentence;
		// console.log(sentence);

	};


	/**
	* Ajax
	* $$ajax.prototype.set({
	* url:"",					// "http://***"
	* method:"POST",	// POST or GET
	* async:true,			// true or false
	* data:{},				// Object
	* query:{},				// Object
	* querys:[]				// Array
	* });
	*/
	var $$ajax = function(){};
	$$ajax.prototype.dataOption = {
		url:"",
		query:{},				// same-key Nothing
		querys:[],			// same-key OK
		data:{},				// ETC-data event受渡用
		async:"true",		// [trye:非同期 false:同期]
		method:"POST",	// [POST / GET]
		type:"application/x-www-form-urlencoded", // [text/javascript]...
		onSuccess:function(res){},
		onError:function(res){}
	};
	$$ajax.prototype.option = {};
	$$ajax.prototype.createHttpRequest = function(){
		//Win ie用
		if(window.ActiveXObject){
			//MSXML2以降用;
			try{return new ActiveXObject("Msxml2.XMLHTTP")}
			catch(e){
				//旧MSXML用;
				try{return new ActiveXObject("Microsoft.XMLHTTP")}
				catch(e2){return null}
			}
		}
		//Win ie以外のXMLHttpRequestオブジェクト実装ブラウザ用;
		else if(window.XMLHttpRequest){return new XMLHttpRequest()}
		else{return null}
	};
	// XMLHttpRequestオブジェクト生成
	$$ajax.prototype.set = function(options){
		if(!options){return}
		var ajax = new $$ajax;
		var httpoj = $$ajax.prototype.createHttpRequest();
		if(!httpoj){return;}
		// open メソッド;
		var option = ajax.setOption(options);
		// 実行
		httpoj.open( option.method , option.url , option.async );
		// type
		httpoj.setRequestHeader('Content-Type', option.type);
		// onload-check
		httpoj.onreadystatechange = function(){
			//readyState値は4で受信完了;
			if (this.readyState==4){
				//コールバック
				option.onSuccess(this.responseText);
			}
		};
		//query整形
		var data = ajax.setQuery(option);
		//send メソッド
		if(data.length){
			httpoj.send(data.join("&"));
		}
		else{
			httpoj.send();
		}
	};
	$$ajax.prototype.setOption = function(options){
		var option = {};
		for(var i in this.dataOption){
			if(typeof options[i] != "undefined"){
				option[i] = options[i];
			}
			else{
				option[i] = this.dataOption[i];
			}
		}
		return option;
	};
	$$ajax.prototype.setQuery = function(option){
		var data = [];
		if(typeof option.query != "undefined"){
			for(var i in option.query){
				data.push(i+"="+encodeURIComponent(option.query[i]));
			}
		}
		if(typeof option.querys != "undefined"){
			for(var i=0;i<option.querys.length;i++){
				if(typeof option.querys[i] == "Array"){
					data.push(option.querys[i][0]+"="+encodeURIComponent(option.querys[i][1]));
				}
				else{
					var sp = option.querys[i].split("=");
					data.push(sp[0]+"="+encodeURIComponent(sp[1]));
				}
			}
		}
		return data;
	};

	$$.prototype.pathinfo = function(p){
		var basename="",
		    dirname=[],
				filename=[],
				ext="";
		var p2 = p.split("?");
		var urls = p2[0].split("/");
		for(var i=0; i<urls.length-1; i++){
			dirname.push(urls[i]);
		}
		basename = urls[urls.length-1];
		var basenames = basename.split(".");
		for(var i=0;i<basenames.length-1;i++){
			filename.push(basenames[i]);
		}
		ext = basenames[basenames.length-1];
		return {
			"hostname":urls[2],
			"basename":basename,
			"dirname":dirname.join("/"),
			"filename":filename.join("."),
			"extension":ext,
			"query":(p2[1])?p2[1]:"",
			"path":p2[0]
		};
	};

	$$.prototype.setEvent_addTag = function(){
		var addTag = document.getElementsByClassName("addTag");
		for(var i=0; i<addTag.length; i++){
			addTag[i].onclick = $$.prototype.setEvent_addTag_click;
		}
	};
	$$.prototype.setEvent_addTag_click = function(event){
		var target = event.target;
		if(!target){return}
		// console.log(target.textContent);
		var tag = $$.prototype.trim(target.textContent);
		switch(tag){
			case "img":
				$$.prototype.setImageButton();
				break;
			case "a":
				$$.prototype.setEvent_addTag_proc(tag+" href='' target='_blank'",tag,"");
				break;
			case "hr":
				$$.prototype.setEvent_addTag_proc(tag,"","");
				break;
			case "form":
				$$.prototype.setEvent_addTag_proc(tag+" method='post' action=''",tag,"\n");
				break;
			case "text":
				$$.prototype.setEvent_addTag_proc("input type='text' name='' value=''","","");
				break;
			case "hidden":
				$$.prototype.setEvent_addTag_proc("input type='hidden' name='' value=''","","");
				break;
			case "radio":
				$$.prototype.setEvent_addTag_proc("input type='radio' name='' value=''","","");
				break;
			case "checkbox":
				$$.prototype.setEvent_addTag_proc("input type='checkbox' name='' value=''","","");
				break;
			case "select":
				$$.prototype.setEvent_addTag_proc(tag+" name=''",tag,"\n");
				break;
			case "option":
				$$.prototype.setEvent_addTag_proc(tag+" value=''",tag,"");
				break;
			case "button":
				$$.prototype.setEvent_addTag_proc("input type='button' name='' value=''","","");
				break;
			case "submit":
				$$.prototype.setEvent_addTag_proc("input type='submit' name='' value=''","","");
				break;
			case "table+":
				$$.prototype.setEvent_addTag_proc("table","table","\n<tr>\n<th></th>\n</tr>\n<tr>\n<td></td>\n</tr>\n\n");
				break;
			case "ul+":
				$$.prototype.setEvent_addTag_proc("ul","ul","\n<li></li>\n\n");
				break;
			case "ol+":
				$$.prototype.setEvent_addTag_proc("ol","ol","\n<li></li>\n\n");
				break;
			case "dl+":
				$$.prototype.setEvent_addTag_proc("dl","dl","\n<dt></dt>\n<dd></dd>\n\n");
				break;
			// case "table":
			// 	var str = "\n<tr>\n<td></td>\n</tr>\n";
			// 	$$.prototype.setEvent_addTag_proc(target.textContent,target.textContent,str);
			// 	break;

			// case "ul":
			// 	$$.prototype.setEvent_addTag_proc(target.textContent,target.textContent,"\n<li></li>\n");
			// 	break;

			default:
				$$.prototype.setEvent_addTag_proc(tag,tag,"");
				break;
		}
	};
	$$.prototype.setEvent_addTag_proc = function(tag1,tag2,str1){
		var textarea = document.getElementById('source');

		// add-textarea
		var sentence = textarea.value;//全部文字
		var len      = sentence.length;//文字全体のサイズ
		var pos      = textarea.selectionStart;//選択している最初の位置

		var before   = sentence.substr(0, textarea.selectionStart);

		var after    = sentence.substr(textarea.selectionEnd, len);
		var str2      = sentence.substr(textarea.selectionStart , (textarea.selectionEnd - textarea.selectionStart));

		var word = "";

		var str = str1 + str2;

		if(tag1 && tag2){
			word = "<"+tag1+">"+str+"</"+tag2+">";
		}
		else if(tag1 && tag2 === ""){
			word = "<"+tag1+">";
		}

		// var strLen = textarea.selectionEnd +

		sentence = before + word + after;

		// console.log(textarea.selectionStart +"/"+ textarea.selectionEnd);

		textarea.value = sentence;
	};

	$$.prototype.trim = function(txt){
		if(!txt){return txt}
		if(typeof(txt)!=="string"){txt = txt.toString()}

		//&nbsp;文字列対策
		var nbsp = String.fromCharCode(160);//&nbsp;
		if(txt!="" && txt.indexOf(nbsp)!=-1){txt = txt.split(nbsp).join(' ');}

		//改行排除
		txt = txt.replace(/\r/g,'');
		txt = txt.replace(/\n/g,'');
		txt = txt.replace(/^\t/g,'');
		txt = txt.replace(/\t$/g,'');

		//文頭、文末のTRIM
		txt = txt.replace(/^ /g,'');
		txt = txt.replace(/ $/g,'');

		return txt;
	};

	new $$();

})();
