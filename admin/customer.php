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
$images_dir = 'images/school/'; // 文件上传路径，结尾加斜杠
$thumb_dir = ''; // 缩略图路径（相对于$images_dir） 结尾加斜杠，留空则跟$images_dir相同
$img = new Upload(ROOT_PATH . $images_dir, $thumb_dir); // 实例化类文件
if (!file_exists(ROOT_PATH . $images_dir)) {
    mkdir(ROOT_PATH . $images_dir, 0777);
}

// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'customer');

/**
 * +----------------------------------------------------------
 * 客户列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['customer']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['customer_add'],
            'href' => 'customer.php?rec=add' 
    ));
    
    // 获取参数
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    $keyword = isset($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
    // 筛选条件
    if($id!=0){
        $where = "WHERE sid='$id'";
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
    
    $sql = "SELECT id, name, engname,sex ,data ,nation ,phone FROM " . $dou->table('customer') . $where . " ORDER BY id DESC" . $limit;
    $query = $dou->query($sql);
    while ($row = $dou->fetch_array($query)) {
    
        //  echo $row['content'];
        //$add_time = date("Y-m-d", $row['add_time']);
       $row['content']=mb_substr($row['content'],0,30,'UTF-8');
        $customer[] = array (
                "id" => $row['id'],
                "name" => $row['name'],
                "engname" => $row['engname'],
                "sex" => $row['sex'],
                "data" => $row['data'],
                "nation" => $row['nation'],
                "phone" => $row['phone']
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
    $smarty->assign('keyword', $keyword);
    $smarty->assign('customer', $customer);
    
    $smarty->display('customer.htm');
} 

/**
 * +----------------------------------------------------------
 * 产品添加
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
    $smarty->display('customer.htm');
} 

elseif ($rec == 'insert') {
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['school_name'] . $_LANG['is_empty']);
    if($_POST['sid']==0)
        $dou->dou_msg($_LANG['school_type'] . $_LANG['is_empty']);
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
 * 产品编辑
 * +----------------------------------------------------------
 */
elseif ($rec == 'edit') {
    $smarty->assign('ur_here', $_LANG['customer_edit']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['customer'],
            'href' => 'customer.php' 
    ));
    
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
    
    $query = $dou->select($dou->table('customer'), '*', '`id` = \'' . $id . '\'');
    $customer = $dou->fetch_array($query);
    //print_r($school);
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    $customer['school_name']= explode("|", $customer['school_name']); 
     $customer['school_adress']= explode("|", $customer['school_adress']); 
    $customer['school_tel']= explode("|", $customer['school_tel']); 
    $customer['school_year']= explode("|", $customer['school_year']); 
    $customer['school_time']= explode("|", $customer['school_time']); 
    $customer['education']= explode("|", $customer['education']);
    $customer['work_name']= explode("|", $customer['work_name']);
    $customer['work_adress']= explode("|", $customer['work_adress']);
    $customer['work_tel']= explode("|", $customer['work_tel']);
    $customer['work_year']= explode("|", $customer['work_year']);
    $customer['work_time']= explode("|", $customer['work_time']);
    $customer['position']= explode("|", $customer['position']);
    $customer['id_cent']= explode("|", $customer['id_cent']);
    foreach($customer['id_cent'] as $val){
        $str=explode(",",$val);
        $id_cent[] =$str[(count($str)-1)];
    }
    $customer['id_cent']=$id_cent;
    $customer['id_phono']= explode("|", $customer['id_phono']);
    foreach($customer['id_phono'] as $val){
        $str=explode(",",$val);
        $id_phono[] =$str[(count($str)-1)];
    }
    $customer['id_phono']=$id_phono;
    $customer['mark']= explode("|", $customer['mark']);
    foreach($customer['mark'] as $val){
        $str=explode(",",$val);
        $mark[] =$str[(count($str)-1)];
    }
    $customer['mark']=$mark;
    $customer['recom']= explode("|", $customer['recom']);
    foreach($customer['recom'] as $val){
        $str=explode(",",$val);
        $recom[] =$str[(count($str)-1)];
    }
    $customer['recom']=$recom;
    $customer['engprove']= explode("|", $customer['engprove']);
    foreach($customer['engprove'] as $val){
        $str=explode(",",$val);
        $engprove[] =$str[(count($str)-1)];
    }
    $customer['engprove']=$engprove;
    $customer['work_cent']= explode("|", $customer['work_cent']);
    foreach($customer['work_cent'] as $val){
        $str=explode(",",$val);
        $work_cent[] =$str[(count($str)-1)];
    }
    $customer['work_cent']=$work_cent;
    $customer['other']= explode("|", $customer['other']);
    foreach($customer['other'] as $val){
        $str=explode(",",$val);
        $other[] =$str[(count($str)-1)];
    }
    $customer['other']=$other;
    $customer['tutor_cent']= explode("|", $customer['tutor_cent']);
    foreach($customer['tutor_cent'] as $val){
        $str=explode(",",$val);
        $tutor_cent[] =$str[(count($str)-1)];
    }
    $customer['tutor_cent']=$tutor_cent;
    $customer['tutor_idphono']= explode("|", $customer['tutor_idphono']);
    foreach($customer['tutor_idphono'] as $val){
        $str=explode(",",$val);
        $tutor_idphono[] =$str[(count($str)-1)];
    }
    $customer['tutor_idphono']=$tutor_idphono;

    // 赋值给模板
    $smarty->assign('form_action', 'update');
    $smarty->assign('customer', $customer);
    $smarty->display('customer.htm');
} 

elseif ($rec == 'update') {
    // echo $_POST['detail'];
    // exit;
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['school_name'] . $_LANG['is_empty']);
    if($_POST['sid']==0)
        $dou->dou_msg($_LANG['school_type'] . $_LANG['is_empty']);
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
    // 格式化自定义参数
    //$_POST['defined'] = str_replace("\r\n", ',', $_POST['defined']);
    
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token']);
   
    $sql = "update " . $dou->table('school') . " SET sid = '$_POST[sid]',cid= '$_POST[cid]',name = '$_POST[name]',engname = '$_POST[engname]', detail = '$_POST[detail]',content = '$_POST[content]' ,sort = '$_POST[sort]'" . $up_file .$up_files. ", website = '$_POST[web]', fee = '$_POST[fee]', start_time = '$_POST[strtime]', srequire = '$_POST[require]', hot_stu = '$_POST[hot]',people_num = '$_POST[num]' WHERE id = '$_POST[id]'";
    $dou->query($sql);
    $dou->create_admin_log($_LANG['school_edit'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['school_edit_succes'], 'school.php');
} 

// /**
//  * +----------------------------------------------------------
//  * 重新生成产品图片
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
 * 学校删除
 * +----------------------------------------------------------
 */
elseif ($rec == 'del') {
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'customer.php');
    
    $name = $dou->get_one("SELECT name FROM " . $dou->table('customer') . " WHERE id = '$id'");
    
    if (isset($_POST['confirm']) ? $_POST['confirm'] : '') {
        
        $dou->create_admin_log($_LANG['customer_del'] . ': ' . $name);
        $dou->delete($dou->table('customer'), "id = $id", 'customer.php');
    } else {
        $_LANG['del_check'] = preg_replace('/d%/Ums', $name, $_LANG['del_check']);
        $dou->dou_msg($_LANG['del_check'], 'customer.php', '', '30', "customer.php?rec=del&id=$id");
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
            $dou->del_all('customer', $_POST['checkbox'], 'id', 'image');
        }
    } else {
        $dou->dou_msg($_LANG['customer_select_empty']);
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