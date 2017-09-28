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

// 如果存在搜索词则转入搜索页面
if ($_REQUEST['s']) {
    if ($check->is_search_keyword($keyword = trim($_REQUEST['s']))) {
        require (ROOT_PATH . 'include/search.inc.php');
    } else {
        $dou->dou_msg($_LANG['search_keyword_wrong']);
    }
}

// 赋值给模板-导航栏
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
$sql = "SELECT * FROM " . $dou->table('nav').'where module="process"';
 $query = $dou->query($sql);
$about = $dou->fetch_array($query);
$ce=$about['show_img'];
echo $ce;
$smarty->assign('ce', $ce);//获取图片

// $sql = "SELECT * FROM " . $dou->table('school').'order by sort desc limit 3';
// $query = $dou->query($sql);
// while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
//     $row['content']=mb_substr($row['content'],0,180,'UTF-8');
//     $school[]=$row;
// }
// $smarty->assign('school', $school);//获取图片
$smarty->display('outlet.html');


?>