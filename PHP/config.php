<?php
// config.php

// =========================
// 基础设置
// =========================
define('SITE_NAME', 'Personal Homepage');

// 你的固定后台密码（后续增删改查时使用）
define('ADMIN_PASSWORD', '请在这里改成你的固定密码');

// 是否显示报错
define('DEBUG_MODE', true);

// 时区
date_default_timezone_set('Asia/Tokyo');

// =========================
// 数据库设置
// =========================
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'personal_homepage');
define('DB_USER', 'root');
define('DB_PASS', '');   // 这里改成你自己的 MySQL 密码
define('DB_CHARSET', 'utf8mb4');

// =========================
// Session 设置
// =========================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =========================
// 调试设置
// =========================
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}