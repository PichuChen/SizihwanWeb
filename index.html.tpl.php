<!DOCTYP HTML>
<html>
<head>
<script type="text/javascript">
var DEFINES = {};
DEFINES['PHP_SELF'] = "main.php";
</script>
<script type="text/javascript" src="resource/jquery-1.5.1.min.js"></script>
<script type="text/javascript">
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
		$(".threads > li#r3").css('background','#ACAC0F');
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
		
		_THREAD = _THREAD.replace(/{\$NO}/g,data.NO)
				 .replace(/{\$NAME_TEXT}/g,language['post_name'])
				 .replace(/{\$REPLYBTN}/g,language['reply_btn']);
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

$(document).ready(function(){
	var SWClient = new _SWClient;
	SWClient.init();
	$(".threads").css('background','#ACACFF');
	
});
</script>
</head>

<body>

<header>

<h1>Pixhu</h1>
</header>
<!--&POSTFORM-->
<form action="{$SELF}" method="post" enctype="multipart/form-data" onsubmit="return c();" id="postform_main">
<div id="postform">
<!--&IF($FORMTOP,'{$FORMTOP}','')-->
<input type="hidden" name="mode" value="{$MODE}" />
<input type="hidden" name="MAX_FILE_SIZE" value="{$MAX_FILE_SIZE}" />
<input type="hidden" name="upfile_path" value="" />
<!--&IF($RESTO,'{$RESTO}','')-->
<div style="text-align: center;">
<table cellpadding="1" cellspacing="1" id="postform_tbl" style="margin: 0px auto; text-align: left;">
<tr><td class="Form_bg"><b>{$FORM_NAME_TEXT}</b></td><td>{$FORM_NAME_FIELD}</td></tr>
<tr><td class="Form_bg"><b>{$FORM_EMAIL_TEXT}</b></td><td>{$FORM_EMAIL_FIELD}</td></tr>
<tr><td class="Form_bg"><b>{$FORM_TOPIC_TEXT}</b></td><td>{$FORM_TOPIC_FIELD}{$FORM_SUBMIT}</td></tr>
<tr><td class="Form_bg"><b>{$FORM_COMMENT_TEXT}</b></td><td>{$FORM_COMMENT_FIELD}</td></tr>
<!--&IF($FORM_ATTECHMENT_FIELD,'<tr><td class="Form_bg"><b>{$FORM_ATTECHMENT_TEXT}</b></td><td>{$FORM_ATTECHMENT_FIELD}[{$FORM_NOATTECHMENT_FIELD}<label for="noimg">{$FORM_NOATTECHMENT_TEXT}</label>]','')-->
<!--&IF($FORM_CONTPOST_FIELD,'[{$FORM_CONTPOST_FIELD}<label for="up_series">{$FORM_CONTPOST_TEXT}</label>]','')-->
<!--&IF($FORM_ATTECHMENT_FIELD,'</td></tr>','')-->
<!--&IF($FORM_CATEGORY_FIELD,'<tr><td class="Form_bg"><b>{$FORM_CATEGORY_TEXT}</b></td><td>{$FORM_CATEGORY_FIELD}<small>{$FORM_CATEGORY_NOTICE}</small></td></tr>','')-->
<tr><td class="Form_bg"><b>{$FORM_DELETE_PASSWORD_TEXT}</b></td><td>{$FORM_DELETE_PASSWORD_FIELD}<small>{$FORM_DELETE_PASSWORD_NOTICE}</small></td></tr>
{$FORM_EXTRA_COLUMN}
<tr><td colspan="2">
<div id="postinfo">
<ul>{$FORM_NOTICE}
<!--&IF($FORM_NOTICE_STORAGE_LIMIT,'{$FORM_NOTICE_STORAGE_LIMIT}','')-->
{$HOOKPOSTINFO}
{$ADDITION_INFO}
</ul>
<noscript><div>{$FORM_NOTICE_NOSCRIPT}</div></noscript>
</div>
</td></tr>
</table>
</div>
<script type="text/javascript">l1();</script>
<hr />
</div>
</form>
<!--&IF($FORMBOTTOM,'{$FORMBOTTOM}','')-->
<!--/&POSTFORM-->
<?php echo "123"?>
<ul class="threads">
</ul>
</body>
</html>