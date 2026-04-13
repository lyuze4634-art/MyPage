<?php
// auth.php

require_once __DIR__ . '/config.php';

/**
 * 判断是否已登录
 */
function is_logged_in()
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * 登录验证
 */
function login($password)
{
    if ($password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['login_time'] = time();
        return true;
    }
    return false;
}

/**
 * 退出登录
 */
function logout()
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
}

/**
 * 强制要求登录
 * 未登录就跳转到 admin_login.php
 */
function require_login()
{
    if (!is_logged_in()) {
        header('Location: admin_login.php');
        exit;
    }
}