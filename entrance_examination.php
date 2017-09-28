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
if (isset($_REQUEST['mobile'])) {
    setcookie('client', 'pc');
    if ($_COOKIE['client'] != 'pc') $_COOKIE['client'] = 'pc';
}

require (dirname(__FILE__) . '/include/init.php');


// 赋值给模板-导航栏
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
$sql = "SELECT * FROM " . $dou->table('nav').'where module="entrance_examination"';
 $query = $dou->query($sql);
$about = $dou->fetch_array($query);
$ce=$about['show_img'];

$smarty->assign('ce', $ce);//获取首页图片
//首次加载默认选择第一个
$sql = "SELECT * FROM " . $dou->table('taste_type').'order by sort desc';
$query = $dou->query($sql);
$row = $GLOBALS['dou']->fetch_assoc($query);
$smarty->assign('id',$row['id']);
//获取分类
$sql = "SELECT * FROM " . $dou->table('taste_type').' order by sort desc limit 4';
$query = $dou->query($sql);
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
    $type[]=$row;
}
$sql = "SELECT * FROM " . $dou->table('taste');
$query = $dou->query($sql);
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
    $course[]=$row;
}

echo $type[0][id];
$smarty->assign('type', $type);
$smarty->assign('course', $course);


$sql = "SELECT * FROM " . $dou->table('taste').'where news_show=1 order by sort desc limit 2';
$query = $dou->query($sql);
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
		$row['content']=mb_substr($row['content'],0,60,'UTF-8');
	   $news[]=$row;
}
$smarty->assign('news', $news);//获取新闻
// 赋值给模板-数据

$smarty->assign('index', 'entrance_examination');//选中加样式
$smarty->assign('recommend_product', $dou->get_list('product', 'ALL', $_DISPLAY['home_product'], 'sort DESC'));

$smarty->assign('recommend_article', $dou->get_list('article', 'ALL', $_DISPLAY['home_article'], 'sort DESC'));

$smarty->display('entrance_examination.html');

?>