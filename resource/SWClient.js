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
	var LANGLoader = new _LANGLoader;
	var language = LANGLoader.getLanguage();
	var $threads;
	var _IMG_SRC = '<a rel="_blank" href="src/{$TIM}{$EXT}" target="_blank"><img title="{$IMG_SIZE}" alt="{$IMG_SIZE}" class="img" style="width: {$TW}px; height: {$TH}px;" src="thumb/{$TIM}s.jpg"></a>';
	var _IMG_BAR = '<a rel="_blank" href="src/{$TIM}{$EXT}" target="_blank">{$TIM}{$EXT}</a>-({$IMG_SIZE}, {$IMGW}x{$IMGH}) <small>{$IMG_SIMPLE}</small>';
	var _QUOTEBTN = '<a href="index.html?res={$NO}#r{$NO}" class="qlink">No.{$NO}</a>';		
	var	_REQLYBTN = '[<a href="index.html?res={$NO}">' + language['reply_btn'] + '</a>]';
	var	_WARN_OLD = '<span class="warn_txt">' + language['warn_oldthread'] + '</span><br />';
	var	_WARN_BEKILL='<span class="warn_txt">' + language['warn_sizelimit'] + '</span><br />';
	var	_WARN_ENDREPLY= '<span class="warn_txt">' + language['warn_locked'] + '</span><br />';
	var	_WARN_HIDEPOST= '<span class="warn_txt2">' + language['notice_omitted'] + '</span><br />';
	var	_THREAD_TPL = 
				'<li class="threadpost" id="r{$NO}">' +
				'{$IMG_BAR}' +
				'{$IFDATAEXT1}'+
				'{$IMG_SRC}' +
				'<input type="checkbox" name="{$NO}" value="delete" />' +
				'<span class="title">{$SUB}</span>{$NAME_TEXT}<span class="name">{$NAME}</span> [{$NOW}]' +
				'{$QUOTEBTN}&nbsp;{$REPLYBTN}' +
				'<div class="quote">{$COM}</div>' +
				'{$IFDATACATE1}' +
				'{$WARN_OLD}{$WARN_BEKILL}{$WARN_ENDREPLY}{$WARN_HIDEPOST}<ul class="reply"></ul><hr/></li>';			
	var _REPLY_TPL = 
				'<li class="reply" id="r{$NO}">' +
				'<input type="checkbox" name="{$NO}" value="delete" />' +
				'<span class="title">{$SUB}</span>' +
				'{$NAME_TEXT} ' +
				'<span class="name">{$NAME}</span>' +
				'[{$NOW}] ' +
				'{$IFDATAEXT1}'+
				'{$IMG_BAR} {$IMG_SRC}' +
				'<div class="quote">{$COM}</div>' +
				'{$IFDATACATE1}' +
				'{$WARN_BEKILL}</li>';	
		
	this.init = function(){
		//alert('init');
		$threads = $(".threads");
//		$threads.append(_mkTHREAD({NO:3}));
	//	$(".threads > li#r3").css('background','#ACAC0F');
//		$(".threads > li#r3").append(_mkREPLY({NO:3}));
		
//		$threads.append(_mkTHREAD({NO:4}));
		argv = location.href.split("?");
		if(argv.length == 2){
			argv = argv[1].split('&');
			datas = []
			$.each(argv,function(i,v){
				tmp = v.split('=');
				datas[tmp[0]] = (tmp.length > 1) ? tmp[1] : true;		
			});
			if(typeof datas['mode'] != 'undefined'){

			}else if(typeof datas['res'] != 'undefined'){
				this.getPage({pageType:'THREAD',res:datas['res']});	
			}	
		}else{	
			this.getPage();
		}
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
	    	if('undefined' == typeof(data.pageType)){data.pageType = "SHOW" ;}
	    	
		
		url = "";
		switch(data.pageType){
			case 'SHOW'  :
				url = "../../main.php/" + DEFINES['BOARD'] + "/SHOW/" + data.pageNo;
				break;		
			case 'THREAD':
				if('undefined' == typeof(data.res))return ;
				url = "../../main.php/" + DEFINES['BOARD'] + "/THREAD/" + data.res + "/" + data.pageNo;
				$('form#postform_main input[name=resto]').val(data.res);
				break;
			default      :
				alert("dataType not support");
				return;

		}

		functions = [];
	    	functions['SHOW'] = function(data){
                                                $.each(data,function(i,v){
                                                        $.each(v,function(vin,vvn){_mkThreadList(vvn);});
                                                });
                                        };
		
	    	functions['THREAD'] = function(data){
                                                $.each(data,function(i,v){_mkThreadList(v);});

                                        };
		
	   // functions[]

	    $.ajax({
				dataType:'json',
				url:url,
				statusCode:{
					200:functions[data.pageType],
					501:function(){alert('尚未支援');}
					
				}
				});
	}
	var _mkThreadList = function(data){
		if(data['resto'] == '0'){//首PO
			$threads.append(_mkTHREAD(data));
		}else{
			$threads.find(' > li#r' + data['resto'] + ' ul.reply').append(_mkREPLY(data));
		}

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
		if('undefined' == typeof(data.hiddenReply)){data.hiddenReply = 0;}
		/*
		array('{$NO}'=>$no, '{$SUB}'=>$sub, '{$NAME}'=>$name, '{$NOW}'=>$now, '{$CATEGORY}'=>$category, '{/li}'=>/li, '{$IMG_BAR}'=>$IMG_BAR, '{$IMG_SRC}'=>$imgsrc, '{$WARN_BEKILL}'=>$WARN_BEKILL, '{$NAME_TEXT}'=>_T('post_name'), '{$CATEGORY_TEXT}'=>_T('post_category'), '{$SELF}'=>PHP_SELF, '{$COM}'=>$com);
		*/
		$IMG_SRC = _STEReplace({
								$TIM	  :data.tim,
								$EXT	  :data.ext,
								$IMG_SIZE :data.imgsize,
								$TW       :data.tw,
								$TH       :data.th
								
								},
								_IMG_SRC);
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
		



	

		$WARN_OLD 		= 0 ? (_WARN_OLD) 		: '';
		$WARN_BEKILL 	= 0 ? (_WARN_BEKILL) 	: '';
		$WARN_ENDREPLY  = 0 ? (_WARN_ENDREPLY)  : '';
		$WARN_HIDEPOST  = (data.hiddenReply!= 0) ? (_WARN_HIDEPOST.replace("%1$s",data.hiddenReply)) : '';
		$THREAD = _STEReplace({
								$NO 	  :data.no,
								$SUB	  :data.sub,
								$NAME     :data.name,
								$NOW      :data.now,
								$IMG_SRC  :(data.ext == '' ? '': $IMG_SRC),
								$IMG_BAR  :(data.ext == '' ? '': $IMG_BAR),
								$COM	  :data.com,	
								$QUOTEBTN :_STEReplace({$NO:data.no},_QUOTEBTN),
								$REPLYBTN :_STEReplace({$NO:data.no},_REQLYBTN),
								$NAME_TEXT:language['post_name'],
								$CATEGORY:data.category,
								$CATEGORY_TEXT:language['post_category'],
								$WARN_OLD :$WARN_OLD,
								$WARN_BEKILL:$WARN_BEKILL,
								$WARN_ENDREPLY:$WARN_ENDREPLY,
								$WARN_HIDEPOST:$WARN_HIDEPOST 
								},
				 _STEReplace({
								$IFDATAEXT1:((data.ext != "") ? '<br />': '') ,
								$IFDATACATE1:((data.category != "") ? '<div class="category">{$CATEGORY_TEXT}{$CATEGORY}</div>' : '') 
								},
								_THREAD_TPL));
		return $THREAD;
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

		$WARN_BEKILL = 0 ? _WARN_BEKILL : '';	

		$REPLY = _STEReplace({
								$NO 	  :data.no,
								$SUB	  :data.sub,
								$NAME     :data.name,
								$NOW      :data.now,
								$IMG_SRC  :(data.ext == '' ? '': $IMG_SRC),
								$IMG_BAR  :(data.ext == '' ? '': $IMG_BAR),
								$COM	  :data.com,	
								$QUOTEBTN :_QUOTEBTN,
								$REPLYBTN :_REQLYBTN,
								$NAME_TEXT:language['post_name'],
								$CATEGORY:data.category,
								$CATEGORY_TEXT:language['post_category'],
								$WARN_BEKILL:$WARN_BEKILL
							//	$WARN_HIDEPOST:_WARN_HIDEPOST
								},
				_STEReplace({
								$IFDATAEXT1:((data.ext != '') ? '<br/>&nbsp' : '' ),
								$IFDATACATE1:((data.category != '') ? '<div class="category">{$CATEGORY_TEXT}{$CATEGORY}</div>' : '' ) 			
								},
								_REPLY_TPL));
		return $REPLY;
	}
	
	var _mkReplyBtn = function(data){
		_DATA = '[' + '<a href="index.html?res="></a>' + ']';

	}	

};

