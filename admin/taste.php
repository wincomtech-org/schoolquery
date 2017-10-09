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

// 图片上传
include_once (ROOT_PATH . 'include/upload.class.php');
$images_dir = 'images/taste/'; // 文件上传路径，结尾加斜杠
$thumb_dir = ''; // 缩略图路径（相对于$images_dir） 结尾加斜杠，留空则跟$images_dir相同
$img = new Upload(ROOT_PATH . $images_dir, $thumb_dir); // 实例化类文件
if (!file_exists(ROOT_PATH . $images_dir)) {
    mkdir(ROOT_PATH . $images_dir, 0777);
}

// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'taste');

/**
 * +----------------------------------------------------------
 * 攻略列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['taste']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['taste_add'],
            'href' => 'taste.php?rec=add' 
    ));
    
    // 获取参数
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    $keyword = isset($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
    // 筛选条件
    if($id!=0){
        $where = "WHERE cid='$id'";
    }else{
        $where = ' WHERE 1=1';
    }

    if ($keyword) {
        $where = $where . " AND name LIKE '%$keyword%'";
        $get = '&keyword=' . $keyword;
    }
    
    // 分页
    $page = $check->is_number($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $page_url = 'taste.php' . ($id ? '?id=' . $id : '');
    $limit = $dou->pager('taste', 15, $page, $page_url, $where, $get);
    
    $sql = "SELECT * FROM " . $dou->table('taste') . $where . " ORDER BY id DESC" . $limit;
    $query = $dou->query($sql);
    while ($row = $dou->fetch_array($query)) {
        $styname = $dou->get_one("SELECT name FROM " . $dou->table('taste_type') . " WHERE id = '$row[cid]'");
        //$conname = $dou->get_one("SELECT name FROM " . $dou->table('area') . " WHERE id = '$row[cid]'");
      
        //  echo $row['content'];
        //$add_time = date("Y-m-d", $row['add_time']);
       $row['content']=mb_substr($row['content'],0,30,'UTF-8');
       $row['content']=strip_tags($row['content']);
        $taste[] = array (
                "id" => $row['id'],
                "name" => $row['name'],
                "news_show" => $row['news_show'],
                "styname" => $styname,
                "content" => $row['content'],
                "sort" => $row['sort'] 
        );
    }
    // // 首页显示商品数量限制框
    // for($i = 1; $i <= $_CFG['home_display_product']; $i++) {
    //     $sort_bg .= "<li><em></em></li>";
    // }
    
    // 赋值给模板
    //echo
    //$smarty->assign('if_sort', $_SESSION['if_sort']);
    //$smarty->assign('sort', get_sort_product());
    //$smarty->assign('sort_bg', $sort_bg);
    $sql = "SELECT * FROM " . $GLOBALS['dou']->table('taste_type');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $sty[]=$row;
    }
    //print
    $smarty->assign('id', $id);
    $smarty->assign('keyword', $keyword);
    $smarty->assign('sty', $sty);
    $smarty->assign('taste', $taste);
    
    $smarty->display('taste.htm');
} 

/**
 * +----------------------------------------------------------
 * 攻略添加
 * +----------------------------------------------------------
 */
elseif ($rec == 'add') {
    $smarty->assign('ur_here', $_LANG['taste_add']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['taste'],
            'href' => 'taste.php' 
    ));
    
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    $sql = "SELECT * FROM " . $GLOBALS['dou']->table('taste_type');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $sty[]=$row;
    }
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('area');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $row['addtime']=date("Y-m-d H:i:s",$row['addtime']);
        $con[]=$row;
    }
    // 赋值给模板
    $smarty->assign('form_action', 'insert');
    $smarty->assign('sty', $sty);
    $smarty->assign('con', $con);
    //print_r($product);
    $smarty->display('taste.htm');
} 

elseif ($rec == 'insert') {
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['taste_name'] . $_LANG['is_empty']);
    if($_POST['cid']==0)
        $dou->dou_msg($_LANG['taste_type'] . $_LANG['is_empty']);
    if(empty($_POST['content']))
        $dou->dou_msg($_LANG['taste_content'] . $_LANG['is_empty']);
    // 判断是否有上传图片/上传图片生成
    if ($_FILES['image']['name'] != '') {
        $upfile = $img->upload_image('image', $dou->auto_id('taste')); // 上传的文件域
        $file = $images_dir . $upfile;
        $img->make_thumb($upfile, $_CFG['thumb_width'], $_CFG['thumb_height']);
    }
    //$add_time = time();
    
    // 格式化自定义参数
    //$_POST['defined'] = str_replace("\r\n", ',', $_POST['defined']);
    
    // CSRF防御令牌验证
   $firewall->check_token($_POST['token']);
    
    $sql = "INSERT INTO " . $dou->table('taste') . " (id,cid, img,name, engname,content, sort, news_show)" . " VALUES (NULL,'$_POST[cid]', '$file','$_POST[name]','$_POST[engname]', '$_POST[content]', '$_POST[sort]', '$_POST[show]')";
    $dou->query($sql);
    $dou->create_admin_log($_LANG['taste_add'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['taste_add_succes'], 'taste.php');
} 

/**
 * +----------------------------------------------------------
 * 攻略编辑
 * +----------------------------------------------------------
 */
elseif ($rec == 'edit') {
    $smarty->assign('ur_here', $_LANG['taste_edit']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['taste'],
            'href' => 'taste.php' 
    ));
    
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
    
    $query = $dou->select($dou->table('taste'), '*', '`id` = \'' . $id . '\'');
    $taste = $dou->fetch_array($query);
    //print_r($taste);
    
    // // 格式化自定义参数
    // if ($_DEFINED['product'] || $product['defined']) {
    //     $defined = explode(',', $_DEFINED['product']);
    //     foreach ($defined as $row) {
    //         $defined_product .= $row . "：\n";
    //     }
    //     // 如果商品中已经写入自定义参数则调用已有的
    //     $product['defined'] = $product['defined'] ? str_replace(",", "\n", $product['defined']) : trim($defined_product);
    //     $product['defined_count'] = count(explode("\n", $product['defined'])) * 2;
    // }
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('taste_type');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $sty[]=$row;
    }
    // 赋值给模板
    $smarty->assign('form_action', 'update');
    $smarty->assign('sty', $sty);
    $smarty->assign('taste', $taste);
    
    $smarty->display('taste.htm');
} 

elseif ($rec == 'update') {
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['taste_name'] . $_LANG['is_empty']);
    if($_POST['cid']==0)
        $dou->dou_msg($_LANG['taste_type'] . $_LANG['is_empty']);
    if(empty($_POST['content']))
        $dou->dou_msg($_LANG['taste_content'] . $_LANG['is_empty']);
        
    // 上传图片生成
    if ($_FILES['image']['name'] != "") {
        $upfile = $img->upload_image('image', $file_name); // 上传的文件域
        $file = $images_dir . $upfile;
        $img->make_thumb($upfile, $_CFG['thumb_width'], $_CFG['thumb_height']);
        
        $up_file = ", img='$file'";
    }
    
    // 格式化自定义参数
    //$_POST['defined'] = str_replace("\r\n", ',', $_POST['defined']);
    
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token']);
   
    $sql = "update " . $dou->table('taste') . " SET cid = '$_POST[cid]', name = '$_POST[name]',engname = '$_POST[engname]', content = '$_POST[content]' ". $up_file .",news_show= '$_POST[show]',sort = '$_POST[sort]' WHERE id = '$_POST[id]'";
    $dou->query($sql);
    $dou->create_admin_log($_LANG['taste_edit'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['taste_edit_succes'], 'taste.php');
} 

// /**
//  * +----------------------------------------------------------
//  * 重新生成攻略图片
//  * +----------------------------------------------------------
//  */
// elseif ($rec == 're_thumb') {
//     $smarty->assign('ur_here', $_LANG['product_thumb']);
//     $smarty->assign('action_link', array (
//             'text' => $_LANG['product'],
//             'href' => 'product.php' 
//     ));
    
//     $sql = "SELECT id, image FROM " . $dou->table('product') . "ORDER BY id ASC";
//     $count = mysql_num_rows($query = $dou->query($sql));
//     $mask['count'] = preg_replace('/d%/Ums', $count, $_LANG['product_thumb_count']);
//     $mask_tag = '<i></i>';
//     $mask['confirm'] = $_POST['confirm'];
    
//     for($i = 1; $i <= $count; $i++)
//         $mask['bg'] .= $mask_tag;
    
//     $smarty->assign('mask', $mask);
//     $smarty->display('product.htm');
    
//     if (isset($_POST['confirm'])) {
//         echo ' ';
//         while ($row = $dou->fetch_array($query)) {
//             $img->make_thumb(basename($row['image']), $_CFG['thumb_width'], $_CFG['thumb_height']);
//             echo "<script type=\"text/javascript\">mask('" . $mask_tag . "');</script>";
//             flush();
//             ob_flush();
//         }
//         echo "<script type=\"text/javascript\">success();</script>\n</body>\n</html>";
//     }
// }

/**
 * +----------------------------------------------------------
 * 首页商品筛选
 * +----------------------------------------------------------
 */
// elseif ($rec == 'sort') {
//     $_SESSION['if_sort'] = $_SESSION['if_sort'] ? false : true;
    
//     // 跳转到上一页面
//     $dou->dou_header($_SERVER['HTTP_REFERER']);
// } 

/**
 * +----------------------------------------------------------
 * 设为首页显示商品
 * +----------------------------------------------------------
 */
// elseif ($rec == 'set_sort') {
//     // 验证并获取合法的ID
//     $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'product.php');
    
//     $max_sort = $dou->get_one("SELECT sort FROM " . $dou->table('product') . " ORDER BY sort DESC");
//     $new_sort = $max_sort + 1;
//     $dou->query("UPDATE " . $dou->table('product') . " SET sort = '$new_sort' WHERE id = '$id'");
    
//     $dou->dou_header($_SERVER['HTTP_REFERER']);
// } 

/**
 * +----------------------------------------------------------
 * 取消首页显示商品
 * +----------------------------------------------------------
 */
// elseif ($rec == 'del_sort') {
//     // 验证并获取合法的ID
//     $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'product.php');
    
//     $dou->query("UPDATE " . $dou->table('product') . " SET sort = '' WHERE id = '$id'");
    
//     $dou->dou_header($_SERVER['HTTP_REFERER']);
// } 

/**
 * +----------------------------------------------------------
 * 攻略删除
 * +----------------------------------------------------------
 */
elseif ($rec == 'del') {
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'taste.php');
    
    $name = $dou->get_one("SELECT name FROM " . $dou->table('taste') . " WHERE id = '$id'");
    
    if (isset($_POST['confirm']) ? $_POST['confirm'] : '') {
        // // 删除相应商品图片
        // $image = $dou->get_one("SELECT taste_img FROM " . $dou->table('taste') . " WHERE id = '$id'");
        // $dou->del_image($image);
        
        $dou->create_admin_log($_LANG['taste_del'] . ': ' . $name);
        $dou->delete($dou->table('taste'), "id = $id", 'taste.php');
    } else {
        $_LANG['del_check'] = preg_replace('/d%/Ums', $name, $_LANG['del_check']);
        $dou->dou_msg($_LANG['del_check'], 'taste.php', '', '30', "taste.php?rec=del&id=$id");
    }
} 

/**
 * +----------------------------------------------------------
 * 批量操作选择
 * +----------------------------------------------------------
 */
elseif ($rec == 'action') {
    if (is_array($_POST['checkbox'])) {
        if ($_POST['action'] == 'del_all') {
            // 批量学校删除
            $dou->del_all('taste', $_POST['checkbox'], 'id');
        }
    } else {
        $dou->dou_msg($_LANG['taste_select_empty']);
    }
}

/**
 * +----------------------------------------------------------
 * 获取首页显示商品
 * +----------------------------------------------------------
 */
// function get_sort_product() {
//     $limit = $GLOBALS['_DISPLAY']['home_product'] ? ' LIMIT ' . $GLOBALS['_DISPLAY']['home_product'] : '';
//     $sql = "SELECT id, name, image FROM " . $GLOBALS['dou']->table('product') . " WHERE sort > 0 ORDER BY sort DESC" . $limit;
//     $query = $GLOBALS['dou']->query($sql);
//     while ($row = $GLOBALS['dou']->fetch_array($query)) {
//         $image = ROOT_URL . $row['image'];
        
//         $sort[] = array (
//                 "id" => $row['id'],
//                 "name" => $row['name'],
//                 "image" => $image 
//         );
//     }
    
//     return $sort;
// }
?>