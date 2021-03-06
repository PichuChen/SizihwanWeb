﻿<?php
define("PIXMICAT_VER", 'Pixmicat!-PIO 5th.Release (v100521)'); 
define("SIZIHWANWEB_VER", '0.02'); // 版本資訊文字


$DEFINE['PATH_ACTION_POSTS'] = './action/posts.php'; //POSTS 模組
$DEFINE['PATH_ACTION_SHOW']  = './action/show.php' ; //SHOW 模組
$DEFINE['PATH_ACTION_THREAD']  = './action/thread.php' ; //THREAD 模組
define("PATH_ACTION_SHOW", './action/show.php');   //SHOW 模組
define("PATH_ACTION_POSTS", './action/posts.php'); //POSTS 模組

include_once('./lib/lib_common.php'); // 引入共通函式檔案
//include_once('./lib/lib_pio.php'); // 引入PIO


/*-----------程式各項功能主要判斷-------------*/
//$mode = isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''); // 目前執行模式 (GET, POST)

//echo $_SERVER['HTTP_ACCEPT'];
//echo $_SERVER['PATH_INFO'];
$ARG = explode("/",$_SERVER['PATH_INFO']); 
if(!isset($ARG[1])){sendStatusCode(400);exit;}//如果沒有要求看板名稱
define("PATH_BOARD", 'board/' . $ARG[1]);//設定看板位置
if(!is_dir(PATH_BOARD)){sendStatusCode(404);exit;}//檢查看板是否存在
//include_once('config.php');               // 引入site設定檔
include_once(PATH_BOARD . '/config.php'); // 引入board設定檔

//if(GZIP_COMPRESS_LEVEL && ($Encoding = CheckSupportGZip())){ ob_start(); ob_implicit_flush(0); } // 支援且開啟Gzip壓縮就設緩衝區
//print_r($ARG);
$BOARD  = $ARG[1];
$ACTION = $ARG[2];

switch($ACTION){ 
	case '':
		if(!is_file(PATH_BOARD. '/index.html')){
			include_once(PATH_BOARD . '/config.php'); // 引入設定檔
			include_once('./lib/lib_language.php'); // 引入語系
			include_once('./lib/lib_common.php'); // 引入共通函式檔案

			LoadLanguage('zh_TW');
			ob_start ();
			require('./index.html.tpl.php');
			$buf = ob_get_contents();
			ob_end_clean();
			$fp = fopen(PATH_BOARD . '/index.html', 'w');
			stream_set_write_buffer($fp, 0);
			fwrite($fp,$buf);
			fclose($fp);
		}
		header('Location: '.fullURL().PATH_BOARD. '/index.html');
		break;
	case 'SHOW':
		{
			require(PATH_ACTION_SHOW);
			if(!isset($ARG[3]) || $ARG[3] == ""){
				$ARG[3] = 1;
			}else if($ARG[3] == "PAGENUM"){
				echo json_encode(actShowPageNum());
				break;
			}
			echo json_encode(actSHOW($ARG[3] - 1));
		}
		break;	
	case 'LANG':
		{
		if(!is_file('lang_zh_TW.json')){
			require("./lib/lang/zh_TW.php");
			$fp = fopen('lang_zh_TW.json', 'w');
			stream_set_write_buffer($fp, 0);
			fwrite($fp,json_encode($language));
			fclose($fp);
		}
		header('Location: '.fullURL().'lang_zh_TW.json'.'?');
		break;
		}
	case 'THREAD'://取得特定討論串
		{
		switch ($_SERVER['REQUEST_METHOD']){
		case 'GET' :
			if(!isset($ARG[3])) {
				 sendStatusCode(405);
				exit;//參數不足
			}
			if(!isset($ARG[4]) || $ARG[4] == "")$ARG[4] = 1;
			require($DEFINE['PATH_ACTION_THREAD']);
			getTHREAD(intval($ARG[3]),intval($ARG[4]));
		//	ddsendStatusCode(501);
			break;
		case 'DELETE' :
			if(!isset($_SERVER['PHP_AUTH_USER'])) {
				Header('WWW-Authenticate: Basic realm="please input your account."');
				Header("HTTP/1.0 401 Unauthorized"); 
				echo '"fob"';
			}else{
				if(!isset($ARG[3])) {
        	                        sendStatusCode(405);
	                                exit;//參數不足
	                        }
        	                if(!isset($ARG[4]) || $ARG[4] == "")$ARG[4] = 1;
                	        require($DEFINE['PATH_ACTION_THREAD']);
				deleteThread();
			}
			break;	
		}
		}
		break;
	case 'POSTS'  ://投稿,刪除,修改
		{
			
			include_once('./lib/lib_language.php'); // 引入語系
			
			LoadLanguage('zh_TW');
			require(PATH_ACTION_POSTS);
			actPOSTS();
		}
		sendStatusCode(200);
		break;
	case 'STATUS' ://顯示系統狀態
	case 'REMAKE' ://重新生成快取
	case 'SEARCH' ://搜尋 \
		sendStatusCode(501);
		break;
	case 'SHOW'://顯示頁面
		{
			if(!isset($ARG[3]) || $ARG[3] == "")$ARG[3] = 1;
			require(PATH_ACTION_SHOW);
			actSHOW( intval($ARG[3]));
		}
		break;	
	default:
		{
			require(PATH_ACTION_SHOW);
			actSHOW( intval($ARG[2]));
			exit;
		
		}
		sendStatusCode("400");
}

exit;

switch ($_SERVER['REQUEST_METHOD']){
	case 'GET' :
	
//		methodGET();
		sendStatusCode("200");
		break;
		
		
	default:
		sendStatusCode("405");

	exit;

}
exit;


//init(); // ←■■！程式環境初始化，跑過一次後請刪除此行！■■
switch($mode){
//	case 'regist':
		// regist();
		// break;
	// case 'admin':
		// $admin = isset($_REQUEST['admin']) ? $_REQUEST['admin'] : ''; 
		// valid();
		// switch($admin){
			// case 'del': admindel(); break;
			// case 'logout':
				// adminAuthenticate('logout');
				// header('HTTP/1.1 302 Moved Temporarily');
				// header('Location: '.fullURL().PHP_SELF2.'?'.time());
				// break;
			// case 'optimize':
			// case 'check':
			// case 'repair':
			// case 'export':
				// if(!$PIO->dbMaintanence($admin)) echo _T('action_main_notsupport');
				// else echo _T('action_main_'.$admin).(($mret = $PIO->dbMaintanence($admin,true))?_T('action_main_success'):_T('action_main_failed')).(is_bool($mret)?'':'<br/>'.$mret);
				// die("</div></form></body>\n</html>");
				// break;
			// default:
		// }
		// break;
	// case 'search':
		// search();
		// break;
	// case 'status':
		// showstatus();
		// break;
	// case 'category':
		// searchCategory();
		// break;
	// case 'module':
		// $loadModule = isset($_GET['load']) ? $_GET['load'] : '';
		// if($PMS->onlyLoad($loadModule)) $PMS->moduleInstance[$loadModule]->ModulePage();
		// else echo '404 Not Found';
		// break;
	// case 'moduleloaded':
		// listModules();
		// break;
	// case 'usrdel':
		// usrdel();
	// case 'remake':
		// updatelog();
		// header('HTTP/1.1 302 Moved Temporarily');
		// header('Location: '.fullURL().PHP_SELF2.'?'.time());
		// break;

	default:
		// 如果瀏覽器支援XHTML標準MIME就輸出
		header('Content-Type: '.((USE_XHTML && strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')!==FALSE) ? 'application/xhtml+xml' : 'text/html').'; charset=utf-8');
		$res = isset($_GET['res']) ? $_GET['res'] : 0; // 欲回應編號
		if($res){ // 回應模式輸出
			$page = isset($_GET['page_num']) ? $_GET['page_num'] : 'RE_PAGE_MAX';
			if(!($page=='all' || $page=='RE_PAGE_MAX')) $page = intval($_GET['page_num']);
			updatelog($res, $page); // 實行分頁
		}elseif(isset($_GET['page_num']) && intval($_GET['page_num']) > -1){ // PHP動態輸出一頁
			updatelog(0, intval($_GET['page_num']));
		}else{ // 導至靜態庫存頁
			if(!is_file(PHP_SELF2)) updatelog();
			header('HTTP/1.1 302 Moved Temporarily');
			header('Location: '.fullURL().PHP_SELF2.'?'.time());
		}
}
if(GZIP_COMPRESS_LEVEL && $Encoding){ // 有啟動Gzip
	if(!ob_get_length()) exit; // 沒內容不必壓縮
	header('Content-Encoding: '.$Encoding);
	header('X-Content-Encoding-Level: '.GZIP_COMPRESS_LEVEL);
	header('Vary: Accept-Encoding');
	print gzencode(ob_get_clean(), GZIP_COMPRESS_LEVEL); // 壓縮內容
}
?>
