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
$smarty->assign('cur', 'area');

/**
 * +----------------------------------------------------------
 * 分类列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['area']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['area_add'],
            'href' => 'area.php?rec=add' 
    ));
    
    // 赋值给模板
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('area').'order by id desc';
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $row['addtime']=date("Y-m-d H:i:s",$row['addtime']);
        $sty[]=$row;
    }
    $smarty->assign('area', $sty);
    $smarty->display('area.htm');
} 

/**
 * +----------------------------------------------------------
 * 分类添加
 * +----------------------------------------------------------
 */
elseif ($rec == 'add') {
    $smarty->assign('ur_here', $_LANG['area_add']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['area'],
            'href' => 'area.php' 
    ));
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    
    // 赋值给模板
    $smarty->assign('form_action', 'insert');
    $smarty->assign('product_category', $dou->get_category_nolevel('product_category'));
    
    $smarty->display('area.htm');
} 

elseif ($rec == 'insert') {
    if (empty($_POST['cat_name']))
        $dou->dou_msg($_LANG['area_name'] . $_LANG['is_empty']);
    
    // if (!$check->is_unique_id($_POST['unique_id']))
    //     $dou->dou_msg($_LANG['unique_id_wrong']);
        
    // if ($dou->value_exist('product_category', 'unique_id', $_POST['unique_id']))
    //     $dou->dou_msg($_LANG['unique_id_existed']);
        
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token']);
    $time=time();
    $sql = "INSERT INTO " . $dou->table('area') . " (id, name, content, addtime,sort,is_show)" . " VALUES (NULL, '$_POST[cat_name]', '$_POST[content]', '$time','$_POST[sort]','$_POST[show]')";
    $dou->query($sql);
    
    $dou->create_admin_log($_LANG['area_add'] . ': ' . $_POST[cat_name]);
    $dou->dou_msg($_LANG['area_add_succes'], 'area.php');
} 

/**
 * +----------------------------------------------------------
 * 分类编辑
 * +----------------------------------------------------------
 */
elseif ($rec == 'edit') {
    $smarty->assign('ur_here', $_LANG['area_edit']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['area'],
            'href' => 'area.php' 
    ));
    
    // 获取分类信息
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
    $query = $dou->select($dou->table('area'), '*', '`id` = \'' . $id . '\'');
    $cat_info = $dou->fetch_array($query);
    //print_r($cat_info);
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    
    // 赋值给模板
    $smarty->assign('form_action', 'update');
    // $smarty->assign('product_category', $dou->get_category_nolevel('product_category', '0', '0', $cat_id));
    $smarty->assign('cat_info', $cat_info);
    
    $smarty->display('area.htm');
} 

elseif ($rec == 'update') {
    if (empty($_POST['cat_name']))
        $dou->dou_msg($_LANG['area_name'] . $_LANG['is_empty']);

    // if (!$check->is_unique_id($_POST['unique_id']))
    //     $dou->dou_msg($_LANG['unique_id_wrong']);

    // if ($dou->value_exist('product_category', 'unique_id', $_POST['unique_id'], "AND cat_id != '$_POST[cat_id]'"))
    //     $dou->dou_msg($_LANG['unique_id_existed']);
        
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token']);
    //$time=time();
    $sql = "update " . $dou->table('area') . " SET name = '$_POST[cat_name]',  content = '$_POST[content]',is_show = '$_POST[show]', sort = '$_POST[sort]' WHERE id = '$_POST[id]'";
    $dou->query($sql);
    
    $dou->create_admin_log($_LANG['area_edit'] . ': ' . $_POST['cat_name']);
    $dou->dou_msg($_LANG['area_edit_succes'], 'area.php');
} 

/**
 * +----------------------------------------------------------
 * 分类删除
 * +----------------------------------------------------------
 */
elseif ($rec == 'del') {
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'area.php');
    $name = $dou->get_one("SELECT name FROM " . $dou->table('area') . " WHERE id = '$id'");
    if($id==1){
        $dou->dou_msg('该地区不能删除');
    }else{
         if (isset($_POST['confirm']) ? $_POST['confirm'] : '') {
            $dou->create_admin_log($_LANG['area_del'] . ': ' . $name);
            $dou->delete($dou->table('area'), "id = $id", 'area.php');
        } else {
            $_LANG['del_check'] = preg_replace('/d%/Ums', $name, $_LANG['del_check']);
            $dou->dou_msg($_LANG['del_check'], 'area.php', '', '30', "area.php?rec=del&id=$id");
        }
    }
       
}
?>