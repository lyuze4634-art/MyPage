<?php
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_login.php');
    exit;
}

$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (login($password)) {
    // 登录成功后跳转到后台页
    // 你后面如果做 admin.php，这里改成 admin.php
    header('Location: admin_dashboard.php');
    exit;
} else {
    header('Location: admin_login.php?error=1');
    exit;
}