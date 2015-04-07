<?php
#namespace Uploader;

class Uploader {
	private $hide = ['.','..'];
	private $config;
	private $db;

	private function is_safe($filename)
	{
		$exts = ['gif', 'png', 'apng', 'jpg',
			'jpeg', 'bmp', 'svg', 'pdf'];
		if (in_array($ext, $exts))
			return true;
		else
			return false;
	}

	private function is_binary($filename)
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

	private function dirsize($dir, $recursive = false)
	{
		if ($dir[strlen($dir)-1] != '/')
			$dir .= '/';
		$size = 0;
		if ($fp = @opendir($dir)) {
			while (false !== ($file = readdir($fp))) {
				$path = $dir.$file;
				if (!in_array($file, $this->hide)) {
					if (is_dir($path) && $recursive)
						$size += $this->dirsize($path, true);
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

	public function list_users($target_dir)
	{
		if ($target_dir[strlen($target_dir)-1] != '/')
			$target_dir .= '/';

		if ($handle = opendir($target_dir)) {
			while (false !== ($dir = readdir($handle))) {
				if ((!in_array($dir, $this->hide))) {
					$dirs[] = $dir;
				}
			}
			closedir($handle);
		} else {
			return false;
		}

		foreach ($dirs as $dir) {
			$path = $target_dir.$dir;
			if ($size = $this->dirsize($path))
				$tmp[] = [
					'name' => $dir,
					'path' => $path,
					'size' => $size
				];
		}
		return $tmp;
	}

	public function list_files($target_dir)
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

	public function __destruct()
	{
		return 0;
	}
}
