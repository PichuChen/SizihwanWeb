<?php
define("PIXMICAT_VER", 'Pixmicat!-PIO 5th.Release (v100521)'); 
define("SIZIHWANWEB_VER", '0.01'); // ������T��r


include_once('./config.php'); // �ޤJ�]�w��
include_once('./lib/lib_pio.php'); // �ޤJPIO


/*-----------�{���U���\��D�n�P�_-------------*/
if(GZIP_COMPRESS_LEVEL && ($Encoding = CheckSupportGZip())){ ob_start(); ob_implicit_flush(0); } // �䴩�B�}��Gzip���Y�N�]�w�İ�
$mode = isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''); // �ثe����Ҧ� (GET, POST)

/*-----------�{���U���\��D�n�P�_-------------*/
if(GZIP_COMPRESS_LEVEL && ($Encoding = CheckSupportGZip())){ ob_start(); ob_implicit_flush(0); } // �䴩�B�}��Gzip���Y�N�]�w�İ�
$mode = isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''); // �ثe����Ҧ� (GET, POST)

//init(); // �������I�{�����Ҫ�l�ơA�]�L�@����ЧR������I����
switch($mode){
//	case 'regist':
		// regist();
		// break;
	// case 'admin':
		// $admin = isset($_REQUEST['admin']) ? $_REQUEST['admin'] : ''; // �޲z�̰���Ҧ�
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
	case 'lang':
		if(!is_file('lang_zh_TW.json')){
			require("/lib/lang/zh_TW.php");
			$fp = fopen('lang_zh_TW.json', 'w');
			stream_set_write_buffer($fp, 0);
			fwrite($fp,json_encode($language));
			fclose($fp);
			@chmod($logfilename, 0666);
		}
		header('Location: '.fullURL().'lang_zh_TW.json'.'?'.time());
		break;
	default:
		// �p�G�s�����䴩XHTML�з�MIME�N��X
		header('Content-Type: '.((USE_XHTML && strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')!==FALSE) ? 'application/xhtml+xml' : 'text/html').'; charset=utf-8');
		$res = isset($_GET['res']) ? $_GET['res'] : 0; // ���^���s��
		if($res){ // �^���Ҧ���X
			$page = isset($_GET['page_num']) ? $_GET['page_num'] : 'RE_PAGE_MAX';
			if(!($page=='all' || $page=='RE_PAGE_MAX')) $page = intval($_GET['page_num']);
			updatelog($res, $page); // ������
		}elseif(isset($_GET['page_num']) && intval($_GET['page_num']) > -1){ // PHP�ʺA��X�@��
			updatelog(0, intval($_GET['page_num']));
		}else{ // �ɦ��R�A�w�s��
			if(!is_file(PHP_SELF2)) updatelog();
			header('HTTP/1.1 302 Moved Temporarily');
			header('Location: '.fullURL().PHP_SELF2.'?'.time());
		}
}
if(GZIP_COMPRESS_LEVEL && $Encoding){ // ���Ұ�Gzip
	if(!ob_get_length()) exit; // �S���e�������Y
	header('Content-Encoding: '.$Encoding);
	header('X-Content-Encoding-Level: '.GZIP_COMPRESS_LEVEL);
	header('Vary: Accept-Encoding');
	print gzencode(ob_get_clean(), GZIP_COMPRESS_LEVEL); // ���Y���e
}

?>