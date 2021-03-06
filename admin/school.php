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
function resize_image($img_src, $new_img_path, $new_width, $new_height)
{
    $img_info = @getimagesize($img_src);
    if (!$img_info || $new_width < 1 || $new_height < 1 || empty($new_img_path)) {
        return false;
    }
    if (strpos($img_info['mime'], 'jpeg') !== false) {
        $pic_obj = imagecreatefromjpeg($img_src);
    } else if (strpos($img_info['mime'], 'gif') !== false) {
        $pic_obj = imagecreatefromgif($img_src);
    } else if (strpos($img_info['mime'], 'png') !== false) {
        $pic_obj = imagecreatefrompng($img_src);
    } else {
        return false;
    }

    $pic_width = imagesx($pic_obj);
    $pic_height = imagesy($pic_obj);

    if (function_exists("imagecopyresampled")) {
        $new_img = imagecreatetruecolor($new_width,$new_height);
        imagecopyresampled($new_img, $pic_obj, 0, 0, 0, 0, $new_width, $new_height, $pic_width, $pic_height);
    } else {
        $new_img = imagecreate($new_width, $new_height);
        imagecopyresized($new_img, $pic_obj, 0, 0, 0, 0, $new_width, $new_height, $pic_width, $pic_height);
    }
    if (preg_match('~.([^.]+)$~', $new_img_path, $match)) {
        $new_type = strtolower($match[1]);
        switch ($new_type) {
            case 'jpg':
                imagejpeg($new_img, $new_img_path);
                break;
            case 'gif':
                imagegif($new_img, $new_img_path);
                break;
            case 'png':
                imagepng($new_img, $new_img_path);
                break;
            default:
                imagejpeg($new_img, $new_img_path);
        }
    } else {
        imagejpeg($new_img, $new_img_path);
    }
    imagedestroy($pic_obj);
    imagedestroy($new_img);
    return true;
}

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 图片上传
include_once (ROOT_PATH . 'include/upload.class.php');
$images_dir = 'images/school/'; // 文件上传路径，结尾加斜杠
$thumb_dir = ''; // 缩略图路径（相对于$images_dir） 结尾加斜杠，留空则跟$images_dir相同
$img = new Upload(ROOT_PATH . $images_dir, $thumb_dir); // 实例化类文件
if (!file_exists(ROOT_PATH . $images_dir)) {
    mkdir(ROOT_PATH . $images_dir, 0777);
}

// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'school');

/**
 * +----------------------------------------------------------
 * 学校列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['school']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['school_add'],
            'href' => 'school.php?rec=add' 
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
    $page_url = 'school.php' . ($id ? '?id=' . $id : '');
    $limit = $dou->pager('school', 15, $page, $page_url, $where, $get);
    
    $sql = "SELECT id, name, sid,cid ,school_logo ,sort ,content FROM " . $dou->table('school') . $where . " ORDER BY id DESC" . $limit;
    $query = $dou->query($sql);
    while ($row = $dou->fetch_array($query)) {
       
        $conname = $dou->get_one("SELECT name FROM " . $dou->table('area') . " WHERE id = '$row[cid]'");
      
        //  echo $row['content'];
        //$add_time = date("Y-m-d", $row['add_time']);
       $row['content']=mb_substr($row['content'],0,30,'UTF-8');
        $school[] = array (
                "id" => $row['id'],
                "name" => $row['name'],
                "img" => $row['school_logo'],
                "conname" => $conname,
                "content" => $row['content'],
                "sort" => $row['sort'] 
        );
    }
    
    $sql = "SELECT * FROM " . $GLOBALS['dou']->table('area');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $sty[]=$row;
    }
    //print
    $smarty->assign('id', $id);
    $smarty->assign('keyword', $keyword);
    $smarty->assign('sty', $sty);
    $smarty->assign('school', $school);
    
    $smarty->display('school.htm');
} 

/**
 * +----------------------------------------------------------
 * 学校添加
 * +----------------------------------------------------------
 */
elseif ($rec == 'add') {
    $smarty->assign('ur_here', $_LANG['school_add']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['school'],
            'href' => 'school.php' 
    ));
    
    // 格式化自定义参数，并存到数组$product，商品编辑页面中调用商品详情也是用数组$product，
    if ($_DEFINED['product']) {
        $defined = explode(',', $_DEFINED['product']);
        foreach ($defined as $row) {
            $defined_product .= $row . "：\n";
        }
        $product['defined'] = trim($defined_product);
        $product['defined_count'] = count(explode("\n", $product['defined'])) * 2;
    }
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    $sql = "SELECT * FROM " . $GLOBALS['dou']->table('school_type');
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
    $smarty->assign('product', $product);
    //print_r($product);
    $smarty->display('school.htm');
} 

elseif ($rec == 'insert') {
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['school_name'] . $_LANG['is_empty']);
    if($_POST['cid']==0)
        $dou->dou_msg($_LANG['school_area'] . $_LANG['is_empty']);
    if(empty($_POST['content']))
        $dou->dou_msg($_LANG['school_content'] . $_LANG['is_empty']);
    // 判断是否有上传图片/上传图片生成
    // echo $_FILES['image']['name'];
    // echo $_FILES['img']['name'];
    // exit;
    // 
    if ($_FILES['image']['name'] != '') {
        $upfile = $img->upload_image('image', $dou->auto_id('school')); // 上传的文件域
        $file = $images_dir . $upfile;
        $img->make_thumb($upfile, $_CFG['thumb_width'], $_CFG['thumb_height']);
    }
    if ($_FILES['img']['name'] != '') {
        $upfiles = $img->upload_image('img'); // 上传的文件域
        $files = $images_dir . $upfiles;
        $img->make_thumb($upfiles, $_CFG['thumb_width'], $_CFG['thumb_height']);
    }
    $ret = resize_image(ROOT_PATH.$files, ROOT_PATH.$files, '300', '210');
    //$add_time = time();
    
    // 格式化自定义参数
    //$_POST['defined'] = str_replace("\r\n", ',', $_POST['defined']);
    
    // CSRF防御令牌验证
   $firewall->check_token($_POST['token']);
    
    $sql = "INSERT INTO " . $dou->table('school') . " (id,sid, cid,name,engname, content, sort,school_img,school_logo, people_num ,hot_stu, srequire, start_time,fee,website,detail)" . " VALUES (NULL,'$_POST[sid]', '$_POST[cid]','$_POST[name]','$_POST[engname]', '$_POST[content]', '$_POST[sort]', '$file', '$files','$_POST[num]', '$_POST[hot]', '$_POST[require]', '$_POST[strtime]', '$_POST[fee]', '$_POST[web]','$_POST[detail]')";
    $dou->query($sql);
    $dou->create_admin_log($_LANG['school_add'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['school_add_succes'], 'school.php');
} 

/**
 * +----------------------------------------------------------
 * 学校编辑
 * +----------------------------------------------------------
 */
elseif ($rec == 'edit') {
    $smarty->assign('ur_here', $_LANG['school_edit']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['school'],
            'href' => 'school.php' 
    ));
    
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
    
    $query = $dou->select($dou->table('school'), '*', '`id` = \'' . $id . '\'');
    $school = $dou->fetch_array($query);
    //print_r($school);
    
   
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('school_type');
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
    $smarty->assign('form_action', 'update');
    $smarty->assign('sty', $sty);
    $smarty->assign('con', $con);
    $smarty->assign('school', $school);
    
    $smarty->display('school.htm');
} 

elseif ($rec == 'update') {
    // echo $_POST['detail'];
    // exit;
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['school_name'] . $_LANG['is_empty']);
    if($_POST['cid']==0)
        $dou->dou_msg($_LANG['school_area'] . $_LANG['is_empty']);
    if(empty($_POST['content']))
        $dou->dou_msg($_LANG['school_content'] . $_LANG['is_empty']);
        
    // 上传图片生成
    if ($_FILES['image']['name'] != "") {
        
        $upfile = $img->upload_image('image', $dou->auto_id('school')); // 上传的文件域
        $file = $images_dir . $upfile;
        $img->make_thumb($upfile, $_CFG['thumb_width'], $_CFG['thumb_height']);
        
        $up_file = ", school_img='$file'";
    }
     if ($_FILES['img']['name'] != '') {
        $upfiles = $img->upload_image('img'); // 上传的文件域
        $files = $images_dir . $upfiles;
        $img->make_thumb($upfiles, $_CFG['thumb_width'], $_CFG['thumb_height']);
        $up_files = ", school_logo='$files'";
    }
    $ret = resize_image(ROOT_PATH.$files, ROOT_PATH.$files, '300', '210');
    // 格式化自定义参数
    //$_POST['defined'] = str_replace("\r\n", ',', $_POST['defined']);
    
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token']);
   
    $sql = "update " . $dou->table('school') . " SET sid = '$_POST[sid]',cid= '$_POST[cid]',name = '$_POST[name]',engname = '$_POST[engname]', detail = '$_POST[detail]',content = '$_POST[content]' ,sort = '$_POST[sort]'" . $up_file .$up_files. ", website = '$_POST[web]', fee = '$_POST[fee]', start_time = '$_POST[strtime]', srequire = '$_POST[require]', hot_stu = '$_POST[hot]',people_num = '$_POST[num]' WHERE id = '$_POST[id]'";
    $dou->query($sql);
    $dou->create_admin_log($_LANG['school_edit'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['school_edit_succes'], 'school.php');
} 

/**
 * +----------------------------------------------------------
 * 学校删除
 * +----------------------------------------------------------
 */
elseif ($rec == 'del') {
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'school.php');
    
    $name = $dou->get_one("SELECT name FROM " . $dou->table('school') . " WHERE id = '$id'");
    
    if (isset($_POST['confirm']) ? $_POST['confirm'] : '') {
        // 删除相应商品图片
        $image = $dou->get_one("SELECT school_img FROM " . $dou->table('school') . " WHERE id = '$id'");
        $dou->del_image($image);
        
        $dou->create_admin_log($_LANG['school_del'] . ': ' . $name);
        $dou->delete($dou->table('school'), "id = $id", 'school.php');
    } else {
        $_LANG['del_check'] = preg_replace('/d%/Ums', $name, $_LANG['del_check']);
        $dou->dou_msg($_LANG['del_check'], 'school.php', '', '30', "school.php?rec=del&id=$id");
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
            $dou->del_all('school', $_POST['checkbox'], 'id', 'image');
        }
    } else {
        $dou->dou_msg($_LANG['school_select_empty']);
    }
}


?>