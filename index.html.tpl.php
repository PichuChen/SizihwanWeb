<?php 
include_once('./config.php'); // 引入設定檔
include_once('./lib/lib_language.php'); // 引入語系

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





?><!DOCTYP HTML>
<html>
<head>
<script type="text/javascript">
var DEFINES = {};
DEFINES['PHP_SELF'] = "main.php";
</script>
<script type="text/javascript" src="resource/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="resource/SWClient.js?<?=time();?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	var SWClient = new _SWClient;
	SWClient.init();
	$(".threads").css('background','#ACACFF');
	
});
</script>
</head>

<body>

<header>

<h1><?php=$TITLE?></h1>
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