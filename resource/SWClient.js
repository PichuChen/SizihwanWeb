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

	var _mkTHREAD = function(data){
		_THREAD = '<li class="threadpost" id="r{$NO}">{$IMG_BAR}($IMG_SRC}' +
				'<input type="checkbox" name="{$NO}" value="delete" />' +
				'<span class="title">{$SUB}</span>{$NAME_TEXT}<span class="name">{$NAME}</span> [{$NOW}] {$QUOTEBTN}&nbsp;{$REPLYBTN}' +
				'<div class="quote">{$COM}</div>{$WARN_OLD}{$WARN_BEKILL}{$WARN_ENDREPLY}{$WARN_HIDEPOST}<ul class="reply"></ul></li>';
		_replaceArray = {
						$NO 	  :data.NO,
						$NAME_TEXT:language['post_name'],
						$REPLYBTN :language['reply_btn']
						};
		$.each(_replaceArray,function(i,v){
	//		alert(i + "," + v);
			_THREAD = _THREAD.replace(new RegExp("{\\" + i + "}","g"),v);
		});
		// _THREAD = _THREAD.replace(/{\$NO}/g,data.NO)
				 // .replace(/{\$NAME_TEXT}/g,language['post_name'])
				 // .replace(/{\$REPLYBTN}/g,language['reply_btn']);
	//	alert(_THREAD);
		return _THREAD;
	}
	
	var _mkREPLY = function(data){
	    if('undefined' == typeof(data)){data ={};}
	    if('undefined' == typeof(data.NO)){data.NO = 0 ;}
		if('undefined' == typeof(data.SUB)){data.SUB = "" ;}
		if('undefined' == typeof(data.SUB)){data.SUB = "" ;}
		if('undefined' == typeof(data.SUB)){data.SUB = "" ;}
		

		_THREAD = '<li class="reply" id="r{$NO}"><input type="checkbox" name="{$NO}" value="delete" /><span class="title">{$SUB}</span> {$NAME_TEXT}<span class="name">{$NAME}</span> [{$NOW}] {$QUOTEBTN}<div class="quote">{$COM}</div>{$WARN_BEKILL}</li>';
		_THREAD = _THREAD.replace(/{\$NO}/g,data.NO)
				 .replace(/{\$SUB}/g,language['post_name']);

		return _THREAD;
	}
	
	var _mkReplyBtn = function(data){
		_DATA = '[' + '<a href="' + DEFINES['PHP_SELF']  + '?res="></a>' + ']';

	}	

};

