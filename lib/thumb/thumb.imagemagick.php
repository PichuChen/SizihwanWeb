<?php
/**
 * Thumbnail Generate API: ImageMagick Wrapper
 *
 * 提供程式便於以 ImageMagick 命令列生成預覽圖的物件
 *
 * @package PMCLibrary
 * @version $Id: thumb.imagemagick.php 496 2007-08-05 11:16:12Z scribe $
 * @date $Date: 2007-08-05 19:16:12 +0800 (星期日, 05 八月 2007) $
 */

class ThumbWrapper{
	var $sourceFile, $sourceWidth, $sourceHeight, $thumbWidth, $thumbHeight, $thumbQuality;
	var $_exec;

	function ThumbWrapper($sourceFile='', $sourceWidth=0, $sourceHeight=0){
		$this->sourceFile = $sourceFile;
		$this->sourceWidth = $sourceWidth;
		$this->sourceHeight = $sourceHeight;
		$this->_exec = 'convert'; // ImageMagick "convert" Binary Location
	}

	function getClass(){
		$str = 'ImageMagick Wrapper';
		if($this->isWorking()){
			$a = null;
			preg_match('/^Version: ImageMagick (.*?) [hf]/', `$this->_exec -version`, $a);
			$str .= ' : '.$a[1];
			unset($a);
		}
		return $str;
	}

	function isWorking(){
		if(!function_exists('exec')) return false;
		@exec("$this->_exec -version", $status, $retval);
		return ($retval===0);
	}

	function setThumbnailConfig($thumbWidth, $thumbHeight, $thumbQuality=50){
		$this->thumbWidth = $thumbWidth;
		$this->thumbHeight = $thumbHeight;
		$this->thumbQuality = $thumbQuality;
	}

	function makeThumbnailtoFile($destFile){
		if(!$this->isWorking()) return false;
		$CLI = "$this->_exec -thumbnail {$this->thumbWidth}x{$this->thumbHeight} -quality $this->thumbQuality \"$this->sourceFile\" \"$destFile\"";
		@exec($CLI);
		return true;
	}
}
?>