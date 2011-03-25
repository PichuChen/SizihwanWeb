/*
 * Pichu
 */
 

/*
 * 語系檔讀取
 */
var _LANGLoader = function(data){
	language = {};
	
	$.ajax({//async:false,
			dataType:'json',
			url:"../../main.php/" + DEFINES['BOARD'] + "/LANG",
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
//	var LANGLoader = new _LANGLoader;
//	var language = LANGLoader.getLanguage();
	var $threads;
	this.init = function(){
		//alert('init');
		$threads = $(".threads");
//		$threads.append(_mkTHREAD({NO:3}));
	//	$(".threads > li#r3").css('background','#ACAC0F');
//		$(".threads > li#r3").append(_mkREPLY({NO:3}));
		
//		$threads.append(_mkTHREAD({NO:4}));
		this.getPage();
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
	this.getPage = function(data){
	    if('undefined' == typeof(data)){data ={};}
	    if('undefined' == typeof(data.pageNo)){data.pageNo = 1 ;}
		$.ajax({
				dataType:'json',
				url:"../../main.php/" + DEFINES['BOARD'] + "/SHOW/" + data.pageNo,
				statusCode:{
					200:function(data){
						$.each(data,function(i,v){
							if(v['resto'] == '0'){//首PO
								$threads.append(_mkTHREAD(v));
							}else{//回應
								$threads.find(' > li#r' + v['resto']).append(_mkREPLY(v));
							}
						});
						
					},
					501:function(){alert('尚未支援');}
					
				}
				});
	}
	
	var _mkTHREAD = function(data){
	    if('undefined' == typeof(data)){data ={};}
	    if('undefined' == typeof(data.no)){data.no = 0 ;}
		if('undefined' == typeof(data.sub)){data.sub = "" ;}
		if('undefined' == typeof(data.name)){data.name = "" ;}
		if('undefined' == typeof(data.now)){data.now = "" ;}
		if('undefined' == typeof(data.category)){data.category = "" ;}
		if('undefined' == typeof(data.img_bar)){data.img_bar = "" ;}
		if('undefined' == typeof(data.img_src)){data.img_src = "" ;}
		if('undefined' == typeof(data.warn_bekill)){data.warn_bekill = "" ;}
		if('undefined' == typeof(data.name_text)){data.name_text = "" ;}
		if('undefined' == typeof(data.now)){data.now = "" ;}
		if('undefined' == typeof(data.now)){data.now = "" ;}
		_THREAD = '<li class="threadpost" id="r{$NO}">{$IMG_BAR}{$IMG_SRC}' +
				'<input type="checkbox" name="{$NO}" value="delete" />' +
				'<span class="title">{$SUB}</span>{$NAME_TEXT}<span class="name">{$NAME}</span> [{$NOW}] {$QUOTEBTN}&nbsp;{$REPLYBTN}' +
				'<div class="quote">{$COM}</div>{$WARN_OLD}{$WARN_BEKILL}{$WARN_ENDREPLY}{$WARN_HIDEPOST}<ul class="reply"></ul><hr/></li>';
		/*
		array('{$NO}'=>$no, '{$SUB}'=>$sub, '{$NAME}'=>$name, '{$NOW}'=>$now, '{$CATEGORY}'=>$category, '{/li}'=>/li, '{$IMG_BAR}'=>$IMG_BAR, '{$IMG_SRC}'=>$imgsrc, '{$WARN_BEKILL}'=>$WARN_BEKILL, '{$NAME_TEXT}'=>_T('post_name'), '{$CATEGORY_TEXT}'=>_T('post_category'), '{$SELF}'=>PHP_SELF, '{$COM}'=>$com);
		*/
		_IMG_SRC = '<a rel="_blank" href="src/{$TIM}{$EXT}" target="_blank"><img title="{$IMG_SIZE}" alt="{$IMG_SIZE}" class="img" style="width: {$TW}px; height: {$TH}px;" src="thumb/{$TIM}s.jpg"></a>';
		_IMG_SRC = _STEReplace({
								$TIM	  :data.tim,
								$EXT	  :data.ext,
								$IMG_SIZE :data.imgsize,
								$TW       :data.tw,
								$TH       :data.th
								
								},
								_IMG_SRC);
		_IMG_BAR = '<a rel="_blank" href="src/{$TIM}{$EXT}" target="_blank">{$TIM}{$EXT}</a>-({$IMG_SIZE}, {$IMGW}x{$IMGH}) <small>{$IMG_SIMPLE}</small>';
		
		
		_IMG_BAR = _STEReplace({
								$TIM	  :data.tim,
								$EXT	  :data.ext,
								$IMG_SIZE :data.imgsize,
								$TW       :data.tw,
								$TH       :data.th,
								$IMGW	  :data.imgw,
								$IMGH	  :data.imgh,
								$IMG_SIMPLE:language['img_sample']
								
								},
								_IMG_BAR);
		
		_THREAD = _STEReplace({
								$NO 	  :data.no,
								$SUB	  :data.sub,
								$NAME     :data.name,
								$NOW      :data.now,
								$IMG_SRC  :(data.ext == '' ? '': _IMG_SRC),
								$IMG_BAR  :(data.ext == '' ? '': _IMG_BAR),
								$COM	  :data.com,	
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

