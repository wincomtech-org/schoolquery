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
require (dirname(__FILE__) . '/include/page.class.php');


// 赋值给模板-导航栏
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
$sql = "SELECT * FROM " . $dou->table('nav').'where module="sea_school"';
 $query = $dou->query($sql);
$about = $dou->fetch_array($query);
$ce=$about['show_img'];

$smarty->assign('ce', $ce);//获取首页图片
$id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
$name=$_REQUEST['name'];
//获得学校
$where=" where cid!=1 ";
if($_REQUEST['rec']=='action'){
    $sch=$_POST['sch'];
    $where.="and (name like '%$sch%' or engname like '%$sch%') ";
    $smarty->assign('sch',$sch);
}else{
    $rec=$_REQUEST['sch'];
     $smarty->assign('sch',$rec);
    if($name){
        $where.=" and (eng_name like '$name%')";
    }
    $where.= " and (name like '%$rec%' or eng_name like '%$rec%') ";
}
$pageNum = empty($_GET["page"])?1:$_GET["page"];
$pageSize=10;
 $sql="SELECT * FROM".$dou->table('school').$where." order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
 $query = $dou->query($sql);
while($row = $dou->fetch_array($query)){
    $row['cut_off_data']=date('Y/m/d',$row['cut_off_data']);
    $school[]=$row;
}
//条件下的总条数
$sql="SELECT count(*) FROM".$dou->table('school').$where;
$query = $dou->query($sql);
$num=$dou->fetch_array($query);
$allnum=$num['count(*)'];
// $endPage = ceil($allnum/$pageSize);
//分页插件
    pageft($allnum,$pageSize,1,1,0,5,"sea_studies.php?sch=$sch");


$smarty->assign('pagenav',$pagenav);
$smarty->assign('school',$school);

$smarty->display('sea_studies.html');

?>