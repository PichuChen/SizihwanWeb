<?php
function getThread($thread_num,$page_num){
	require("./lib/lib_pio.php");
	global $PIO, $FileIO, $language, $LIMIT_SENSOR;

	$resno = intval($thread_num); // 編號數字化
	$page_start = $page_end = 0; // 靜態頁面編號
	$inner_for_count = 1; // 內部迴圈執行次數
	$RES_start = $RES_amount = $hiddenReply = $tree_count = 0;
	$kill_sensor = $old_sensor = false; // 預測系統啟動旗標
	$arr_kill = $arr_old = array(); // 過舊編號陣列

	if(!$PIO->isThread($resno)){ error(_T('thread_not_found')); }
	$AllRes = isset($_GET['page_num']) && $_GET['page_num']=='0'; // 是否使用 ALL 全部輸出

	// 計算回應分頁範圍
	$tree_count = $PIO->postCount($resno) - 1; // 討論串回應個數
	if($tree_count && RE_PAGE_DEF){ // 有回應且RE_PAGE_DEF > 0才做分頁動作
		if($page_num==='0'){ // show all
			$page_num = 0;
			$RES_start = 1; $RES_amount = $tree_count;
		}else{
			if($page_num==='RE_PAGE_MAX') $page_num = ceil($tree_count / RE_PAGE_DEF) - 1; // 特殊值：最末頁
			if($page_num < 0) $page_num = 0; // 負數
			if($page_num * RE_PAGE_DEF >= $tree_count) error(_T('page_not_found'));
			$RES_start = $page_num * RE_PAGE_DEF + 1; // 開始
			$RES_amount = RE_PAGE_DEF; // 取幾個
		}
	}else if($page_num > 0) error(_T('page_not_found')); // 沒有回應的情況只允許page_num = 0 或負數
	else{ $RES_start = 1; $RES_amount = $tree_count; $page_num = 0; } // 輸出全部回應
/*
	if(USE_RE_CACHE && !$adminMode){ // 檢查快取是否仍可使用 / 頁面有無更動
		$cacheETag = md5(($AllRes ? 'all' : $page_num).'-'.$tree_count); // 最新狀態快取用 ETag
		$cacheFile = './cache/'.$resno.'-'.($AllRes ? 'all' : $page_num).'.'; // 暫存快取檔位置
		$cacheGzipPrefix = extension_loaded('zlib') ? 'compress.zlib://' : ''; // 支援 Zlib Compression Stream 就使用
		$cacheControl = isset($_SERVER['HTTP_CACHE_CONTROL']) ? $_SERVER['HTTP_CACHE_CONTROL'] : ''; // 瀏覽器快取控制
		if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == '"'.$cacheETag.'"'){ // 再度瀏覽而快取無更動
			header('HTTP/1.1 304 Not Modified');
			header('ETag: "'.$cacheETag.'"');
			return;
		}elseif(file_exists($cacheFile.$cacheETag) && $cacheControl != 'no-cache'){ // 有(更新的)暫存快取檔存在 (未強制no-cache)
			header('X-Cache: HIT from Pixmicat!');
			header('ETag: "'.$cacheETag.'"');
			header('Connection: close');
			readfile($cacheGzipPrefix.$cacheFile.$cacheETag); return;
		}else{
			header('X-Cache: MISS from Pixmicat!');
		}
	}*/

	// 預測過舊文章和將被刪除檔案
	if(PIOSensor::check('predict', $LIMIT_SENSOR)){ // 是否需要預測
		$old_sensor = true; // 標記打開
		$arr_old = array_flip(PIOSensor::listee('predict', $LIMIT_SENSOR)); // 過舊文章陣列
	}
	$tmp_total_size = total_size(); // 目前附加圖檔使用量
	$tmp_STORAGE_MAX = STORAGE_MAX * (($tmp_total_size >= STORAGE_MAX) ? 1 : 0.95); // 預估上限值
	if(STORAGE_LIMIT && STORAGE_MAX > 0 && ($tmp_total_size >= $tmp_STORAGE_MAX)){
		$kill_sensor = true; // 標記打開
		$arr_kill = $PIO->delOldAttachments($tmp_total_size, $tmp_STORAGE_MAX); // 過舊附檔陣列
	}

	//$PMS->useModuleMethods('ThreadFront', array(&$pte_vals['{$THREADFRONT}'], $resno)); // "ThreadFront" Hook Point
	//$PMS->useModuleMethods('ThreadRear', array(&$pte_vals['{$THREADREAR}'], $resno)); // "ThreadRear" Hook Point

	// 生成靜態頁面一頁份內容
	for($page = $page_start; $page <= $page_end; $page++){
		//$dat = ''; $pte_vals['{$THREADS}'] = '';
		//head($dat, $resno);
		//form($dat, $resno);
		// 輸出討論串內容
		for($i = 0; $i < $inner_for_count; $i++){
			// 取出討論串編號
			if($resno) $tID = $resno; // 單討論串輸出 (回應模式)
			else{
				if($page_num == -1 && ($page * PAGE_DEF + $i) >= $threads_count) break; // remake 超出索引代表已全部完成
				$tID = ($page_start==$page_end) ? $threads[$i] : $threads[$page * PAGE_DEF + $i]; // 一頁內容 (一般模式) / 多頁內容 (remake模式)
				$tree_count = $PIO->postCount($tID) - 1; // 討論串回應個數
				$RES_start = $tree_count - RE_DEF + 1; if($RES_start < 1) $RES_start = 1; // 開始
				$RES_amount = RE_DEF; // 取幾個
				$hiddenReply = $RES_start - 1; // 被隱藏回應數
			}

			// $RES_start, $RES_amount 拿去算新討論串結構 (分頁後, 部分回應隱藏)
			$tree = $PIO->fetchPostList($tID); // 整個討論串樹狀結構
			$tree_cut = array_slice($tree, $RES_start, $RES_amount); array_unshift($tree_cut, $tID); // 取出特定範圍回應
			$posts = $PIO->fetchPosts($tree_cut); // 取得文章架構內容
			//$pte_vals['{$THREADS}'] .= arrangeThread($PTE, $tree, $tree_cut, $posts, $hiddenReply, $resno, $arr_kill, $arr_old, $kill_sensor, $old_sensor, true, $adminMode); // 交給這個函式去搞討論串印出
		}
		//$pte_vals['{$PAGENAV}'] = '<div id="page_switch">';

		// 換頁判斷
		$prev = $page_num - 1;
		$next = $page_num + 1;
		if($resno){ // 回應分頁
			if(RE_PAGE_DEF > 0){ // 回應分頁開啟
				$pte_vals['{$PAGENAV}'] .= '<table border="1"><tr><td style="white-space: nowrap;">';
				$pte_vals['{$PAGENAV}'] .= ($prev >= 0) ? '<a href="'.PHP_SELF.'?res='.$resno.'&amp;page_num='.$prev.'">'._T('prev_page').'</a>' : _T('first_page');
				$pte_vals['{$PAGENAV}'] .= "</td><td>";
				if($tree_count==0) $pte_vals['{$PAGENAV}'] .= '[<b>0</b>] '; // 無回應
				else{
					for($i = 0, $len = $tree_count / RE_PAGE_DEF; $i < $len; $i++){
						if(!$AllRes && $page_num==$i) $pte_vals['{$PAGENAV}'] .= '[<b>'.$i.'</b>] ';
						else $pte_vals['{$PAGENAV}'] .= '[<a href="'.PHP_SELF.'?res='.$resno.'&amp;page_num='.$i.'">'.$i.'</a>] ';
					}
					$pte_vals['{$PAGENAV}'] .= $AllRes ? '[<b>'._T('all_pages').'</b>] ' : ($tree_count > RE_PAGE_DEF ? '[<a href="'.PHP_SELF.'?res='.$resno.'&amp;page_num=all">'._T('all_pages').'</a>] ' : '');
				}
				$pte_vals['{$PAGENAV}'] .= '</td><td style="white-space: nowrap;">';
				$pte_vals['{$PAGENAV}'] .= (!$AllRes && $tree_count > $next * RE_PAGE_DEF) ? '<a href="'.PHP_SELF.'?res='.$resno.'&amp;page_num='.$next.'">'._T('next_page').'</a>' : _T('last_page');
				$pte_vals['{$PAGENAV}'] .= '</td></tr></table>'."\n";
			}
		}else{
		}
		//$pte_vals['{$PAGENAV}'] .= '<br style="clear: left;" /></div>';
		//$dat .= $PTE->ParseBlock('MAIN', $pte_vals);
		//foot($dat);

		// 存檔 / 輸出
		if($single_page){ // 靜態快取頁面生成
			if($page==0) $logfilename = PHP_SELF2;
			else $logfilename = $page.PHP_EXT;
			$fp = fopen($logfilename, 'w');
			stream_set_write_buffer($fp, 0);
			fwrite($fp, $dat);
			fclose($fp);
			@chmod($logfilename, 0666);
			if(STATIC_HTML_UNTIL != -1 && STATIC_HTML_UNTIL==$page) break; // 頁面數目限制
		}else{ // PHP 輸出 (回應模式/一般動態輸出)
			if(USE_RE_CACHE && $resno && !isset($_GET['upseries'])){ // 更新快取
				if($oldCaches = glob($cacheFile.'*')){
					foreach($oldCaches as $o) unlink($o); // 刪除舊快取
				}
				$fp = fopen($cacheGzipPrefix.$cacheFile.$cacheETag, 'w');
				fwrite($fp, $dat);
				fclose($fp);
				@chmod($cacheFile.$cacheETag, 0666);
				header('ETag: "'.$cacheETag.'"');
				header('Connection: close');
			}
			break;
		}
	}
}


?>