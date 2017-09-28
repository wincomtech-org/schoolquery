<?php
define('IN_DOUCO', true);
require (dirname(__FILE__) . '/include/init.php');
include_once (ROOT_PATH . 'include/upload.class.php');
$images_dir = 'images/client/'; // 文件上传路径，结尾加斜杠
$thumb_dir = ''; // 缩略图路径（相对于$images_dir） 结尾加斜杠，留空则跟$images_dir相同
$img = new Upload(ROOT_PATH . $images_dir, $thumb_dir); // 实例化类文件
if (!file_exists(ROOT_PATH . $images_dir)) {
    mkdir(ROOT_PATH . $images_dir, 0777);
}
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';
if($rec=='load'){
if(isset($_POST['upload'])){
	if(move_uploaded_file($_FILES['file']['tmp_name'], 'images/client/'.basename($_FILES['file']['name']))){
		echo json_encode(['state'=>1,'path'=>$_FILES]);die;
	}
		echo json_encode(['state'=>1,'path'=>$_FILES['file']['tmp_name']]);die;
	}else{
		echo json_encode(['state'=>1,'path'=>'/']);die;
	}
}



?>