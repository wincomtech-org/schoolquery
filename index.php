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

// 如果存在搜索词则转入搜索页面
if ($_REQUEST['s']) {
    if ($check->is_search_keyword($keyword = trim($_REQUEST['s']))) {
        require (ROOT_PATH . 'include/search.inc.php');
    } else {
        $dou->dou_msg($_LANG['search_keyword_wrong']);
    }
}

// // 获取关于我们信息
// $sql = "SELECT * FROM " . $dou->table('page') . " WHERE id = '1'";
// $query = $dou->query($sql);
// $about = $dou->fetch_array($query);
// // 写入到index数组
// $index['about_name'] = $about['page_name'];
// $index['about_content'] = $dou->dou_substr($about['content'], 170, false)."..."; // 这里的300数值不能设置得过大，否则会造成程序卡死
// $index['about_link'] = $dou->rewrite_url('page', '1');
// $index['cur'] = true;

// // 赋值给模板-meta和title信息
// $smarty->assign('page_title', $dou->page_title());
// $smarty->assign('keywords', $_CFG['site_keywords']);
// $smarty->assign('description', $_CFG['site_description']);

// 赋值给模板-导航栏
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
$sql = "SELECT * FROM " . $dou->table('show');
 $query = $dou->query($sql);
$about = $dou->fetch_array($query);
$ce=$about['show_img'];

$smarty->assign('ce', $ce);//获取首页图片

$sql = "SELECT * FROM " . $dou->table('taste').'order by sort desc limit 4';
$query = $dou->query($sql);
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
    $taste[]=$row;
}
//获取分数类型
$sql = "SELECT * FROM " . $dou->table('score').'order by sort desc';
$query = $dou->query($sql);
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
    $score[]=$row;
}
$smarty->assign('score', $score);
$smarty->assign('taste', $taste);//获取资讯

$sql = "SELECT * FROM " . $dou->table('course').'order by sort desc limit 6';
$query = $dou->query($sql);
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
	 $shname = $dou->get_one("SELECT name FROM " . $dou->table('school') . " WHERE id = '$row[shid]'");
	  $course[] = array (
                "id" => $row['id'],
                "name" => $row['name'],
                "shname" => $shname,
        );
}
$smarty->assign('course', $course);//获取学科

$sql = "SELECT * FROM " . $dou->table('taste').'where news_show=1 order by sort desc limit 2';
$query = $dou->query($sql);
while ($row = $GLOBALS['dou']->fetch_assoc($query)) {
		$row['content']=mb_substr($row['content'],0,40,'UTF-8');
	   $news[]=$row;
}
$smarty->assign('news', $news);//获取新闻
// 赋值给模板-数据
$smarty->assign('index', 'index');
$smarty->display('index.html');


?>