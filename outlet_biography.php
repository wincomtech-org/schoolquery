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



// 赋值给模板-导航栏
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
$sql = "SELECT * FROM " . $dou->table('nav').'where module="process"';
 $query = $dou->query($sql);
$about = $dou->fetch_array($query);
$ce=$about['show_img'];
$smarty->assign('ce', $ce);//获取图片
$id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
$sql = "SELECT * FROM " . $dou->table('school')."where id='$id'";
$query = $dou->query($sql);
$school=$GLOBALS['dou']->fetch_assoc($query);
print_r($school);
$smarty->assign('school', $school);
$smarty->assign('index', 'process');
$smarty->display('outlet_biography.html');


?>