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
// if (isset($_REQUEST['mobile'])) {
//     setcookie('client', 'pc');
//     if ($_COOKIE['client'] != 'pc') $_COOKIE['client'] = 'pc';
// }

require (dirname(__FILE__) . '/include/init.php');

if(!isset($_SESSION['pass'])){
	echo '';
	exit;
}
// 赋值给模板-导航栏
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
$sql = "SELECT * FROM " . $dou->table('nav').'where module="local_school"';
 $query = $dou->query($sql);
$about = $dou->fetch_array($query);
$ce=$about['show_img'];

$smarty->assign('ce', $ce);//获取首页图片
$id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
$sql="SELECT * FROM".$dou->table('course')." where id='$id'";
$query=$dou->query($sql);
$course=$dou->fetch_array($query);
$course['open_data']=date('Y年m月',$course['open_data']);
$sql="SELECT name FROM".$dou->table('school')." where id='$course[shid]'";
$query=$dou->query($sql);
$school=$dou->fetch_array($query);
print_r($course);
$smarty->assign('school',$school);
$smarty->assign('course',$course);
$smarty->display('studis_detail.html');

?>