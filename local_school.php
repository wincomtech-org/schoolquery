<?php
define('IN_DOUCO', true);

// 强制在移动端中显示PC版
// if (isset($_REQUEST['mobile'])) {
//     setcookie('client', 'pc');
//     if ($_COOKIE['client'] != 'pc') $_COOKIE['client'] = 'pc';
// }

require (dirname(__FILE__) . '/include/init.php');


// 赋值给模板-导航栏
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
$sql = "SELECT * FROM " . $dou->table('nav').'where module="local_school"';
$query = $dou->query($sql);
$about = $dou->fetch_array($query);
$ce=$about['show_img'];

$smarty->assign('ce', $ce);//获取首页图片
//首次加载默认选择第一个
$sql = "SELECT * FROM " . $dou->table('course_type').'order by sort desc';
$query = $dou->query($sql);
$row = $GLOBALS['dou']->fetch_assoc($query);
$smarty->assign('id',$row['id']);
//获取分类
$sql = "SELECT * FROM " . $dou->table('course_type').'order by sort desc';
$query = $dou->query($sql);
$i=0;
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
	$sq = "SELECT shid FROM " . $dou->table('course')."where tid='$row[id]'";
	$querys = $dou->query($sq);
	while ($rows = $GLOBALS['dou']->fetch_assoc($querys)) {
		$sqls = "SELECT * FROM " . $dou->table('school')."where id='$rows[shid]' and cid=1";
		$quer = $dou->query($sqls);
		while ($ro = $GLOBALS['dou']->fetch_assoc($quer)) {
			$sch[]=$ro;

		}
		$school[$i]=$sch;


	}

	foreach ($school[$i] as $v){
		$v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
		$temp[$i][] = $v;
	}
	$temp[$i] = array_unique($temp[$i]); //去掉重复的字符串,也就是重复的一维数组
	foreach ($temp[$i] as $k => $v){
		$temp[$i][$k] = explode(",",$v); //再将拆开的数组重新组装
	}
	$i++;
	$course[]=$row;
}
$smarty->assign('school', $temp);

$smarty->assign('course', $course);
//$smarty->assign('school', $school);


$sql = "SELECT * FROM " . $dou->table('taste').'where news_show=1 order by sort desc limit 2';
$query = $dou->query($sql);
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
	$row['content']=mb_substr($row['content'],0,60,'UTF-8');
	$news[]=$row;
}
$smarty->assign('news', $news);//获取新闻
// 赋值给模板-数据

$smarty->assign('index', 'local_school');//选中加样式
$smarty->assign('recommend_product', $dou->get_list('product', 'ALL', $_DISPLAY['home_product'], 'sort DESC'));

$smarty->assign('recommend_article', $dou->get_list('article', 'ALL', $_DISPLAY['home_article'], 'sort DESC'));

$smarty->display('local_school.html');

?>