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
//$where="where 1=1";
$wh='';
if(!empty($key)){
    //根据关键字查询学校id
    $sql= "SELECT id FROM " . $dou->table('school')."where name like '%$key%' or engname like '%$key%'";
    $query = $dou->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $scid[]=$row;
    }
    foreach($scid as $val){
        foreach($val as $v){
            $sid.= $v.',';
        }
    }
    $sid=substr($sid,0,-1);
    if(!empty($sid)){
        $wh.=" or shid in ($sid)";
    }
    //关键字查询课程类别的id
    $sql= "SELECT id FROM " . $dou->table('course_type')."where name like '%$key%'";
    $query = $dou->query($sql);
    while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
        $thid[]=$row;
    }
    foreach($thid as $val){
        foreach($val as $v){
            $tid.= $v.',';
        }
    }
    $tid=substr($tid,0,-1);
    if(!empty($tid)){
        $wh.=" or tid in ($tid)";
    }
}
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
            $sql="SELECT * FROM " . $dou->table('course')." where id in ($couid) and (name like '%$key%' or eng_name like '%$key%' ".$wh.") and cut_off_data >='$date' order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
            $sqls="SELECT count(*) FROM " . $dou->table('course')." where id in ($couid) and (name like '%$key%' or eng_name like '%$key%' ".$wh.") and cut_off_data >='$date'";

        }else{
            $sql="SELECT * FROM " . $dou->table('course')." where id in ($couid) and (name like '%$key%' or eng_name like '%$key%' ".$where.") order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
            $sqls="SELECT count(*) FROM " . $dou->table('course')." where id in ($couid) and (name like '%$key%' or eng_name like '%$key%' ".$where.")";
        }
    }else{
        if(!empty($_REQUEST['date'])){
            $sql="SELECT * FROM " . $dou->table('course')." where (name like '%$key%' or eng_name like '%$key%' ".$wh.") and cut_off_data >='$date' order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
            $sqls="SELECT count(*) FROM " . $dou->table('course')."  where (name like '%$key%' or eng_name like '%$key%' ".$wh.") and cut_off_data >='$date'";
        }else{
            $sql="SELECT * FROM " . $dou->table('course')." where (name like '%$key%' or eng_name like '%$key%' ".$wh.") order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
            $sqls="SELECT count(*) FROM " . $dou->table('course')."  where (name like '%$key%' or eng_name like '%$key%' ".$wh.")";
        }
    }
    $query = $dou->query($sql);
    while ($rows = $GLOBALS['dou']->fetch_assoc($query)) {
        $end=date('Y/m/d', $rows['cut_off_data']);
        $styname = $dou->get_one("SELECT name FROM " . $dou->table('course_type') . " WHERE id = '$rows[tid]'");
        $schname = $dou->get_one("SELECT name FROM " . $dou->table('school') . " WHERE id = '$rows[shid]'");
        $course[] = array (
                "id" => $rows['id'],
                "name" => $rows['name'],
                "engname" => $rows['eng_name'],
                "type" =>  $styname,
                "school" =>  $schname,
                "end" => $end
              
        );
    }
    
    $query = $dou->query($sqls);
    $num=$dou->fetch_array($query);
    $allnum=$num['count(*)'];
    if(empty($_REQUEST['date']) && empty($couid) &&empty($_REQUEST['key'])){
        $course='';
        $allnum='';
    }
}else{  
    if(empty($key)){
        $course='';
        $allnum='';
    }else{
        $pageNum = empty($_GET["page"])?1:$_GET["page"];
        $pageSize=10;
        $sql="SELECT * FROM " . $dou->table('course')." where  (name like '%$key%' or eng_name like '%$key%' ".$wh.") order by sort desc limit ". (($pageNum - 1) * $pageSize) . "," . $pageSize;
        $query = $dou->query($sql);
        while ($rows = $GLOBALS['dou']->fetch_assoc($query)) {
            $end=date('Y/m/d', $rows['cut_off_data']);
            $styname = $dou->get_one("SELECT name FROM " . $dou->table('course_type') . " WHERE id = '$rows[tid]'");
            $schname = $dou->get_one("SELECT name FROM " . $dou->table('school') . " WHERE id = '$rows[shid]'");
            $course[] = array (
                "id" => $rows['id'],
                "name" => $rows['name'],
                "engname" => $rows['eng_name'],
                "type" =>  $styname,
                "school" =>  $schname,
                "end" => $end
            );
        }
    $sql="SELECT count(*) FROM".$dou->table('course')."where (name like '%$key%' or eng_name like '%$key%' ".$wh.")";
    $query = $dou->query($sql);
    $num=$dou->fetch_array($query);
    $allnum=$num['count(*)'];
    }
   
}

pageft($allnum,$pageSize,1,1,0,5,"search_result.php?grade=$grade&gradetype=$gradetype&date=$dates&key=$key");
print_r($course);
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