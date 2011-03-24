<?php
function actPOSTS(){
	print_r($_POST);
	print_r($_FILE);
	global $PIO, $FileIO, $PMS, $language, $BAD_STRING, $BAD_FILEMD5, $BAD_IPADDR, $LIMIT_SENSOR;
	$dest = ''; $mes = ''; $up_incomplete = 0; $is_admin = false;
	$path = realpath('.').DIRECTORY_SEPARATOR; // ���ؿ��������m

	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		sendStatusCode(405);
		return;
		//error(_T('regist_notpost')); // �D���WPOST�覡
	}
	// ��쳴��
	$FTname = isset($_POST['name']) ? $_POST['name'] : '';
	$FTemail = isset($_POST['email']) ? $_POST['email'] : '';
	$FTsub = isset($_POST['sub']) ? $_POST['sub'] : '';
	$FTcom = isset($_POST['com']) ? $_POST['com'] : '';
	$FTreply = isset($_POST['reply']) ? $_POST['reply'] : '';
	if($FTname != 'spammer' || $FTemail != 'foo@foo.bar' || $FTsub != 'DO NOT FIX THIS' || $FTcom != 'EID OG SMAPS' || $FTreply != '') error(_T('regist_nospam'));

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

	//$PMS->useModuleMethods('RegistBegin', array(&$name, &$email, &$sub, &$com, array('file'=>&$upfile, 'path'=>&$upfile_path, 'name'=>&$upfile_name, 'status'=>&$upfile_status), array('ip'=>$ip, 'host'=>$host), $resto)); // "RegistBegin" Hook Point
	// ����GIP/Hostname/DNSBL �ˬd����
	$baninfo = '';
	if(BanIPHostDNSBLCheck($ip, $host, $baninfo)){
		//error(_T('regist_ipfiltered', $baninfo));
		sendStatusCode(403);
		return ;
		//;
	}
	// ����G����X�{����r
	foreach($BAD_STRING as $value){
		if(strpos($com, $value)!==false || strpos($sub, $value)!==false || strpos($name, $value)!==false || strpos($email, $value)!==false){
			//error(_T('regist_wordfiltered'));
			sendStatusCode(403);
			return ;
		}
	}

	// �ˬd�O�_��J����尲�W
	foreach(array($name, $email, $sub, $com) as $anti) if(anti_sakura($anti)){
		//error(_T('regist_sakuradetected'));
		sendStatusCode(403);
		return ;
	}

	// �ɶ�
	$time = time();
	$tim = $time.substr(microtime(),2,3);

	// �P�_�W�Ǫ��A
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
		case 4: // �L�W��
			if(!$resto && !isset($_POST['noimg'])) error(_T('regist_upload_noimg'));
			break;
		case 0: // �W�ǥ��`
		default:
	}

	// �p�G���W���ɮ׫h�B�z���[����
	if($upfile && (@is_uploaded_file($upfile) || @is_file($upfile))){
		// �@�E���x�s�ɮ�
		$dest = $path.$tim.'.tmp';
		@move_uploaded_file($upfile, $dest) or @copy($upfile, $dest);
		@chmod($dest, 0666);
		if(!is_file($dest)) error(_T('regist_upload_filenotfound'), $dest);

		// �G�E�P�_�W�Ǫ��[���ɳ~���O�_�����_
		$upsizeTTL = $_SERVER['CONTENT_LENGTH'];
		if(isset($_FILES['upfile'])){ // ���ǿ��Ƥ~�ݭn�p��A�קK�@�դu
			$upsizeHDR = 0;
			// �ɮ׸��|�GIE��������|�A�G�o�q���ê����o
			$tmp_upfile_path = $upfile_name;
			if($upfile_path) $tmp_upfile_path = get_magic_quotes_gpc() ? stripslashes($upfile_path) : $upfile_path;
			list(,$boundary) = explode('=', $_SERVER['CONTENT_TYPE']);
			foreach($_POST as $header => $value){ // ������ǰe���
				$upsizeHDR += strlen('--'.$boundary."\r\n");
				$upsizeHDR += strlen('Content-Disposition: form-data; name="'.$header.'"'."\r\n\r\n".(get_magic_quotes_gpc()?stripslashes($value):$value)."\r\n");
			}
			// ���[�������ǰe���
			$upsizeHDR += strlen('--'.$boundary."\r\n");
			$upsizeHDR += strlen('Content-Disposition: form-data; name="upfile"; filename="'.$tmp_upfile_path."\"\r\n".'Content-Type: '.$_FILES['upfile']['type']."\r\n\r\n");
			$upsizeHDR += strlen("\r\n--".$boundary."--\r\n");
			$upsizeHDR += $_FILES['upfile']['size']; // �ǰe���[���ɸ�ƶq
			// �W�Ǧ줸�ծt�ȶW�L HTTP_UPLOAD_DIFF�G�W�Ǫ��[���ɤ�����
			if(($upsizeTTL - $upsizeHDR) > HTTP_UPLOAD_DIFF){
				if(KILL_INCOMPLETE_UPLOAD){
					unlink($dest);
					die(_T('regist_upload_killincomp')); // ���s���������ܡA���p�ϥΪ��٬ݪ��쪺�ܤ~���|�Ǵe
				}else $up_incomplete = 1;
			}
		}

		// �T�E�ˬd�O�_���i�������ɮ�
		$size = @getimagesize($dest);
		if(!is_array($size)) error(_T('regist_upload_notimage'), $dest); // $size�����}�C�N���O����
		$imgsize = @filesize($dest); // �ɮפj�p
		$imgsize = ($imgsize>=1024) ? (int)($imgsize/1024).' KB' : $imgsize.' B'; // KB�MB���P�O
		switch($size[2]){ // �P�_�W�Ǫ��[���ɤ��榡
			case 1 : $ext = ".gif"; break;
			case 2 : $ext = ".jpg"; break;
			case 3 : $ext = ".png"; break;
			case 4 : $ext = ".swf"; break;
			case 5 : $ext = ".psd"; break;
			case 6 : $ext = ".bmp"; break;
			case 13 : $ext = ".swf"; break;
			default : $ext = ".xxx"; error(_T('regist_upload_notsupport'), $dest);
		}
		$allow_exts = explode('|', strtolower(ALLOW_UPLOAD_EXT)); // ���������[���ɰ��ɦW
		if(array_search(substr($ext, 1), $allow_exts)===false) error(_T('regist_upload_notsupport'), $dest); // �õL�b�������ɦW���C
		// ����]�w�G����W�Ǫ��[���ɤ�MD5�ˬd�X
		$md5chksum = md5_file($dest); // �ɮ�MD5
		if(array_search($md5chksum, $BAD_FILEMD5)!==FALSE) error(_T('regist_upload_blocked'), $dest); // �b����]�w���h����

		// �|�E�p����[���ɹ����Y����ܤؤo
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

	// �ˬd�����줺�e�í׾�
	if(strlen($name) > 100) error(_T('regist_nametoolong'), $dest);
	if(strlen($email) > 100) error(_T('regist_emailtoolong'), $dest);
	if(strlen($sub) > 100) error(_T('regist_topictoolong'), $dest);
	if(strlen($resto) > 10) error(_T('regist_longthreadnum'), $dest);

	// E-mail / ���D�׾�
	$email = str_replace("\r\n", '', $email); $sub = str_replace("\r\n", '', $sub);
	// �W�٭׾�
	$name = str_replace(_T('trip_pre'), _T('trip_pre_fake'), $name); // ����????���y
	$name = str_replace(CAP_SUFFIX, _T('cap_char_fake'), $name); // ����޲z��????���y
	$name = str_replace("\r\n", '', $name);
	$nameOri = $name; // �W��
	if(preg_match('/(.*?)[#��](.*)/u', $name, $regs)){ // ????(Trip)����
		$name = $nameOri = $regs[1]; $cap = strtr($regs[2], array('&amp;'=>'&'));
		$salt = preg_replace('/[^\.-z]/', '.', substr($cap.'H.', 1, 2));
		$salt = strtr($salt, ':;<=>?@[\\]^_`', 'ABCDEFGabcdef');
		$name = $name._T('trip_pre').substr(crypt($cap, $salt), -10);
	}
	if(CAP_ENABLE && preg_match('/(.*?)[#��](.*)/', $email, $aregs)){ // �޲z��????(Cap)����
		$acap_name = $nameOri; $acap_pwd = strtr($aregs[2], array('&amp;'=>'&'));
		if($acap_name==CAP_NAME && $acap_pwd==CAP_PASS){
			$name = '<span class="admin_cap">'.$name.CAP_SUFFIX.'</span>';
			$is_admin = true;
			$email = $aregs[1]; // �h�� #xx �K�X
		}
	}
	if(!$is_admin){ // �D�޲z��
		$name = str_replace(_T('admin'), '"'._T('admin').'"', $name);
		$name = str_replace(_T('deletor'), '"'._T('deletor').'"', $name);
	}
	$name = str_replace('&'._T('trip_pre'), '&amp;'._T('trip_pre'), $name); // �קK &#xxxx; �᭱�Q���� Trip �d�U & �y���ѪR���~
	// ����׾�
	if((strlen($com) > COMM_MAX) && !$is_admin) error(_T('regist_commenttoolong'), $dest);
	$com = CleanStr($com, $is_admin); // �ޤJ$is_admin�ѼƬO�]����޲z��????�ҰʮɡA���\�޲z����config�]�w�O�_�ϥ�HTML
	if(!$com && $upfile_status==4) error(_T('regist_withoutcomment'));
	$com = str_replace(array("\r\n", "\r"), "\n", $com); $com = preg_replace("/\n((�@| )*\n){3,}/", "\n", $com);
	if(!BR_CHECK || substr_count($com,"\n") < BR_CHECK) $com = nl2br($com); // ����r����<br />�N��
	$com = str_replace("\n", '', $com); // �Y�٦�\n����r���h��������
	// �w�]�����e
	if(!$name || preg_match("/^[ |�@|]*$/", $name)){
		if(ALLOW_NONAME) $name = DEFAULT_NONAME;
		else error(_T('regist_withoutname'), $dest);
	}
	if(!$sub || preg_match("/^[ |�@|]*$/", $sub)) $sub = DEFAULT_NOTITLE;
	if(!$com || preg_match("/^[ |�@|\t]*$/", $com)) $com = DEFAULT_NOCOMMENT;
	// �׾���Ҽ˦�
	if($category && USE_CATEGORY){
		$category = explode(',', $category); // ����ҩ�}�C
		$category = ','.implode(',', array_map('trim', $category)).','; // �h�ťզA�X�֬���@�r�� (���k�t,�K�i�H�����H,XX,�Φ��j�M)
	}else{ $category = ''; }
	if($up_incomplete) $com .= '<br /><br /><span class="warn_txt">'._T('notice_incompletefile').'</span>'; // �W�Ǫ��[���ɤ�����������

	// �K�X�M�ɶ����˦�
	if($pwd=='') $pwd = ($pwdc=='') ? substr(rand(),0,8) : $pwdc;
	$pass = $pwd ? substr(md5($pwd), 2, 8) : '*'; // �ͦ��u���x�s�P�_�Ϊ��K�X
	$youbi = array(_T('sun'),_T('mon'),_T('tue'),_T('wed'),_T('thu'),_T('fri'),_T('sat'));
	$yd = $youbi[gmdate('w', $time+TIME_ZONE*60*60)];
	$now = gmdate('y/m/d', $time+TIME_ZONE*60*60).'('.(string)$yd.')'.gmdate('H:i', $time+TIME_ZONE*60*60);
	if(DISP_ID){ // ���ID
		if($email && DISP_ID==1) $now .= ' ID:???';
		else $now .= ' ID:'.substr(crypt(md5(getREMOTE_ADDR().IDSEED.gmdate('Ymd', $time+TIME_ZONE*60*60)),'id'), -8);
	}

	// �s���Z / �ۦP���[�����ˬd
	$checkcount = 50; // �w�]�ˬd50�����
	$pwdc = substr(md5($pwdc), 2, 8); // Cookies�K�X
	if($PIO->isSuccessivePost($checkcount, $com, $time, $pass, $pwdc, $host, $upfile_name)) error(_T('regist_successivepost'), $dest); // �s���Z�ˬd
	if($dest){ if($PIO->isDuplicateAttachment($checkcount, $md5chksum)) error(_T('regist_duplicatefile'), $dest); } // �ۦP���[�����ˬd
	if($resto) $ThreadExistsBefore = $PIO->isThread($resto);

	// �¤峹�R���B�z
	if(PIOSensor::check('delete', $LIMIT_SENSOR)){
		$delarr = PIOSensor::listee('delete', $LIMIT_SENSOR);
		if(count($delarr)){
			deleteCache($delarr);
			$PMS->useModuleMethods('PostOnDeletion', array($delarr, 'recycle')); // "PostOnDeletion" Hook Point
			$files = $PIO->removePosts($delarr);
			if(count($files)) $FileIO->deleteImage($files);
		}
	}

	// ���[���ɮe�q����\��ҰʡG�R���L�j��
	if(STORAGE_LIMIT && STORAGE_MAX > 0){
		$tmp_total_size = total_size(); // ���o�ثe���[���ɨϥζq
		if($tmp_total_size > STORAGE_MAX){
			$files = $PIO->delOldAttachments($tmp_total_size, STORAGE_MAX, false);
			$FileIO->deleteImage($files);
		}
	}

	// �P�_���^�����峹�O���O���Q�R���F
	if($resto){
		if($ThreadExistsBefore){ // ���^�����Q�צ�O�_�s�b
			if(!$PIO->isThread($resto)){ // �Q�^�����Q�צ�s�b���w�Q�R
				// ���e��s��ƨӷ��A�����s�W�礣����
				$PIO->dbCommit();
				//updatelog();
				error(_T('regist_threaddeleted'), $dest);
			}else{ // �ˬd�O�_�Q�צ�Q�]���T��^�� (���K���X��Q�צꪺ�K��ɶ�)
				$post = $PIO->fetchPosts($resto); // [�S��] ����g�峹���e�A���O�^�Ǫ�$post�P�˾a[$i]�����峹�I
				list($chkstatus, $chktime) = array($post[0]['status'], $post[0]['tim']);
				$chktime = substr($chktime, 0, -3); // �����L�� (�᭱�T�Ӧr��)
				$flgh = $PIO->getPostStatus($chkstatus);
				if($flgh->exists('TS')) error(_T('regist_threadlocked'), $dest);
			}
		}else error(_T('thread_not_found'), $dest); // ���s�b
	}

	// �p��Y������
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
			if(!MAX_AGE_TIME || (($time - $chktime) < (MAX_AGE_TIME * 60 * 60))) $age = true; // �Q�צ�õL�L���A����
		}
	}

}


?>
