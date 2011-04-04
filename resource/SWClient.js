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
	var _IMG_SRC = '<a rel="_blank" href="src/{$TIM}{$EXT}" target="_blank"><img title="{$IMG_SIZE}" alt="{$IMG_SIZE}" class="img" style="width: {$TW}px; height: {$TH}px;" src="thumb/{$TIM}s.jpg"></a>';
	var _IMG_BAR = '<a rel="_blank" href="src/{$TIM}{$EXT}" target="_blank">{$TIM}{$EXT}</a>-({$IMG_SIZE}, {$IMGW}x{$IMGH}) <small>{$IMG_SIMPLE}</small>';
		
		
	
	
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
							$.each(v,function(vin,vvn){
								if(vvn['resto'] == '0'){//首PO
									$threads.append(_mkTHREAD(vvn));
								}else{
									 $threads.find(' > li#r' + vvn['resto'] + ' ul.reply').append(_mkREPLY(vvn));
								}
												


							});
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
		if('undefined' == typeof(data.com)){data.com = "" ;}
		if('undefined' == typeof(data.img_bar)){data.img_bar = "" ;}
		if('undefined' == typeof(data.img_src)){data.img_src = "" ;}
		if('undefined' == typeof(data.warn_bekill)){data.warn_bekill = "" ;}
		if('undefined' == typeof(data.name_text)){data.name_text = "" ;}
		_THREAD = '<li class="threadpost" id="r{$NO}">{$IMG_BAR}{$IMG_SRC}<br/>' +
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
		
		_QUOTEBTN = '<a href="main.php?res=2&amp;page_num=all#r' + data.no + '" class="qlink">No.' + data.no + '</a>';		
		_REQLYBTN = '[<a href="main.php?res=' + data.no + '">' + language['reply_btn'] + '</a>]';
		_WARN_OLD = '<span class="warn_txt">' + language['warn_oldthread'] + '</span><br />';
		_WARN_BEKILL='<span class="warn_txt">' + language['warn_sizelimit'] + '</span><br />';
		_WARN_ENDREPLY= '<span class="warn_txt">' + language['warn_locked'] + '</span><br />';
		_WARN_HIDEPOST= '<span class="warn_txt2">' + language['notice_omitted'].replace("%1$s",/*$hiddenReply*/ 3) + '</span><br />';
		$hiddenReply = data.hiddenreply;
		_THREAD = _STEReplace({
								$NO 	  :data.no,
								$SUB	  :data.sub,
								$NAME     :data.name,
								$NOW      :data.now,
								$IMG_SRC  :(data.ext == '' ? '': _IMG_SRC),
								$IMG_BAR  :(data.ext == '' ? '': _IMG_BAR),
								$COM	  :data.com,	
								$QUOTEBTN :_QUOTEBTN,
								$REPLYBTN :_REQLYBTN,
								$NAME_TEXT:language['post_name'],
								$WARN_OLD :_WARN_OLD,
								$WARN_BEKILL:_WARN_BEKILL,
								$WARN_ENDREPLY:_WARN_ENDREPLY,
								$WARN_HIDEPOST:_WARN_HIDEPOST
								},
								_THREAD);
		return _THREAD;
	}
	
	var _mkREPLY = function(data){
	    if('undefined' == typeof(data)){data ={};}
	    if('undefined' == typeof(data.no)){data.no = 0 ;}
		if('undefined' == typeof(data.sub)){data.sub = "" ;}
		if('undefined' == typeof(data.name)){data.name = "" ;}
		if('undefined' == typeof(data.now)){data.now = "" ;}
		if('undefined' == typeof(data.com)){data.com = "" ;}		
		if('undefined' == typeof(data.warn_bekill)){data.warn_bekill = "" ;}
		if('undefined' == typeof(data.name_text)){data.name_text = "" ;}
		



/*
<div class="reply" id="r{$NO}">
<input type="checkbox" name="{$NO}" value="delete" onclick="boxclicked=1;" /><span class="title">{$SUB}</span> {$NAME_TEXT}<span class="name">{$NAME}</span> [{$NOW}] {/li}&nbsp;
<!--&IF($IMG_BAR,'<br />&nbsp;','')-->{$IMG_BAR} {$IMG_SRC}
{$WARN_BEKILL}<div class="quote">{$COM}</div>
<!--&IF($CATEGORY,'<div class="category">{$CATEGORY_TEXT}{$CATEGORY}</div>','')-->
</div>
*/				
		_IMG_SRC = '<a rel="_blank" href="src/{$TIM}{$EXT}" target="_blank"><img title="{$IMG_SIZE}" alt="{$IMG_SIZE}" class="img" style="width: {$TW}px; height: {$TH}px;" src="thumb/{$TIM}s.jpg"></a>';
		$IMG_SRC = _STEReplace({
								$TIM	  :data.tim,
								$EXT	  :data.ext,
								$IMG_SIZE :data.imgsize,
								$TW       :data.tw,
								$TH       :data.th
								
								},
								_IMG_SRC);
		_IMG_BAR = '<a rel="_blank" href="src/{$TIM}{$EXT}" target="_blank">{$TIM}{$EXT}</a>-({$IMG_SIZE}, {$IMGW}x{$IMGH}) <small>{$IMG_SIMPLE}</small>';
		
		
		$IMG_BAR = _STEReplace({
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

		_THREAD = '<li class="reply" id="r{$NO}">' +
				  '<input type="checkbox" name="{$NO}" value="delete" />' +
				  '<span class="title">{$SUB}</span>' +
				  '{$NAME_TEXT} ' +
				  '<span class="name">{$NAME}</span>' +
				  '[{$NOW}] ' +
				  (($IMG_BAR != '') ? '<br/>&nbsp' : '' ) +
				  '{$IMG_BAR} {$IMG_SRC}' +
				  '<div class="quote">{$COM}</div>' +
				  (($IMG_BAR != '') ? '<div class="category">{$CATEGORY_TEXT}{$CATEGORY}</div>' : '' ) +
				  '{$WARN_BEKILL}</li>';

		_THREAD = _STEReplace({
								$NO 	  :data.no,
								$SUB	  :data.sub,
								$NAME     :data.name,
								$NOW      :data.now,
								$IMG_SRC  :(data.ext == '' ? '': _IMG_SRC),
								$IMG_BAR  :(data.ext == '' ? '': _IMG_BAR),
								$COM	  :data.com,	
								$QUOTEBTN :_QUOTEBTN,
								$REPLYBTN :_REQLYBTN,
								$NAME_TEXT:language['post_name'],
								$WARN_OLD :_WARN_OLD,
								$WARN_BEKILL:_WARN_BEKILL,
								$WARN_ENDREPLY:_WARN_ENDREPLY,
								$WARN_HIDEPOST:_WARN_HIDEPOST
								},
								_THREAD);
		return _THREAD;
	}
	
	var _mkReplyBtn = function(data){
		_DATA = '[' + '<a href="' + DEFINES['PHP_SELF']  + '?res="></a>' + ']';

	}	

};

