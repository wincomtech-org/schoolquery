<?php
/**
 * DouPHP
 * --------------------------------------------------------------------------------------------------
 * 版权所有 2013-2015 漳州豆壳网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.douco.com
 * --------------------------------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在遵守授权协议前提下对程序代码进行修改和使用；不允许对程序代码以任何形式任何目的的再发布。
 * 授权协议：http://www.douco.com/license.html
 * --------------------------------------------------------------------------------------------------
 * Author: DouCo
 * Release Date: 2015-10-16
 */
define('IN_DOUCO', true);

// 强制在移动端中显示PC版

require (dirname(__FILE__) . '/include/init.php');
$_SESSION['cusid']=999;
if($_SESSION['cusid']){
	$cusid=$_SESSION['cusid'];
}else{
	exit;
}

// 赋值给模板-导航栏
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
$sql = "SELECT * FROM " . $dou->table('nav').'where module="process"';
 $query = $dou->query($sql);
$about = $dou->fetch_array($query);
$ce=$about['show_img'];

$smarty->assign('ce', $ce);//获取首页图片
if($rec=='load'){
	$path= 'images/client/'.$cusid.'/';
	if(!is_dir($path)){
		mkdir($path,0777,true);
	}
	if(isset($_POST['upload'])){
		if(move_uploaded_file($_FILES['file']['tmp_name'], $path.basename($_FILES['file']['name']))){
			echo json_encode(['state'=>1,'path'=>$path.basename($_FILES['file']['name'])]);exit;
		}else{
			echo json_encode(['state'=>1,'path'=>'/']);die;
		}
	}else{
		echo json_encode(['state'=>2,'path'=>'/']);die;
	}
}
if($rec=='next'){
	if(empty($_POST['filePath1'])){
		echo "<script>alert('没有上传身份证明文件');history.go(-1);</script>";  
		exit;
	}
	if(empty($_POST['filePath2'])){
		echo "<script>alert('没有上传证件照');history.go(-1);</script>";  
		exit;
	}
	if(empty($_POST['filePath3'])){
		echo "<script>alert('没有上传成绩表或毕业证');history.go(-1);</script>";  
		exit;
	}
	$id=$_POST['id'];
	$age=$_POST['age'];
	$id_cent=$_POST['filePath1'];
	$id_phono=$_POST['filePath2'];
	$mark=$_POST['filePath3'];
	$work_cent==$_POST['filePath4'];
	$recom=$_POST['filePath5'];
	$engprove=$_POST['filePath6'];
	$other=$_POST['filePath7'];
	if($age<18){
		if(empty($_POST['filePath8'])){
			echo "<script>alert('没有上传监护人身份证明文件');history.go(-1);</script>";  
			exit;
		}
		if(empty($_POST['filePath9'])){
			echo "<script>alert('没有上传监护人证件照');history.go(-1);</script>";  
			exit;
		}
		$tutor_cent=$_POST['filePath8'];
		$tutor_idphono=$_POST['filePath9'];
	}else{
		$tutor_cent='';
		$tutor_idphono='';
	}
	$sql = "update " . $dou->table('customer') . " SET id_cent = '$id_cent',id_phono= '$id_phono',mark = '$mark',work_cent = '$work_cent', recom = '$recom',engprove = '$engprove' ,other = '$other', tutor_cent = '$tutor_cent', tutor_idphono = 'tutor_idphono' WHERE id = '$id'";
	
    if($dou->query($sql)){
    	$smarty->display('uploading_completed.html');
    	unset ($_SESSION['cusid']);
    	exit;
    }
}
 
$smarty->display('uploading.html');
?>