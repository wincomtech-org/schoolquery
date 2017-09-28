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
// if (isset($_REQUEST['mobile'])) {
//     setcookie('client', 'pc');
//     if ($_COOKIE['client'] != 'pc') $_COOKIE['client'] = 'pc';
// }

require (dirname(__FILE__) . '/include/init.php');

if($_REQUEST['ent']=='pass'){
    //获取登录密码
    $pass=$_POST['password'];
    // echo $_SERVER['HTTP_REFERER'];
    // echo $_SERVER['PHP_SELF'];
    $CFG = $dou->get_config();
    $CFG['entrance'];
    if($pass==$CFG['entrance']){
        $_SESSION['pass']=$pass;
       header('location:index.php');
    }
    else{
        header('location:index.php');
    }
}
if($_REQUEST['ent']=='exit'){
    unset($_SESSION['pass']);
    header('location:index.php');
}
?>