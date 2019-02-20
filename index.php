<?php
/*
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Perpose: landing first page to execute for project
 */
global $start_time, $started_memory;
$start_time=microtime(true);
header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' data: fonts.googleapis.com fonts.gstatic.com s3.ap-south-1.amazonaws.com;frame-ancestors 'self';");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=63072000;");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
header("Referrer-Policy: no-referrer, strict-origin-when-cross-origin");
$started_memory = array("memory_usage_real_usage_off" => memory_get_usage(false),"memory_usage_real_usage_on" => memory_get_usage(true));
$web_down=0;
$web_down_allow_ips=array('127.0.0.1','180.151.85.178','103.48.109.90');
$web_template='web_maintenance.php';
$web_maintenance_msg='Adhyayan software is under service and will be functional by 26-Dec-2017 17:00 HRS IST';
$current_ip=$_SERVER['REMOTE_ADDR'];
if($web_down==1 && !in_array($current_ip,$web_down_allow_ips)){
require_once($web_template);    
die();
}

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).DS);

require_once (ROOT . 'config' . DS . 'config.php');
require_once (ROOT . 'config' . DS . 'config_var.php');
require_once (ROOT . 'library' . DS . 'shared.php');
