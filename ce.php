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

$sub = 'insert|del';
$subbox = array(
        "module" => 'guestbook',
        "sub" => $sub
);

require (dirname(__FILE__) . '/include/init.php');

// 开启SESSION
session_start();

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

/**
 * +----------------------------------------------------------
 * 留言板
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    // SQL查询条件
  

    $smarty->display('example.html');
}

/**
 * +----------------------------------------------------------
 * 留言添加
 * +----------------------------------------------------------
 */
if ($rec == 'insert') {
    $ip = $dou->get_ip();
    $add_time = time();
    $captcha = $check->is_captcha($_POST['captcha']) ? strtoupper($_POST['captcha']) : '';
        
    // 如果限制必须输入中文则修改错误提示
    $include_chinese = $_CFG['guestbook_check_chinese'] ? $_LANG['guestbook_include_chinese'] : '';
    
    // 验证主题
    if ($check->is_illegal_char($_POST['title'])) {
        $wrong['title'] = $_LANG['guestbook_title'] . $_LANG['illegal_char'];
    } elseif (!check_guestbook($_POST['title'], 200)) {
        $wrong['title'] = preg_replace('/d%/Ums', $include_chinese, $_LANG['guestbook_title_wrong']);
    }
    
    // 验证联系人
    if ($check->is_illegal_char($_POST['name'])) {
        $wrong['name'] = $_LANG['guestbook_name'] . $_LANG['illegal_char'];
    } elseif (!check_guestbook($_POST['name'], 200)) {
        $wrong['name'] = preg_replace('/d%/Ums', $include_chinese, $_LANG['guestbook_name_wrong']);
    }
    
    // 验证回复方式
    if (empty($_POST['contact_type'])) {
        $wrong['contact'] = $_LANG['guestbook_contact_type_empty'];
    } elseif ($_POST['contact_type'] == 'email') {
        if (!$check->is_email($_POST['contact']))
            $wrong['contact'] = $_LANG['guestbook_email_wrong'];
    } elseif ($_POST['contact_type'] == 'tel') {
        if (!$check->is_telphone($_POST['contact']))
            $wrong['contact'] = $_LANG['guestbook_tel_wrong'];
    } elseif ($_POST['contact_type'] == 'qq') {
        if (!$check->is_qq($_POST['contact']))
            $wrong['contact'] = $_LANG['guestbook_qq_wrong'];
    }
    
    // 验证留言内容
    if ($check->is_illegal_char($_POST['content'])) {
        $wrong['content'] = $_LANG['guestbook_content'] . $_LANG['illegal_char'];
    } elseif (!check_guestbook($_POST['content'], 300)) {
        $wrong['content'] = preg_replace('/d%/Ums', $include_chinese, $_LANG['guestbook_content_wrong']);
    }
    
    // 判断验证码
    if ($_CFG['captcha'] && md5($captcha . DOU_SHELL) != $_SESSION['captcha'])
        $wrong['captcha'] = $_LANG['captcha_wrong'];
    
    // AJAX验证表单
    if ($_REQUEST['do'] == 'callback') {
        if ($wrong) {
            foreach ($_POST as $key => $value) {
                $wrong_json[$key] = $wrong[$key];
            }
            echo json_encode($wrong_json);
        }
        exit;
    }
    
    // 检查IP是否频繁留言
    if (is_water($ip))
        $dou->dou_msg($_LANG['guestbook_is_water'], $_URL['guestbook']);
    
    if ($wrong) {
        foreach ($wrong as $key => $value) {
            $wrong_format .= $wrong[$key] . '<br>';
        }
        $dou->dou_msg($wrong_format, $_URL['guestbook']);
    }
    
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token'], 'guestbook');

    // 安全处理用户输入信息
    $_POST = $firewall->dou_foreground($_POST);
    
    $sql = "INSERT INTO " . $dou->table('guestbook') . " (id, title, name, contact_type, contact, content, ip, add_time)" . " VALUES (NULL, '$_POST[title]', '$_POST[name]', '$_POST[contact_type]', '$_POST[contact]', '$_POST[content]', '$ip', '$add_time')";
    $dou->query($sql);
    
    $dou->dou_msg($_LANG['guestbook_insert_success'], $_URL['guestbook']);
}

/**
 * +----------------------------------------------------------
 * 防灌水
 * +----------------------------------------------------------
 */
function is_water($ip) {
    $unread_messages = $GLOBALS['dou']->get_one("SELECT COUNT(*) FROM " . $GLOBALS['dou']->table('guestbook') . " WHERE ip = '$ip' AND if_read = 0 AND reply_id = 0");
    
    // 如果管理员未回复的留言数量大于3
    if ($unread_messages >= '3')
        return true;
}

/**
 * +----------------------------------------------------------
 * 检查是否包含中文字符且长度符合要求
 * +----------------------------------------------------------
 */
function check_guestbook($value, $length) {
    $check_chinese = $GLOBALS['_CFG']['guestbook_check_chinese'] ? $GLOBALS['check']->if_include_chinese($value) : true;
    
    if ($check_chinese && $GLOBALS['check']->ch_length($value, $length)) {
        return true;
    }
}
?>