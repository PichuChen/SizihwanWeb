<?php 

/*
{$ADDITION_INFO} - (發文表單用) config.php 中的表單下額外文字
{$ADMIN} - (BODYHEAD用) 「管理」連結
{$ALLOW_UPLOAD_EXT} - (發文表單用) config.php 中接受之附加圖檔副檔名
{$BACK_TEXT} - (錯誤頁面用) 「返回」字串
{$CATEGORY_TEXT} - (類別標籤分類功能) 「類別：」字串
{$CATEGORY} - (類別標籤分類功能) 文章的類別標籤列表
{$COM} - 文章內文
{$DEL_HEAD_TEXT} - 刪文表單的頭
{$DEL_IMG_ONLY_FIELD} - (刪文表單用) 「僅刪除附加檔案」勾選按鈕
{$DEL_IMG_ONLY_TEXT} - (刪文表單用) 「僅刪除附加檔案」字串
{$DEL_PASS_FIELD} - (刪文表單用) 刪除用密碼欄
{$DEL_PASS_TEXT} - (刪文表單用) 「刪除用密碼:」字串
{$DEL_SUBMIT_BTN} - (刪文表單用) 「刪除」按鈕
{$FOOTER} - 頁尾
{$FORMBOTTOM} - (發文表單用) 表單下的HTML碼
{$FORMTOP} - (發文表單用) 表單上的HTML碼
{$FORM_ATTECHMENT_FIELD}
{$FORM_ATTECHMENT_TEXT}
{$FORM_CATEGORY_FIELD}
{$FORM_CATEGORY_NOTICE}
{$FORM_CATEGORY_TEXT}
{$FORM_COMMENT_FIELD}
{$FORM_COMMENT_TEXT}
{$FORM_CONTPOST_FIELD}
{$FORM_CONTPOST_TEXT}
{$FORM_DELETE_PASSWORD_FIELD}
{$FORM_DELETE_PASSWORD_NOTICE}
{$FORM_DELETE_PASSWORD_TEXT}
{$FORM_EMAIL_FIELD}
{$FORM_EMAIL_TEXT}
{$FORM_NAME_FIELD}
{$FORM_NAME_TEXT}
{$FORM_NOATTECHMENT_FIELD}
{$FORM_NOATTECHMENT_TEXT}
{$FORM_NOTICE_NOSCRIPT}
{$FORM_NOTICE_STORAGE_LIMIT}
{$FORM_NOTICE}
{$FORM_SUBMIT}
{$FORM_TOPIC_FIELD}
{$FORM_TOPIC_TEXT}
{$HOME} - (BODYHEAD用) 「主頁」連結
{$HOOKLINKS} - (PMS用) (BODYHEAD用) PMS附加頁首連結
{$HOOKPOSTINFO} - (PMS用) (發文表單用) PMS發文表單下方附加說明文字
{$IMG_BAR} - (有貼圖時) 圖片資訊列，顯示長寬大小用
{$IMG_SRC} - (有貼圖時) 圖片本體
{$JS_REGIST_WITHOUTCOMMENT}
{$JS_REGIST_UPLOAD_NOTSUPPORT}
{$JS_CONVERT_SAKURA}
{$MAX_FILE_SIZE} - (發文表單用) 「最大檔案大小」字串
{$MESG} - (錯誤頁面用) 錯誤信息
{$NAME_TEXT} - (討論串用) 「名稱」字串
{$NAME} - 發文者名稱
{$NOW} - 發文時間
{$NO} - 文章編號
{$PAGENAV}
{$QUOTEBTN} - 文章引用系統功能按鈕 (可以按的 No.XXX)
{$REFRESH}
{$REPLYBTN} - (一般瀏覽時) 進入回應連結
{$RESTO}
{$RETURN_TEXT}
{$SEARCH} - (BODYHEAD用) 「搜尋」連結
{$SELF2}
{$SELF}
{$STATUS}
{$SUB} - 文章標題
{$THREADFRONT}
{$THREADREAR}
{$THREADS}
{$TITLE} - 標題
{$TOP_LINKS} - (BODYHEAD用) config.php 中的頁面右上方的額外連結
{$WARN_BEKILL} - (提示文字) 此篇文章的附加檔案即將被刪除
{$WARN_ENDREPLY} - (提示文字) 此討論串禁止回應
{$WARN_HIDEPOST} - (提示文字) 此討論串有幾篇回應已隱藏
{$WARN_OLD} - (提示文字) 此篇文章過舊即將被刪除


*/


$TITLE = TITLE;
$SELF  = PHP_SELF;
$MAX_FILE_SIZE = MAX_KB * 1024;
//$RESTO = $resno ? '<input type="hidden" name="resto" value="'.$resno.'" />' : '';
$FORM_NAME_TEXT = _T('form_name');
$FORM_NAME_FIELD = '<input class="hide" type="text" name="name" value="spammer" /><input type="text" name="'.FT_NAME.'" id="fname" size="28" value="'./*$name.*/'" />';
$FORM_EMAIL_TEXT = _T('form_email');
$FORM_EMAIL_FIELD = '<input type="text" name="'.FT_EMAIL.'" id="femail" size="28" value="'./*$mail.*/'" /><input type="text" class="hide" name="email" value="foo@foo.bar" />';
$FORM_TOPIC_TEXT = _T('form_topic');
$FORM_TOPIC_FIELD = '<input class="hide" value="DO NOT FIX THIS" type="text" name="sub" /><input type="text" name="'.FT_SUBJECT.'" id="fsub" size="28" value="'./*$sub.*/'" />';
$FORM_SUBMIT = '<input type="submit" name="sendbtn" value="'._T('form_submit_btn').'" />';
$FORM_COMMENT_TEXT = _T('form_comment');
$FORM_COMMENT_FIELD = '<textarea name="'.FT_COMMENT.'" id="fcom" cols="48" rows="4" style="width: 400px; height: 80px;"></textarea><textarea name="com" class="hide" cols="48" rows="4">EID OG SMAPS</textarea>';
$FORM_DELETE_PASSWORD_FIELD = '<input type="password" name="pwd" size="8" maxlength="8" value="" />';
$FORM_DELETE_PASSWORD_TEXT = _T('form_delete_password');
$FORM_DELETE_PASSWORD_NOTICE = _T('form_delete_password_notice');
$FORM_EXTRA_COLUMN = '';
$FORM_NOTICE   = _T('form_notice',str_replace('|',',',ALLOW_UPLOAD_EXT),MAX_KB,(/*$resno*/1? MAX_RW : MAX_W),(/*$resno*/1 ? MAX_RH : MAX_H));
$HOOKPOSTINFO  = '';
$ADDITION_INFO = $ADDITION_INFO;
$FORM_NOTICE_NOSCRIPT = _T('form_notice_noscript');
//$PMS->useModuleMethods('PostForm', array(&$pte_vals['{$FORM_EXTRA_COLUMN}'])); // "PostForm" Hook Point
	if((RESIMG || !$resno)){
		$FORM_ATTECHMENT_TEXT = _T('form_attechment');
		$FORM_ATTECHMENT_FIELD = '<input type="file" name="upfile" id="fupfile" size="25" /><input class="hide" type="checkbox" name="reply" value="yes" />';
		$FORM_NOATTECHMENT_TEXT = _T('form_noattechment');
		$FORM_NOATTECHMENT_FIELD = '<input type="checkbox" name="noimg" id="noimg" value="on" />';
		if(USE_UPSERIES) { // 啟動連貼機能
			$FORM_CONTPOST_FIELD = '<input type="checkbox" name="up_series" id="up_series" value="on"'.((isset($_GET["upseries"]) && 1 /*$resno*/)?' checked="checked"':'').' />';
			$FORM_CONTPOST_TEXT  = _T('form_contpost');
		}
	}

$ALLOW_UPLOAD_EXT = ALLOW_UPLOAD_EXT;
$JS_REGIST_WITHOUTCOMMENT = str_replace('\'', '\\\'', _T('regist_withoutcomment'));
$JS_REGIST_UPLOAD_NOTSUPPORT = str_replace('\'', '\\\'', _T('regist_upload_notsupport'));
$JS_CONVERT_SAKURA = str_replace('\'', '\\\'', _T('js_convert_sakura'));
$TOP_LINKS = TOP_LINKS;
$HOME = '<li>[<a href="'.HOME.'" rel="_top">'._T('head_home').'</a>]</li>';
$STATUS = '<li>[<a href="'.PHP_SELF.'?mode=status">'._T('head_info').'</a>]</li>';
$ADMIN = '<li>[<a href="'.PHP_SELF.'?mode=admin">'._T('head_admin').'</a>]</li>';
$REFRESH = '<li>[<a href="'.PHP_SELF2.'?">'._T('head_refresh').'</a>]</li>';
$SEARCH = (USE_SEARCH) ? '<li>[<a href="'.PHP_SELF.'?mode=search">'._T('head_search').'</a>]</li>' : '';
$HOOKLINKS = '';
?><!DOCTYP HTML>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="Sat, 1 Jan 2000 00:00:00 GMT" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="zh-tw" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?php echo $TITLE?></title>
<link rel="stylesheet" type="text/css" href="../../resource/mainstyle.css" />

<script type="text/javascript">
var DEFINES = {};
DEFINES['PHP_SELF'] = "<?php echo $SELF?>";
DEFINES['BOARD']    = "<?php echo $BOARD?>";
</script>
<script type="text/javascript" src="../../resource/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../../resource/SWClient.js?<?=time();?>"></script>
<script type="text/javascript">

$(document).ready(function(){
	var SWClient = new _SWClient;
	SWClient.init();
//	$(".threads").css('background','#ACACFF');
	
});
</script>
</head>

<body>

<header>

<h1><?php echo $TITLE?></h1>
<!--&TOPLINKS-->
<ul id="toplink">
<?php echo "{$HOME} {$SEARCH} {$HOOKLINKS} {$TOP_LINKS} {$STATUS} {$ADMIN} {$REFRESH}"?>
</ul>
<!--/&TOPLINKS-->

<!--&POSTFORM-->
<form target="uploader" action="../../main.php/<?php echo $BOARD?>/POSTS" method="post" enctype="multipart/form-data"  id="postform_main">
<div id="postform">
<?php if(isset($FORMTOP) ){echo $FORMTOP;}?>
<input type="hidden" name="mode" value="<?php //echo $MODE;?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $MAX_FILE_SIZE?>" />
<input type="hidden" name="upfile_path" value="" />
<?php if(isset($RESTO) ){echo $RESTO;}?>
<div style="text-align: center;">
<table cellpadding="1" cellspacing="1" id="postform_tbl" style="margin: 0px auto; text-align: left;">
	<tr><td class="Form_bg"><b><?php echo $FORM_NAME_TEXT?>   </b></td><td><?php echo $FORM_NAME_FIELD?></td></tr>
	<tr><td class="Form_bg"><b><?php echo $FORM_EMAIL_TEXT?>  </b></td><td><?php echo $FORM_EMAIL_FIELD?></td></tr>
	<tr><td class="Form_bg"><b><?php echo $FORM_TOPIC_TEXT?>  </b></td><td><?php echo $FORM_TOPIC_FIELD . $FORM_SUBMIT?></td></tr>
	<tr><td class="Form_bg"><b><?php echo $FORM_COMMENT_TEXT?></b></td><td><?php echo $FORM_COMMENT_FIELD?></td></tr>
<?php if(isset($FORM_ATTECHMENT_FIELD) ){?>
	<tr><td class="Form_bg"><b><?php echo $FORM_ATTECHMENT_TEXT ?> </b></td>
	    <td><?php echo $FORM_ATTECHMENT_FIELD . '[' . $FORM_NOATTECHMENT_FIELD . '<label for="noimg">' . $FORM_NOATTECHMENT_TEXT ?> </label>]
<?php } ?>
<?php if(isset($FORM_CONTPOST_FIELD) ){?>
	[<?php echo $FORM_CONTPOST_FIELD?><label for="up_series"><?php echo $FORM_CONTPOST_TEXT?></label>]

<?php } ?>

<?php if(isset($FORM_ATTECHMENT_FIELD) ){?></td></tr>
<?php } ?>
<?php if(isset($FORM_CATEGORY_FIELD) ){?>
	<tr><td class="Form_bg"><b><?php echo $FORM_CATEGORY_TEXT?>
	</b></td><td><?php echo $FORM_CATEGORY_FIELD?><small><?php echo FORM_CATEGORY_NOTICE?></small></td></tr>
<?php } ?>
<tr><td class="Form_bg"><b><?php echo $FORM_DELETE_PASSWORD_TEXT?></b></td><td><?php echo $FORM_DELETE_PASSWORD_FIELD?><small><?php echo $FORM_DELETE_PASSWORD_NOTICE?></small></td></tr>
<?php echo $FORM_EXTRA_COLUMN?>
<tr><td colspan="2">
<div id="postinfo">
<ul><?php echo $FORM_NOTICE?>
<?php if(isset($FORM_NOTICE_STORAGE_LIMIT) ){echo $FORM_NOTICE_STORAGE_LIMIT;} ?>
<?php echo $HOOKPOSTINFO?>
<?php echo $ADDITION_INFO?>
</ul>
<noscript><div><?php echo $FORM_NOTICE_NOSCRIPT?></div></noscript>
</div>
</td></tr>
</table>
</div>
<script type="text/javascript">//l1();</script>
<hr />
</div>
</form>
<iframe name="uploader" src="http://www.google.com"></iframe> 

<?php if(isset($FORMBOTTOM) ){echo $FORMBOTTOM;}?>
<!--/&POSTFORM-->
</header>

<ul class="threads">
</ul>


<!--&FOOTER-->
<footer id="footer">
<?php echo "{$FOOTER}"?>
<script type="text/javascript">//preset();</script>
</footer>
<!--/&FOOTER-->
</body>
</html>
