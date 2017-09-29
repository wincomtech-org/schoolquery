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
require (dirname(__FILE__) . '/include/page.class.php');

// 赋值给模板-导航栏
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
$sql = "SELECT * FROM " . $dou->table('nav').'where module="local_school"';
 $query = $dou->query($sql);
$about = $dou->fetch_array($query);
$ce=$about['show_img'];

$smarty->assign('ce', $ce);//获取首页图片

$grade=$_REQUEST['grade'];
$gradetype=$_REQUEST['gradetype'];
$dates=$_REQUEST['date'];
$key=$_REQUEST['key'];
$date=strtotime($dates);

if($_SESSION['pass']){
    $sql = "SELECT cou_id FROM " . $dou->table('sco_cou')."where sco_id='$gradetype' and min_score<='$grade'";
    $query = $dou->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $cou_id[]=$row;
       
    }
    foreach($cou_id as $val){
        foreach($val as $v){
            $couid.= $v.',';
        }
       
    }
     $couid=substr($couid,0,-1);
    $pageNum = empty($_GET["page"])?1:$_GET["page"];
    $pageSize=10;
    if(!empty($couid)){
        if(!empty($_REQUEST['date'])){
             $sql="SELECT * FROM " . $dou->table('course')." where id in ($couid) and (name like '%$key%' or eng_name like '%$key%') and cut_off_data >='$date' order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
        }else{
             $sql="SELECT * FROM " . $dou->table('course')." where id in ($couid) and (name like '%$key%' or eng_name like '%$key%') order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
        }
    }else{
        if(!empty($_REQUEST['date'])){
           $sql="SELECT * FROM " . $dou->table('course')." where (name like '%$key%' or eng_name like '%$key%') and cut_off_data >='$date' order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
        }else{
             $sql="SELECT * FROM " . $dou->table('course')." where (name like '%$key%' or eng_name like '%$key%') order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
        }
    }
     // $sql="SELECT * FROM " . $dou->table('course')." where id in ($couid) and (name like '%$key%' or eng_name like '%$key%') and cut_off_data >='$date' order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
     $query = $dou->query($sql);
     while ($rows = $GLOBALS['dou']->fetch_assoc($query)) {
        $end=date('Y/m/d', $rows['cut_off_data']);
         $styname = $dou->get_one("SELECT name FROM " . $dou->table('course_type') . " WHERE id = '$rows[tid]'");
        $course[] = array (
                "id" => $rows['id'],
                "name" => $rows['name'],
                "engname" => $rows['eng_name'],
                "type" =>  $styname,
                "end" => $end
              
        );
    }
    $sql="SELECT count(*) FROM".$dou->table('course')."where id in ($couid) and (name like '%$key%' or eng_name like '%$key%') and cut_off_data >='$date'";
    $query = $dou->query($sql);
    $num=$dou->fetch_array($query);
    $allnum=$num['count(*)'];
}else{
    $pageNum = empty($_GET["page"])?1:$_GET["page"];
    $pageSize=10;
     $sql="SELECT * FROM " . $dou->table('course')." where name like '%$key%' or eng_name like '%$key%' order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
     $query = $dou->query($sql);
     while ($rows = $GLOBALS['dou']->fetch_assoc($query)) {
        $end=date('Y/m/d', $rows['cut_off_data']);
         $styname = $dou->get_one("SELECT name FROM " . $dou->table('course_type') . " WHERE id = '$rows[tid]'");
        $course[] = array (
                "id" => $rows['id'],
                "name" => $rows['name'],
                "engname" => $rows['eng_name'],
                "type" =>  $styname,
                "end" => $end
              
        );
    }
    $sql="SELECT count(*) FROM".$dou->table('course')."where name like '%$key%' or eng_name like '%$key%'";
    $query = $dou->query($sql);
    $num=$dou->fetch_array($query);
    $allnum=$num['count(*)'];
}
if($key==''){
    $course='';
    $allnum='';
}

pageft($allnum,$pageSize,1,1,0,5,"search_result.php?grade=$grade&gradetype=$gradetype&date=$dates&key=$key");
$smarty->assign('course',$course);
//获取分数类型
$sql = "SELECT * FROM " . $dou->table('score').'order by sort desc';
$query = $dou->query($sql);
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
    $score[]=$row;
}

$smarty->assign('score', $score);
$smarty->assign('grade',$grade);
$smarty->assign('gradetype',$gradetype);
$smarty->assign('date',$dates);
$smarty->assign('key',$key);
$smarty->assign('pagenav',$pagenav);
$smarty->display('search-result.html');


?>