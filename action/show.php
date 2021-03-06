<?php
function actSHOW($page_num){ 
	require("./lib/lib_pio.php");
	//print_r($PIO->fetchThreadList());
	$SelectArgv = 'no,resto,sub,name,com,now,category,tim,ext,imgsize,tw,th,imgw,imgh';
	//$SelectArgv = "no,sub";
//	print_r($PIO->threadCount());
//print_r($PIO->fetchPostList());
	$page_num = intval($page_num);
	$resno = 0;$page = 1;
	$page_start = 0;
	$threads = array();
	$out = array();
	
	 // 討論串分頁模式 (PHP動態輸出一頁份)
	$threads_count = $PIO->threadCount(); // 討論串個數
	if($page_num < 0 || ($page_num * PAGE_DEF) >= $threads_count) error(_T('page_not_found')); // $page_num超過範圍
	$page_start = $page_end = $page_num; // 設定靜態頁面編號
	$threads = $PIO->fetchThreadList(); // 取得全討論串列表
	$threads = array_splice($threads, $page_num * PAGE_DEF, PAGE_DEF); // 取出分頁後的討論串首篇列表
	$inner_for_count = count($threads); // 討論串個數就是迴圈次數
		
	for($i = 0; $i < $inner_for_count; $i++){
		// 取出討論串編號
		//	if($page_num == -1 && ($page * PAGE_DEF + $i) >= $threads_count) break; // remake 超出索引代表已全部完成
		$tID = ($page_start==$page_end) ? $threads[$i] : $threads[$page * PAGE_DEF + $i]; // 一頁內容 (一般模式) / 多頁內容 (remake模式)
	//	print_r($threads);
		//	print_r($tID);
		$tree_count = $PIO->postCount($tID) - 1; // 討論串回應個數
		$RES_start = $tree_count - RE_DEF + 1; if($RES_start < 1) $RES_start = 1; // 開始
		$RES_amount = RE_DEF; // 取幾個
		$hiddenReply = $RES_start - 1; // 被隱藏回應數
		// $RES_start, $RES_amount 拿去算新討論串結構 (分頁後, 部分回應隱藏)
		$tree = $PIO->fetchPostList($tID); // 整個討論串樹狀結構
		$tree_cut = array_slice($tree, $RES_start, $RES_amount); array_unshift($tree_cut, $tID); // 取出特定範圍回應
		$posts = $PIO->fetchPosts($tree_cut); // 取得文章架構內容
		$out[$i] = $posts;
		$out[$i][0]['hiddenReply'] = $hiddenReply;
//		$pte_vals['{$THREADS}'] .= arrangeThread($PTE, $tree, $tree_cut, $posts, $hiddenReply, $resno, $arr_kill, $arr_old, $kill_sensor, $old_sensor, true, $adminMode); // 交給這個函式去搞討論串印出
	}
	return $out;
}

function actShowPageNum(){
	require("./lib/lib_pio.php");
	return  ceil($PIO->threadCount() / PAGE_DEF) ;

}

?>
