<?php
/**************************************************************************
 File Name: cache_list.php
 Author: liukai
 Created Time: Mon 24 Aug 2015 02:05:20 PM CST
**************************************************************************/
//yaf 使用命令行方式
//可以直接使用model，完成autoload


define('APPLICATION_PATH', dirname(dirname(__FILE__)));

$app = new Yaf_Application(APPLICATION_PATH. "/conf/application.ini");
//把配置保存起来
$arrConfig = Yaf_Application::app()->getConfig();
Yaf_Registry::set('config', $arrConfig);

$app->execute('main');

function main(){
    echo "running";
}
