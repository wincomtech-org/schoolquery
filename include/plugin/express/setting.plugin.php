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
 * Release Date: 2015-06-10
 */
if (!defined('IN_DOUCO')) {
    die('Hacking attempt');
}

/* 插件唯一ID
----------------------------------------------------------------------------- */
$plugin['unique_id'] = 'express';

/* 插件名称
----------------------------------------------------------------------------- */
$plugin['name'] = $plugin_mysql['name'] ? $plugin_mysql['name'] : '快递配送';

/* 插件描述
----------------------------------------------------------------------------- */
$plugin['description'] = $plugin_mysql['description'] ? $plugin_mysql['description'] : '速度快，价格实惠，超重不加价。';

/* 插件版本
----------------------------------------------------------------------------- */
$plugin['ver'] = '1.0';

/* 所属组
----------------------------------------------------------------------------- */
$plugin['plugin_group'] = 'shipping';

/* 插件参数提交表单
 * type默认为'text'及文本框，可选"text,select,textarea"
 * option默认为空，select默认选项，如array("选项一" => 0,"选项二" => 1)
----------------------------------------------------------------------------- */
// 配送费用
$plugin['config'][] = array (
        "field" => 'fee',
        "name" => '配送费用',
        "desc" => '如果无需运费，则留空或为零',
        "value" => isset($plugin_mysql['config']['fee']) ? $plugin_mysql['config']['fee'] : 10
);

// 满多少钱包邮
$plugin['config'][] = array (
        "field" => 'free',
        "name" => '满多少钱包邮',
        "desc" => '设置用户买满多少钱后包邮，如果不设定则留空或为零。',
        "value" => isset($plugin_mysql['config']['free']) ? $plugin_mysql['config']['free'] : 0
);
?>