<?php
// db.php

require_once __DIR__ . '/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 出错抛异常
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // 默认返回关联数组
        PDO::ATTR_EMULATE_PREPARES   => false,                  // 使用真实预处理
    ]);
} catch (PDOException $e) {
    die('数据库连接失败：' . $e->getMessage());
}