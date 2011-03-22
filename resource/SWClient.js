/*
 * Pichu
 */
 

/*
 * 語系檔讀取
 */
var _LANGLoader = function(data){
	language = {};
	
	$.ajax({async:false,
			dataType:'json',
			url:DEFINES['PHP_SELF'] + "?mode=lang",
			success:function(data){
			//	alert(data);
			//	alert(typeof(language));
				language = data;	

			},
			statusCode:{
				302:function(){alert('xd');}
				
			}
			});
	this.getLanguage = function(){return language};
}
 
 
var _SWClient = function(data){
	var LANGLoader = new _LANGLoader;
	var language = LANGLoader.getLanguage();
	this.init = function(){
		//alert('init');
		$(".threads").append(_mkTHREAD({NO:3}));
	//	$(".threads > li#r3").css('background','#ACAC0F');
		$(".threads > li#r3").append(_mkREPLY({NO:3}));
		
		$(".threads").append(_mkTHREAD({NO:4}));
	}
	var _mkForm = function(){
		$('#POSTFORM').attr('action',DEFINES['PHP_SELF']);

	}
	var _STEReplace = function(replaceArray,data){
		$.each(replaceArray,function(i,v){
			data = data.replace(new RegExp("{\\" + i + "}","g"),v);
		});
		return data;
	}
	
	
	var _mkTHREAD = function(data){
	    if('undefined' == typeof(data)){data ={};}
	    if('undefined' == typeof(data.NO)){data.NO = 0 ;}
		if('undefined' == typeof(data.SUB)){data.SUB = "" ;}
		if('undefined' == typeof(data.NAME)){data.NAME = "" ;}
		if('undefined' == typeof(data.NOW)){data.NOW = "" ;}
		if('undefined' == typeof(data.CATEGORY)){data.CATEGORY = "" ;}
		if('undefined' == typeof(data.IMG_BAR)){data.IMG_BAR = "" ;}
		if('undefined' == typeof(data.IMG_SRC)){data.IMG_SRC = "" ;}
		if('undefined' == typeof(data.WARN_BEKILL)){data.WARN_BEKILL = "" ;}
		if('undefined' == typeof(data.NAME_TEXT)){data.NAME_TEXT = "" ;}
		if('undefined' == typeof(data.NOW)){data.NOW = "" ;}
		if('undefined' == typeof(data.NOW)){data.NOW = "" ;}
		_THREAD = '<li class="threadpost" id="r{$NO}">{$IMG_BAR}($IMG_SRC}' +
				'<input type="checkbox" name="{$NO}" value="delete" />' +
				'<span class="title">{$SUB}</span>{$NAME_TEXT}<span class="name">{$NAME}</span> [{$NOW}] {$QUOTEBTN}&nbsp;{$REPLYBTN}' +
				'<div class="quote">{$COM}</div>{$WARN_OLD}{$WARN_BEKILL}{$WARN_ENDREPLY}{$WARN_HIDEPOST}<ul class="reply"></ul></li>';
		/*
		array('{$NO}'=>$no, '{$SUB}'=>$sub, '{$NAME}'=>$name, '{$NOW}'=>$now, '{$CATEGORY}'=>$category, '{/li}'=>/li, '{$IMG_BAR}'=>$IMG_BAR, '{$IMG_SRC}'=>$imgsrc, '{$WARN_BEKILL}'=>$WARN_BEKILL, '{$NAME_TEXT}'=>_T('post_name'), '{$CATEGORY_TEXT}'=>_T('post_category'), '{$SELF}'=>PHP_SELF, '{$COM}'=>$com);
		*/
		_THREAD = _STEReplace({
								$NO 	  :data.NO,
								$SUB	  :data.SUB,
								$NAME     :data.NAME,
								
								$NAME_TEXT:language['post_name'],
								$REPLYBTN :language['reply_btn']
								},
								_THREAD);
		return _THREAD;
	}
	
	var _mkREPLY = function(data){
	    if('undefined' == typeof(data)){data ={};}
	    if('undefined' == typeof(data.NO)){data.NO = 0 ;}
		if('undefined' == typeof(data.SUB)){data.SUB = "" ;}
		if('undefined' == typeof(data.SUB)){data.SUB = "" ;}
		if('undefined' == typeof(data.SUB)){data.SUB = "" ;}
		

		_THREAD = '<li class="reply" id="r{$NO}"><input type="checkbox" name="{$NO}" value="delete" /><span class="title">{$SUB}</span> {$NAME_TEXT}<span class="name">{$NAME}</span> [{$NOW}] {$QUOTEBTN}<div class="quote">{$COM}</div>{$WARN_BEKILL}</li>';
		
		_THREAD = _STEReplace({
								$NO 	  :data.NO,
								
								$NAME_TEXT:language['post_name'],
								$REPLYBTN :language['reply_btn']
								},
								_THREAD);
		return _THREAD;
	}
	
	var _mkReplyBtn = function(data){
		_DATA = '[' + '<a href="' + DEFINES['PHP_SELF']  + '?res="></a>' + ']';

	}	

};

