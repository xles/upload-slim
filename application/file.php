<?php
namespace Uploader;
use Slim\Slim;

class File {
	private $hide = ['.','..'];
	private $config;
	private $db;

	public static function download()
	{
		$filename = $_GET['file'];
		if(!empty($filename)) {
			if (file_exists($filename) && is_file($filename)) {
				$contentLength = filesize($filename);
				header ("Content-Type: application/octet-stream; name=" . $filename);
				header ("Content-Length: " . $contentLength);
	//    				header ("Content-Disposition: attachment; filename=poop.jpg");
				header ("Content-Disposition: attachment; filename=" . $filename);
				readfile($filename);
			} else {
				echo "<b>This file does not exist!</b>";
			}
		} else {
			echo "<b>No file selected...</b>";
		}

	}

	private function is_safe($filename)
	{
		$exts = ['gif', 'png', 'apng', 'jpg',
			'jpeg', 'bmp', 'svg', 'pdf'];
		if (in_array($ext, $exts))
			return true;
		else
			return false;
	}

	private static function is_binary($filename)
	{
		// return mime type ala mimetype extension
		$finfo = new finfo(FILEINFO_MIME);

		//check to see if the mime-type starts with 'text'
		if (substr($finfo->file($filename), 0, 4) == 'text') {
			return false;
		} else {
			return true;
		}
	}

	public static function dirsize($dir, $recursive = false)
	{
		$app = Slim::getInstance();

		if ($dir[strlen($dir)-1] != '/')
			$dir .= '/';
		$size = 0;
		if ($fp = @opendir($dir)) {
			while (false !== ($file = readdir($fp))) {
				$path = $dir.$file;
				if (!in_array($dir, $app->uploaderConfig['hide'])) {
					if (is_dir($path) && $recursive)
						$size += self::dirsize($path, true);
					else
						$size += filesize($path);
				}
			}
			closedir($fp);
			return $size;
		} else {
			return false;
		}
	}


	public static function list_files($target_dir)
	{
		if ($target_dir[strlen($target_dir)-1] != '/')
			$target_dir .= '/';

		if ($handle = opendir($target_dir)) {
			while (false !== ($file = readdir($handle))) {
				if (!in_array($file, $this->hide)) {
					$files[] = $file;
				}
			}
			closedir($handle);
		} else {
			return false;
		}

		$finfo = new finfo(FILEINFO_MIME);
		foreach ($files as $file) {
			$path = $target_dir.$file;
			$tmp[] = [
				'name'     => $file,
				'ext'      => strrchr($file, "."),
				'path'     => $path,
				'size'     => @filesize($path),
				'modified' => filemtime($path),
				'mimetype' => $finfo->file($path)
			];
		}
		return $tmp;
	}
}
