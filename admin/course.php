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
$images_dir = 'images/course/'; // 文件上传路径，结尾加斜杠
$thumb_dir = ''; // 缩略图路径（相对于$images_dir） 结尾加斜杠，留空则跟$images_dir相同
$img = new Upload(ROOT_PATH . $images_dir, $thumb_dir); // 实例化类文件
if (!file_exists(ROOT_PATH . $images_dir)) {
    mkdir(ROOT_PATH . $images_dir, 0777);
}

// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'course');

/**
 * +----------------------------------------------------------
 * 产品列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['course']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['course_add'],
            'href' => 'course.php?rec=add' 
    ));
    
    // 获取参数
    $shid = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    $keyword = isset($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
    // 筛选条件
    if($shid!=0){
        $where = "WHERE shid='$shid'";
    }else{
        $where = ' WHERE 1=1';
    }

    if ($keyword) {
        $where = $where . " AND name LIKE '%$keyword%'";
        $get = '&keyword=' . $keyword;
    }
    
    // 分页
    $page = $check->is_number($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $page_url = 'course.php' . ($id ? '?id=' . $id : '');
    $limit = $dou->pager('course', 15, $page, $page_url, $where, $get);
    
    $sql = "SELECT id, name, shid, tid,cut_off_data,sort FROM " . $dou->table('course') . $where . " ORDER BY id DESC" . $limit;
    $query = $dou->query($sql);
    while ($row = $dou->fetch_array($query)) {
        //$row['open_data']=date("Y-m-d",$row['open_data']);
        $row['cut_off_data']=date("Y-m-d",$row['cut_off_data']);
        $tyname= $dou->get_one("SELECT name FROM " . $dou->table('course_type') . " WHERE id = '$row[tid]'");
        $shname = $dou->get_one("SELECT name FROM " . $dou->table('school') . " WHERE id = '$row[shid]'");
        // echo $row['sort'];
        //  echo $row['content'];
        //$add_time = date("Y-m-d", $row['add_time']);
       //$row['content']=mb_substr($row['content'],0,30,'UTF-8');
        $course[] = array (
                "id" => $row['id'],
                "name" => $row['name'],
                "tyname" => $tyname,
                "ldata" => $row['cut_off_data'],
                "shname" => $shname,
                "sort" => $row['sort'] 
        );
    }
    
    $sql = "SELECT * FROM " . $GLOBALS['dou']->table('school');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $sch[]=$row;
    }
   // print_r($sch);
    $smarty->assign('id', $shid);
    $smarty->assign('keyword', $keyword);
    $smarty->assign('sch', $sch);
    $smarty->assign('course', $course);
    
    $smarty->display('course.htm');
} 

/**
 * +----------------------------------------------------------
 * 产品添加
 * +----------------------------------------------------------
 */
elseif ($rec == 'add') {
    $smarty->assign('ur_here', $_LANG['course_add']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['course'],
            'href' => 'course.php' 
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
    $sql = "SELECT * FROM " . $GLOBALS['dou']->table('school');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $sty[]=$row;
    }
    $sql = "SELECT * FROM " . $GLOBALS['dou']->table('course_type');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $typ[]=$row;
    }
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('score');
      $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $score[]=$row;
    }
    // 赋值给模板
    $smarty->assign('score',$score);
    $smarty->assign('form_action', 'insert');
    $smarty->assign('sty', $sty);
    $smarty->assign('typ', $typ);
    $smarty->assign('product', $product);
    print_r($product);
    $smarty->display('course.htm');
} 

elseif ($rec == 'insert') {

    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['course_name'] . $_LANG['is_empty']);
    if($_POST['shid']==0)
        $dou->dou_msg($_LANG['course_insty'] . $_LANG['is_empty']);
     if($_POST['tid']==0)
        $dou->dou_msg($_LANG['course_typ'] . $_LANG['is_empty']);
    if(empty($_POST['ndata']))
        $dou->dou_msg($_LANG['course_ndata'] . $_LANG['is_empty']);
    if(empty($_POST['ldata']))
        $dou->dou_msg($_LANG['course_ldata'] . $_LANG['is_empty']);
    if(empty($_POST['content']))
        $dou->dou_msg($_LANG['course_require'] . $_LANG['is_empty']);
    $add_time = time();
     $ndata=strtotime($_POST['ndata']);
    $ldata=strtotime($_POST['ldata']);
    // 格式化自定义参数
    //$_POST['defined'] = str_replace("\r\n", ',', $_POST['defined']);
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('score');
      $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $scores[]=$row;
    }
    
    // CSRF防御令牌验证
   $firewall->check_token($_POST['token']);
    $score=$_POST['score'];
    $i=0;
   

    $sql = "INSERT INTO " . $dou->table('course') . " (id,code,shid,tid, name,eng_name, sort, depart ,open_data, cut_off_data, srequire)" . " VALUES (NULL,'$_POST[code]','$_POST[shid]','$_POST[tid]', '$_POST[name]', '$_POST[engname]', '$_POST[sort]', '$_POST[depart]', '$ndata', '$ldata', '$_POST[content]')";

    $dou->query($sql);
    $sql="SELECT id FROM " . $dou->table('course')."order by id desc";
     $query= $dou->query($sql);
    $row = $GLOBALS['dou']->fetch_assoc($query);
    $rows= $row['id'];
     foreach($score as $v){
        $sid=$scores[$i]['id'];
        $sql = "INSERT INTO " . $dou->table('sco_cou') . " (id,sco_id,cou_id,min_score)" . " VALUES (NULL,'$sid', '$rows','$v')";
        $dou->query($sql);
        $i++;
     }
    $dou->create_admin_log($_LANG['course_add'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['course_add_succes'], 'course.php');
} 

/**
 * +----------------------------------------------------------
 * 产品编辑
 * +----------------------------------------------------------
 */
elseif ($rec == 'edit') {
    $smarty->assign('ur_here', $_LANG['course_edit']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['course'],
            'href' => 'course.php' 
    ));
    
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
    
    $query = $dou->select($dou->table('course'), '*', '`id` = \'' . $id . '\'');
    $course = $dou->fetch_array($query);
    $course['open_data']=date('Y-m-d', $course['open_data']); 
    $course['cut_off_data']=date('Y-m-d', $course['cut_off_data']); 
   
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('school');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $sty[]=$row;
    }
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('course_type');
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $typ[]=$row;
    }
     $sql = "SELECT * FROM " . $GLOBALS['dou']->table('score');
      $query = $GLOBALS['dou']->query($sql);
        while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
         $scores= $dou->get_one("SELECT min_score FROM " . $dou->table('sco_cou') . " WHERE sco_id = '$row[id]' and cou_id='$id'");
        $score[] = array (
                "id" => $row['id'],
                "name" => $row['name'],
                "scores" => $scores
        );
    }
    //查询对应的分数要求
    // $sql="SELECT * FROM".$GLOBALS['dou']->table('sco_cou')."where cou_id=$id";
    // $query= $dou->query($sql);
    // while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
    //     $scores[]=$row;
    // }
    // 赋值给模板
    $smarty->assign('score',$score);
    //$smarty->assign('scores',$scores);
    $smarty->assign('form_action', 'update');
    $smarty->assign('sty', $sty);
    $smarty->assign('typ', $typ);
    $smarty->assign('course', $course);
    
    $smarty->display('course.htm');
} 

elseif ($rec == 'update') {
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['course_name'] . $_LANG['is_empty']);
    if($_POST['shid']==0)
        $dou->dou_msg($_LANG['course_insty'] . $_LANG['is_empty']);
     if($_POST['tid']==0)
        $dou->dou_msg($_LANG['course_typ'] . $_LANG['is_empty']);
    if(empty($_POST['ndata']))
        $dou->dou_msg($_LANG['course_ndata'] . $_LANG['is_empty']);
    if(empty($_POST['ldata']))
        $dou->dou_msg($_LANG['course_ldata'] . $_LANG['is_empty']);
    if(empty($_POST['content']))
        $dou->dou_msg($_LANG['course_require'] . $_LANG['is_empty']);
    //$add_time = time();
     $ndata=strtotime($_POST['ndata']);
    $ldata=strtotime($_POST['ldata']);
        
    
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token']);
    $sql = "SELECT * FROM " . $GLOBALS['dou']->table('score');
      $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $scores[]=$row;
    }
    $score=$_POST['score'];
    $i=0;
    //删除原先的记录
    $sql = "delete from" . $dou->table('sco_cou') ." WHERE cou_id='$_POST[id]'";
     $dou->query($sql);

    foreach($score as $v){
        $sid=$scores[$i]['id'];
        $sql = "INSERT INTO " . $dou->table('sco_cou') . " (id,sco_id,cou_id,min_score)" . " VALUES (NULL,'$sid', '$_POST[id]','$v')";
        $dou->query($sql);
        $i++;
     }
    $sql = "update " . $dou->table('course') . " SET shid = '$_POST[shid]',code = '$_POST[code]',tid = '$_POST[tid]', name = '$_POST[name]', eng_name = '$_POST[engname]' ,sort = '$_POST[sort]',depart = '$_POST[depart]', open_data = '$ndata', cut_off_data = '$ldata', srequire = '$_POST[content]' WHERE id = '$_POST[id]'";

    $dou->query($sql);
    $dou->create_admin_log($_LANG['course_edit'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['course_edit_succes'], 'course.php');
} 



/**
 * +----------------------------------------------------------
 * 学校删除
 * +----------------------------------------------------------
 */
elseif ($rec == 'del') {
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'course.php');
    
    $name = $dou->get_one("SELECT name FROM " . $dou->table('course') . " WHERE id = '$id'");
    
    if (isset($_POST['confirm']) ? $_POST['confirm'] : '') {
         //删除对应的分数
            $sql = "delete from" . $dou->table('sco_cou') ." WHERE cou_id='$id'";
            $dou->query($sql);
        
        $dou->create_admin_log($_LANG['course_del'] . ': ' . $name);
        $dou->delete($dou->table('course'), "id = $id", 'course.php');
    } else {
        $_LANG['del_check'] = preg_replace('/d%/Ums', $name, $_LANG['del_check']);
        $dou->dou_msg($_LANG['del_check'], 'course.php', '', '30', "course.php?rec=del&id=$id");
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
            $dou->del_all('course', $_POST['checkbox'], 'id', 'image');
        }
    } else {
        $dou->dou_msg($_LANG['course_select_empty']);
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