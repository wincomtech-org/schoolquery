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

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'score');

/**
 * +----------------------------------------------------------
 * 分类列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['score_type']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['score_type_add'],
            'href' => 'score.php?rec=add' 
    ));
    
    // 赋值给模板
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('score').'order by id desc';
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $row['addtime']=date("Y-m-d H:i:s",$row['addtime']);
        $sty[]=$row;
    }
    $smarty->assign('school_type', $sty);
    $smarty->display('score.htm');
} 

/**
 * +----------------------------------------------------------
 * 分类添加
 * +----------------------------------------------------------
 */
elseif ($rec == 'add') {
    $smarty->assign('ur_here', $_LANG['score_type_add']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['score_type'],
            'href' => 'score.php' 
    ));
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    
    // 赋值给模板
    $smarty->assign('form_action', 'insert');
    //$smarty->assign('product_category', $dou->get_category_nolevel('product_category'));
    
    $smarty->display('score.htm');
} 

elseif ($rec == 'insert') {
    if (empty($_POST['cat_name']))
        $dou->dou_msg($_LANG['score_type_name'] . $_LANG['is_empty']);
    
    // if (!$check->is_unique_id($_POST['unique_id']))
    //     $dou->dou_msg($_LANG['unique_id_wrong']);
        
    // if ($dou->value_exist('product_category', 'unique_id', $_POST['unique_id']))
    //     $dou->dou_msg($_LANG['unique_id_existed']);
        
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token']);
    $time=time();
    $sql = "INSERT INTO " . $dou->table('score') . " (id, name, content, addtime, sort)" . " VALUES (NULL, '$_POST[cat_name]', '$_POST[content]', '$time','$_POST[sort]')";
    $dou->query($sql);
    
    $dou->create_admin_log($_LANG['score_type_add'] . ': ' . $_POST[cat_name]);
    $dou->dou_msg($_LANG['score_type_add_succes'], 'score.php');
} 

/**
 * +----------------------------------------------------------
 * 分类编辑
 * +----------------------------------------------------------
 */
elseif ($rec == 'edit') {
    $smarty->assign('ur_here', $_LANG['score_type_edit']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['score_type'],
            'href' => 'score.php' 
    ));
    
    // 获取分类信息
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
    $query = $dou->select($dou->table('score'), '*', '`id` = \'' . $id . '\'');
    $cat_info = $dou->fetch_array($query);
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    
    // 赋值给模板
    $smarty->assign('form_action', 'update');
    // $smarty->assign('product_category', $dou->get_category_nolevel('product_category', '0', '0', $cat_id));
    $smarty->assign('cat_info', $cat_info);
    
    $smarty->display('score.htm');
} 

elseif ($rec == 'update') {
    if (empty($_POST['cat_name']))
        $dou->dou_msg($_LANG['score_type_name'] . $_LANG['is_empty']);

    // if (!$check->is_unique_id($_POST['unique_id']))
    //     $dou->dou_msg($_LANG['unique_id_wrong']);

    // if ($dou->value_exist('product_category', 'unique_id', $_POST['unique_id'], "AND cat_id != '$_POST[cat_id]'"))
    //     $dou->dou_msg($_LANG['unique_id_existed']);
        
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token']);
    //$time=time();
    $sql = "update " . $dou->table('score') . " SET name = '$_POST[cat_name]',  content = '$_POST[content]', sort = '$_POST[sort]' WHERE id = '$_POST[id]'";
    $dou->query($sql);
    
    $dou->create_admin_log($_LANG['score_type_edit'] . ': ' . $_POST['cat_name']);
    $dou->dou_msg($_LANG['score_type_edit_succes'], 'score.php');
} 

/**
 * +----------------------------------------------------------
 * 分类删除
 * +----------------------------------------------------------
 */
elseif ($rec == 'del') {
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'score.php');
    $name = $dou->get_one("SELECT name FROM " . $dou->table('score') . " WHERE id = '$id'");
    // $is_parent = $dou->get_one("SELECT id FROM " . $dou->table('product') . " WHERE cat_id = '$cat_id'") .
    //          $dou->get_one("SELECT cat_id FROM " . $dou->table('product_category') . " WHERE parent_id = '$cat_id'");
    // if ($is_parent) {
    //     $_LANG['product_category_del_is_parent'] = preg_replace('/d%/Ums', $cat_name, $_LANG['product_category_del_is_parent']);
    //     $dou->dou_msg($_LANG['product_category_del_is_parent'], 'product_category.php', '', '3');
    // } else {
        if (isset($_POST['confirm']) ? $_POST['confirm'] : '') {
            $dou->create_admin_log($_LANG['score_type_del'] . ': ' . $name);
            $dou->delete($dou->table('score'), "id = $id", 'score.php');
        } else {
            $_LANG['del_check'] = preg_replace('/d%/Ums', $name, $_LANG['del_check']);
            $dou->dou_msg($_LANG['del_check'], 'score.php', '', '30', "score.php?rec=del&id=$id");
        }
    // }
}
?>