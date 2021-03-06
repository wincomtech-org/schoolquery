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

// 验证并获取合法的ID，如果不合法将其设定为-1
$id = $firewall->get_legal_id('gallery', $_REQUEST['id'], $_REQUEST['unique_id']);
$cat_id = $dou->get_one("SELECT cat_id FROM " . $dou->table('gallery') . " WHERE id = '$id'");
$parent_id = $dou->get_one("SELECT parent_id FROM " . $dou->table('gallery_category') . " WHERE cat_id = '" . $cat_id . "'");
if ($id == -1)
    $dou->dou_msg($GLOBALS['_LANG']['page_wrong'], ROOT_URL);
    
/* 获取详细信息 */
$query = $dou->select($dou->table('gallery'), '*', '`id` = \'' . $id . '\'');
$gallery = $dou->fetch_array($query);

// 格式化数据
$gallery['add_time'] = date("Y-m-d", $gallery['add_time']);

// 相册图集
if (is_array($gallery_array = unserialize($gallery['gallery']))) {
    $i = 0;
    foreach ($gallery_array as $row) {
        $array[] = array (
                "image" => ROOT_URL . $row,
                "number" => $i 
        );
        $i++;
    }
    $gallery['array'] = $array;
}

// 访问统计
$click = $gallery['click'] + 1;
$dou->query("update " . $dou->table('gallery') . " SET click = '$click' WHERE id = '$id'");

// 赋值给模板-meta和title信息
$smarty->assign('page_title', $dou->page_title('gallery_category', $cat_id, $gallery['title']));
$smarty->assign('keywords', $gallery['keywords']);
$smarty->assign('description', $gallery['description']);

// 赋值给模板-导航栏
$smarty->assign('nav_top_list', $dou->get_nav('top'));
$smarty->assign('nav_middle_list', $dou->get_nav('middle', '0', 'gallery_category', $cat_id, $parent_id));
$smarty->assign('nav_bottom_list', $dou->get_nav('bottom'));

// 赋值给模板-数据
$smarty->assign('ur_here', $dou->ur_here('gallery_category', $cat_id, $gallery['title']));
$smarty->assign('gallery_category', $dou->get_category('gallery_category', 0, $cat_id));
$smarty->assign('cat_id', $cat_id);
$smarty->assign('lift', $dou->lift('gallery', $id, $cat_id));
$smarty->assign('gallery', $gallery);

$smarty->display('gallery.dwt');
?>