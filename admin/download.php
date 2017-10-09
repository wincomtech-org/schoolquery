<?php
	/********************
	*@file - path to file
	*/
	//header("Content-type: text/html; charset=utf-8"); 
    define('IN_DOUCO', true);

    require (dirname(__FILE__) . '/include/init.php');
	$file=$_REQUEST['name'];
    $file=ROOT_PATH.$file;
    $name=basename($file);
	if ((isset($file))&&(file_exists($file))) {
		header("Content-length: ".filesize($file));
		header('Content-Type: application/octet-stream');
        header("Accept-Ranges: bytes");
		header('Content-Disposition: attachment; filename="' . $name . '"');
		readfile("$file");
	} else {
		echo "您要下载的文件已不存在，可能是被删除";
	}
?>