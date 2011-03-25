<?php
function actPOSTS(){
	print_r($_POST);
//	print_r($_FILE);
	require("./lib/lib_pio.php");
//require("./lib/lib_fileio.php");
	
	
	
	
echo __LINE__ . '\n';
	$dest = ''; $mes = ''; $up_incomplete = 0; $is_admin = false;
	$path = realpath('.').DIRECTORY_SEPARATOR; // 此目錄的絕對位置

	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		sendStatusCode(405);
		return;
		//error(_T('regist_notpost')); // 非正規POST方式
	}
	// 欄位陷阱
	$FTname = isset($_POST['name']) ? $_POST['name'] : '';
	$FTemail = isset($_POST['email']) ? $_POST['email'] : '';
	$FTsub = isset($_POST['sub']) ? $_POST['sub'] : '';
	$FTcom = isset($_POST['com']) ? $_POST['com'] : '';
	$FTreply = isset($_POST['reply']) ? $_POST['reply'] : '';
	if($FTname != 'spammer' || $FTemail != 'foo@foo.bar' || $FTsub != 'DO NOT FIX THIS' || $FTcom != 'EID OG SMAPS' || $FTreply != '') error(_T('regist_nospam'));


echo __LINE__ . '<br/>';


echo __LINE__ . '\n';


	$name = isset($_POST[FT_NAME]) ? CleanStr($_POST[FT_NAME]) : '';
	$email = isset($_POST[FT_EMAIL]) ? CleanStr($_POST[FT_EMAIL]) : '';
	$sub = isset($_POST[FT_SUBJECT]) ? CleanStr($_POST[FT_SUBJECT]) : '';
	$com = isset($_POST[FT_COMMENT]) ? $_POST[FT_COMMENT] : '';
	$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';
	$category = isset($_POST['category']) ? CleanStr($_POST['category']) : '';
	$resto = isset($_POST['resto']) ? intval($_POST['resto']) : 0;
	$upfile = isset($_FILES['upfile']['tmp_name']) ? $_FILES['upfile']['tmp_name'] : '';
	$upfile_path = isset($_POST['upfile_path']) ? $_POST['upfile_path'] : '';
	$upfile_name = isset($_FILES['upfile']['name']) ? $_FILES['upfile']['name'] : false;
	$upfile_status = isset($_FILES['upfile']['error']) ? $_FILES['upfile']['error'] : 4;
	$pwdc = isset($_COOKIE['pwdc']) ? $_COOKIE['pwdc'] : '';
	$ip = getREMOTE_ADDR(); $host = gethostbyaddr($ip);
echo __LINE__ . '<br/>';

	//$PMS->useModuleMethods('RegistBegin', array(&$name, &$email, &$sub, &$com, array('file'=>&$upfile, 'path'=>&$upfile_path, 'name'=>&$upfile_name, 'status'=>&$upfile_status), array('ip'=>$ip, 'host'=>$host), $resto)); // "RegistBegin" Hook Point
	// 封鎖：IP/Hostname/DNSBL 檢查機能
	$baninfo = '';
	if(BanIPHostDNSBLCheck($ip, $host, $baninfo)){
		//error(_T('regist_ipfiltered', $baninfo));
		sendStatusCode(403);
		echo json_encode(array('statusCode' => 403,'message' => 'regist_ipfiltered' ,'extra' => array( $baninfo)));
		return ;
		//;
	}
echo BAN_CHECK;
	// 封鎖：限制出現之文字
	$tmparr = json_decode(BAD_STRING);

	foreach($tmparr as $value){
		if(strpos($com, $value)!==false || strpos($sub, $value)!==false || strpos($name, $value)!==false || strpos($email, $value)!==false){
			//error(_T('regist_wordfiltered'));
			sendStatusCode(403);
			echo json_encode(array('statusCode' => 403,'message' => 'regist_wordfiltered' ));
			return ;
		}
	}
echo __LINE__ . '<br/>';

	// 檢查是否輸入櫻花日文假名
	foreach(array($name, $email, $sub, $com) as $anti) if(anti_sakura($anti)){
		//error(_T('regist_sakuradetected'));
		sendStatusCode(403);
		return ;
	}

	// 時間
	$time = time();
	$tim = $time.substr(microtime(),2,3);
echo __LINE__ . '<br/>';
// 判斷上傳狀態
	switch($upfile_status){
		case 1:
			error(_T('regist_upload_exceedphp'));
			break;
		case 2:
			error(_T('regist_upload_exceedcustom'));
			break;
		case 3:
			error(_T('regist_upload_incompelete'));
			break;
		case 6:
			error(_T('regist_upload_direrror'));
			break;
		case 4: // 無上傳
			if(!$resto && !isset($_POST['noimg'])){
				 sendStatusCode(403);
				echo json_encode('regist_upload_noimg');
				//sendStatusCode(403);
				exit;
				//error(_T('regist_upload_noimg'));
			}
			break;
		case 0: // 上傳正常
		default:
	}
echo __LINE__ . '<br/>';

	// 如果有上傳檔案則處理附加圖檔
	if($upfile && (@is_uploaded_file($upfile) || @is_file($upfile))){
		// 一‧先儲存檔案
		$dest = $path.$tim.'.tmp';
		@move_uploaded_file($upfile, $dest) or @copy($upfile, $dest);
		@chmod($dest, 0666);
		if(!is_file($dest)) error(_T('regist_upload_filenotfound'), $dest);

		// 二‧判斷上傳附加圖檔途中是否有中斷
		$upsizeTTL = $_SERVER['CONTENT_LENGTH'];
		if(isset($_FILES['upfile'])){ // 有傳輸資料才需要計算，避免作白工
			$upsizeHDR = 0;
			// 檔案路徑：IE附完整路徑，故得從隱藏表單取得
			$tmp_upfile_path = $upfile_name;
			if($upfile_path) $tmp_upfile_path = get_magic_quotes_gpc() ? stripslashes($upfile_path) : $upfile_path;
			list(,$boundary) = explode('=', $_SERVER['CONTENT_TYPE']);
			foreach($_POST as $header => $value){ // 表單欄位傳送資料
				$upsizeHDR += strlen('--'.$boundary."\r\n");
				$upsizeHDR += strlen('Content-Disposition: form-data; name="'.$header.'"'."\r\n\r\n".(get_magic_quotes_gpc()?stripslashes($value):$value)."\r\n");
			}
			// 附加圖檔欄位傳送資料
			$upsizeHDR += strlen('--'.$boundary."\r\n");
			$upsizeHDR += strlen('Content-Disposition: form-data; name="upfile"; filename="'.$tmp_upfile_path."\"\r\n".'Content-Type: '.$_FILES['upfile']['type']."\r\n\r\n");
			$upsizeHDR += strlen("\r\n--".$boundary."--\r\n");
			$upsizeHDR += $_FILES['upfile']['size']; // 傳送附加圖檔資料量
			// 上傳位元組差值超過 HTTP_UPLOAD_DIFF：上傳附加圖檔不完全
			if(($upsizeTTL - $upsizeHDR) > HTTP_UPLOAD_DIFF){
				if(KILL_INCOMPLETE_UPLOAD){
					unlink($dest);
					die(_T('regist_upload_killincomp')); // 給瀏覽器的提示，假如使用者還看的到的話才不會納悶
				}else $up_incomplete = 1;
			}
		}

echo __LINE__ . '<br/>';
		// 三‧檢查是否為可接受的檔案
		$size = getimagesize($dest);
echo __LINE__ . '<br/>';
		if(!is_array($size)) error(_T('regist_upload_notimage'), $dest); // $size不為陣列就不是圖檔
		$imgsize = filesize($dest); // 檔案大小
		$imgsize = ($imgsize>=1024) ? (int)($imgsize/1024).' KB' : $imgsize.' B'; // KB和B的判別
		switch($size[2]){ // 判斷上傳附加圖檔之格式
			case 1 : $ext = ".gif"; break;
			case 2 : $ext = ".jpg"; break;
			case 3 : $ext = ".png"; break;
			case 4 : $ext = ".swf"; break;
			case 5 : $ext = ".psd"; break;
			case 6 : $ext = ".bmp"; break;
			case 13 : $ext = ".swf"; break;
			default : $ext = ".xxx"; error(_T('regist_upload_notsupport'), $dest);
		}
echo __LINE__ . '<br/>';
		$allow_exts = explode('|', strtolower(ALLOW_UPLOAD_EXT)); // 接受之附加圖檔副檔名
		if(array_search(substr($ext, 1), $allow_exts)===false) error(_T('regist_upload_notsupport'), $dest); // 並無在接受副檔名之列
		// 封鎖設定：限制上傳附加圖檔之MD5檢查碼
		$md5chksum = md5_file($dest); // 檔案MD5
		$tmparr = json_decode(BAD_FILEMD5);
		if(array_search($md5chksum, $tmparr)!==FALSE) error(_T('regist_upload_blocked'), $dest); // 在封鎖設定內則阻擋
echo __LINE__ . '<br/>';
		// 四‧計算附加圖檔圖檔縮圖顯示尺寸
		$W = $imgW = $size[0];
		$H = $imgH = $size[1];
		$MAXW = $resto ? MAX_RW : MAX_W;
		$MAXH = $resto ? MAX_RH : MAX_H;
		if($W > $MAXW || $H > $MAXH){
			$W2 = $MAXW / $W;
			$H2 = $MAXH / $H;
			$key = ($W2 < $H2) ? $W2 : $H2;
			$W = ceil($W * $key);
			$H = ceil($H * $key);
		}
		$mes = _T('regist_uploaded', CleanStr($upfile_name));
	}
echo __LINE__ . '<br/>';
	// 檢查表單欄位內容並修整
	if(strlen($name) > 100) error(_T('regist_nametoolong'), $dest);
	if(strlen($email) > 100) error(_T('regist_emailtoolong'), $dest);
	if(strlen($sub) > 100) error(_T('regist_topictoolong'), $dest);
	if(strlen($resto) > 10) error(_T('regist_longthreadnum'), $dest);

	// E-mail / 標題修整
	$email = str_replace("\r\n", '', $email); $sub = str_replace("\r\n", '', $sub);
	// 名稱修整
	$name = str_replace(_T('trip_pre'), _T('trip_pre_fake'), $name); // 防止????偽造
	$name = str_replace(CAP_SUFFIX, _T('cap_char_fake'), $name); // 防止管理員????偽造
	$name = str_replace("\r\n", '', $name);
	$nameOri = $name; // 名稱
	if(preg_match('/(.*?)[#＃](.*)/u', $name, $regs)){ // ????(Trip)機能
		$name = $nameOri = $regs[1]; $cap = strtr($regs[2], array('&amp;'=>'&'));
		$salt = preg_replace('/[^\.-z]/', '.', substr($cap.'H.', 1, 2));
		$salt = strtr($salt, ':;<=>?@[\\]^_`', 'ABCDEFGabcdef');
		$name = $name._T('trip_pre').substr(crypt($cap, $salt), -10);
	}
	if(CAP_ENABLE && preg_match('/(.*?)[#＃](.*)/', $email, $aregs)){ // 管理員????(Cap)機能
		$acap_name = $nameOri; $acap_pwd = strtr($aregs[2], array('&amp;'=>'&'));
		if($acap_name==CAP_NAME && $acap_pwd==CAP_PASS){
			$name = '<span class="admin_cap">'.$name.CAP_SUFFIX.'</span>';
			$is_admin = true;
			$email = $aregs[1]; // 去除 #xx 密碼
		}
	}
	if(!$is_admin){ // 非管理員
		$name = str_replace(_T('admin'), '"'._T('admin').'"', $name);
		$name = str_replace(_T('deletor'), '"'._T('deletor').'"', $name);
	}
	$name = str_replace('&'._T('trip_pre'), '&amp;'._T('trip_pre'), $name); // 避免 &#xxxx; 後面被視為 Trip 留下 & 造成解析錯誤
	// 內文修整
	if((strlen($com) > COMM_MAX) && !$is_admin) error(_T('regist_commenttoolong'), $dest);
	$com = CleanStr($com, $is_admin); // 引入$is_admin參數是因為當管理員????啟動時，允許管理員依config設定是否使用HTML
	if(!$com && $upfile_status==4) {
		echo json_encode('regist_withoutcomment');
		sendStatusCode(403);
		exit;
	}
	$com = str_replace(array("\r\n", "\r"), "\n", $com); $com = preg_replace("/\n((　| )*\n){3,}/", "\n", $com);
	if(!BR_CHECK || substr_count($com,"\n") < BR_CHECK) $com = nl2br($com); // 換行字元用<br />代替
	$com = str_replace("\n", '', $com); // 若還有\n換行字元則取消換行
	// 預設的內容
	if(!$name || preg_match("/^[ |　|]*$/", $name)){
		if(ALLOW_NONAME) $name = DEFAULT_NONAME;
		else error(_T('regist_withoutname'), $dest);
	}
	if(!$sub || preg_match("/^[ |　|]*$/", $sub)) $sub = DEFAULT_NOTITLE;
	if(!$com || preg_match("/^[ |　|\t]*$/", $com)) $com = DEFAULT_NOCOMMENT;
	// 修整標籤樣式
	if($category && USE_CATEGORY){
		$category = explode(',', $category); // 把標籤拆成陣列
		$category = ','.implode(',', array_map('trim', $category)).','; // 去空白再合併為單一字串 (左右含,便可以直接以,XX,形式搜尋)
	}else{ $category = ''; }
	if($up_incomplete) $com .= '<br /><br /><span class="warn_txt">'._T('notice_incompletefile').'</span>'; // 上傳附加圖檔不完全的提示

	// 密碼和時間的樣式
	if($pwd=='') $pwd = ($pwdc=='') ? substr(rand(),0,8) : $pwdc;
	$pass = $pwd ? substr(md5($pwd), 2, 8) : '*'; // 生成真正儲存判斷用的密碼
	$youbi = array(_T('sun'),_T('mon'),_T('tue'),_T('wed'),_T('thu'),_T('fri'),_T('sat'));
	$yd = $youbi[gmdate('w', $time+TIME_ZONE*60*60)];
	$now = gmdate('y/m/d', $time+TIME_ZONE*60*60).'('.(string)$yd.')'.gmdate('H:i', $time+TIME_ZONE*60*60);
	if(DISP_ID){ // 顯示ID
		if($email && DISP_ID==1) $now .= ' ID:???';
		else $now .= ' ID:'.substr(crypt(md5(getREMOTE_ADDR().IDSEED.gmdate('Ymd', $time+TIME_ZONE*60*60)),'id'), -8);
	}
echo __LINE__;
	// 連續投稿 / 相同附加圖檔檢查
	$checkcount = 50; // 預設檢查50筆資料
	$pwdc = substr(md5($pwdc), 2, 8); // Cookies密碼
	if($PIO->isSuccessivePost($checkcount, $com, $time, $pass, $pwdc, $host, $upfile_name)) error(_T('regist_successivepost'), $dest); // 連續投稿檢查
	if($dest){ if($PIO->isDuplicateAttachment($checkcount, $md5chksum)) error(_T('regist_duplicatefile'), $dest); } // 相同附加圖檔檢查
	if($resto) $ThreadExistsBefore = $PIO->isThread($resto);
echo __LINE__;
	// 舊文章刪除處理
//	if(PIOSensor::check('delete', $LIMIT_SENSOR)){
//		$delarr = PIOSensor::listee('delete', $LIMIT_SENSOR);
//		if(count($delarr)){
//			deleteCache($delarr);
//			$PMS->useModuleMethods('PostOnDeletion', array($delarr, 'recycle')); // "PostOnDeletion" Hook Point
//			$files = $PIO->removePosts($delarr);
//			if(count($files)) $FileIO->deleteImage($files);
//		}
//	}
//exit;
	// 附加圖檔容量限制功能啟動：刪除過大檔
//	if(STORAGE_LIMIT && STORAGE_MAX > 0){
//		$tmp_total_size = total_size(); // 取得目前附加圖檔使用量
//		($tmp_total_size > STORAGE_MAX){
		//	$files = $PIO->delOldAttachments($tmp_total_size, STORAGE_MAX, false);
		//	$FileIO->deleteImage($files);
//		}
//	}
//exit;
	// 判斷欲回應的文章是不是剛剛被刪掉了
	if($resto){
		if($ThreadExistsBefore){ // 欲回應的討論串是否存在
			if(!$PIO->isThread($resto)){ // 被回應的討論串存在但已被刪
				// 提前更新資料來源，此筆新增亦不紀錄
				$PIO->dbCommit();
				//updatelog();
				error(_T('regist_threaddeleted'), $dest);
			}else{ // 檢查是否討論串被設為禁止回應 (順便取出原討論串的貼文時間)
				$post = $PIO->fetchPosts($resto); // [特殊] 取單篇文章內容，但是回傳的$post同樣靠[$i]切換文章！
				list($chkstatus, $chktime) = array($post[0]['status'], $post[0]['tim']);
				$chktime = substr($chktime, 0, -3); // 拿掉微秒 (後面三個字元)
				$flgh = $PIO->getPostStatus($chkstatus);
				if($flgh->exists('TS')) error(_T('regist_threadlocked'), $dest);
			}
		}else error(_T('thread_not_found'), $dest); // 不存在
	}
echo __LINE__;
	// 計算某些欄位值
	$no = $PIO->getLastPostNo('beforeCommit') + 1;
	isset($ext) ? 0 : $ext = '';
	isset($imgW) ? 0 : $imgW = 0;
	isset($imgH) ? 0 : $imgH = 0;
	isset($imgsize) ? 0 : $imgsize = '';
	isset($W) ? 0 : $W = 0;
	isset($H) ? 0 : $H = 0;
	isset($md5chksum) ? 0 : $md5chksum = '';
	$age = false;
	$status = '';
	if($resto){
		if(!stristr($email, 'sage') && ($PIO->postCount($resto) <= MAX_RES || MAX_RES==0)){
			if(!MAX_AGE_TIME || (($time - $chktime) < (MAX_AGE_TIME * 60 * 60))) $age = true; // 討論串並無過期，推文
		}
	}
echo "aa";// 正式寫入儲存
	$PIO->addPost($no,$resto,$md5chksum,$category,$tim,$ext,$imgW,$imgH,$imgsize,$W,$H,$pass,$now,$name,$email,$sub,$com,$host,$age,$status);
	$PIO->dbCommit();
	$lastno = $PIO->getLastPostNo('afterCommit'); // 取得此新文章編號
//	$PMS->useModuleMethods('RegistAfterCommit', array($lastno, $resto, $name, $email, $sub, $com)); // "RegistAfterCommit" Hook Point
echo "fF";
	// Cookies儲存：密碼與E-mail部分，期限是一週
	setcookie('pwdc', $pwd, time()+7*24*3600);
	setcookie('emailc', $email, time()+7*24*3600);
//	total_size(true); // 刪除舊容量快取
	if($dest && is_file($dest)){
		$destFile = $path.IMG_DIR.$tim.$ext; // 圖檔儲存位置
		$thumbFile = $path.THUMB_DIR.$tim.'s.jpg'; // 預覽圖儲存位置
		rename($dest, $destFile);
		if(USE_THUMB !== 0){ // 生成預覽圖
			$thumbType = USE_THUMB; if(USE_THUMB==1){ $thumbType = 'gd'; } // 與舊設定相容
			require('./lib/thumb/thumb.'.$thumbType.'.php');
			$thObj = new ThumbWrapper($destFile, $imgW, $imgH);
			$thObj->setThumbnailConfig($W, $H, THUMB_Q);
			$thObj->makeThumbnailtoFile($thumbFile);
			@chmod($thumbFile, 0666);
			unset($thObj);
		}
		if($FileIO->uploadImage()){ // 支援上傳圖片至其他伺服器
			if(file_exists($destFile)) $FileIO->uploadImage($tim.$ext, $destFile, filesize($destFile));
			if(file_exists($thumbFile)) $FileIO->uploadImage($tim.'s.jpg', $thumbFile, filesize($thumbFile));
		}
	}
	//updatelog();

	// 引導使用者至新頁面
	$RedirURL = PHP_SELF2.'?'.$tim; // 定義儲存資料後轉址目標
	if(isset($_POST['up_series'])){ // 勾選連貼機能
		if($resto) $RedirURL = PHP_SELF.'?res='.$resto.'&amp;upseries=1'; // 回應後繼續轉回此主題下
		else{
			$RedirURL = PHP_SELF.'?res='.$lastno.'&amp;upseries=1'; // 新增主題後繼續轉到此主題下
		}
	}
	$RedirforJS = strtr($RedirURL, array("&amp;"=>"&")); // JavaScript用轉址目標

}


?>
