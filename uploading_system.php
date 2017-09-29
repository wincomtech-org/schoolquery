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


require (dirname(__FILE__) . '/include/init.php');
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 赋值给模板-导航栏
if($rec=='default'){
	$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
	$sql = "SELECT * FROM " . $dou->table('nav').'where module="process"';
	 $query = $dou->query($sql);
	$about = $dou->fetch_array($query);
	$ce=$about['show_img'];

	$smarty->assign('ce', $ce);//获取首页图片
	


	$smarty->display('uploading_system.html');
}
if($rec=='load'){
	
	if(empty($_POST['zh_name'])){
		echo "<script>alert('中文名不能为空');history.go(-1);</script>";
		exit;
	}
	if(empty($_POST['en_name'])){
		echo "<script>alert('英文名不能为空');history.go(-1);</script>"; 
		exit; 
	}
	if(empty($_POST['date'])){
		echo "<script>alert('英文名不能为空');history.go(-1);</script>";  
		exit;
	}
	if(empty($_POST['nation'])){
		echo "<script>alert('国籍不能为空');history.go(-1);</script>"; 
		exit; 
	}
	if(empty($_POST['register'])){
		echo "<script>alert('户籍不能为空');history.go(-1);</script>"; 
		exit; 
	}
	if(empty($_POST['shenfen'])){
		echo "<script>alert('身份证号不能为空');history.go(-1);</script>";  
		exit;
	}
	if(empty($_POST['txz'])){
		echo "<script>alert('通行证号不能为空');history.go(-1);</script>";  
		exit;
	}
	if(empty($_POST['neidi_tel'])){
		echo "<script>alert('内地手机号不能为空');history.go(-1);</script>"; 
		exit; 
	}
	if(empty($_POST['address'])){
		echo "<script>alert('内地住址不能为空');history.go(-1);</script>";
		exit;  
	}
	if(empty($_POST['school_name']) || empty($_POST['school_address']) || empty($_POST['school_tel']) || empty($_POST['school_year']) || empty($_POST['school_time']) || empty($_POST['education'])){
		echo "<script>alert('学校信息不完整');history.go(-1);</script>";
		exit;
	}
	$age = strtotime($_POST['date']); 
	if($age === false){ 
	  return false; 
	 } 
	 list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age)); 
	 $now = strtotime("now"); 
	 list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now)); 
	 $age = $y2 - $y1; 
	 if((int)($m2.$d2) < (int)($m1.$d1)) 
	  $age -= 1; 
	 if($age<18){
	 	if(empty($_POST['tutor_zh_name'])){
			echo "<script>alert('监护人中文名不能为空');history.go(-1);</script>";  
			exit;
		}
		if(empty($_POST['tutor_en_name'])){
			echo "<script>alert('监护人英文名不能为空');history.go(-1);</script>"; 
			exit; 
		}
		if(empty($_POST['tutor_shenfen'])){
			echo "<script>alert('监护人身份证号不能为空');history.go(-1);</script>"; 
			exit; 
		}
		if(empty($_POST['tutor_txz'])){
			echo "<script>alert('监护人通行证或护照号不能为空');history.go(-1);</script>"; 
			exit; 
		}
	
		if(empty($_POST['tutor_work'])){
			echo "<script>alert('监护人职业不能为空');history.go(-1);</script>";  
			exit;
		}
		if(empty($_POST['tutor_relation'])){
			echo "<script>alert('与客户关系不能为空');history.go(-1);</script>"; 
			exit; 
		}
	}
	print_r($_POST['school_name']);
	$_POST['school_name']=implode('|',$_POST['school_name']);
	$_POST['school_address']=implode('|',$_POST['school_address']);
	$_POST['school_tel']=implode('|',$_POST['school_tel']);
	$_POST['school_year']=implode('|',$_POST['school_year']);
	$_POST['school_time']=implode('|',$_POST['school_time']);
	$_POST['education']=implode('|',$_POST['education']);
	$_POST['work_name']=implode('|',$_POST['work_name']);
	$_POST['work_address']=implode('|',$_POST['work_address']);
	$_POST['work_tel']=implode('|',$_POST['work_tel']);
	$_POST['work_year']=implode('|',$_POST['work_year']);
	$_POST['work_time']=implode('|',$_POST['work_time']);
	$_POST['position']=implode('|',$_POST['position']);
	echo $_POST['school_name'];
	 $sql = "INSERT INTO " . $dou->table('customer') . " (id,name,engname,sex,data,nation, contry, id_number,pass_check,phone,g_tel, n_adress ,g_adress, gid, school_name,school_adress,school_tel,school_year,school_time,education,work_name,work_adress,work_tel,work_year,work_time,position,tutor_name,tutor_engname,tutor_sex,tutor_id,tutor_pass,tutor_gid,tutor_work,relation,tutor_year,know_gd,special,health)" . " VALUES (NULL,'$_POST[zh_name]','$_POST[en_name]','$_POST[sex]','$_POST[date]','$_POST[nation]','$_POST[register]','$_POST[shenfen]','$_POST[txz]','$_POST[neidi_tel]','$_POST[hokong_tel]','$_POST[address]','$_POST[gaddress]','$_POST[gid]','$_POST[school_name]','$_POST[school_address]','$_POST[school_tel]','$_POST[school_year]','$_POST[school_time]','$_POST[education]','$_POST[work_name]','$_POST[work_address]','$_POST[work_tel]','$_POST[work_year]','$_POST[work_time]','$_POST[position]','$_POST[tutor_zh_name]','$_POST[tutor_en_name]','$_POST[tutor_sex]','$_POST[tutor_shenfen]','$_POST[tutor_txz]','$_POST[tutor_hongkong_shenfen]','$_POST[tutor_work]','$_POST[tutor_relation]','$_POST[age]','$_POST[understand]','$_POST[stduy_ness]','$_POST[health_ness]')";
    if($dou->query($sql)){
    	$sqls="SELECT id FROM".$dou->table('customer')."where name='$_POST[zh_name]'";
    	$query=$dou->query($sqls);
    	$cus=$GLOBALS['dou']->fetch_assoc($query);
    	$id=$cus[id];
    	$_SESSION['cusid']=$id;
    	$smarty->assign('id',$id);
    	$smarty->assign('age',$age);
    	$smarty->display('uploading.html');
    }
} 




?>