<?php include 'header.php'; ?>
<?php
require_once __DIR__ . '/auth.php';

// 如果已经登录，直接跳到后台首页（你后面做 admin.php 时可改这里）
if (is_logged_in()) {
    header('Location: admin_dashboard.php');
    exit;
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
$message = '';

if ($error === '1') {
    $message = '密码错误，请重新输入';
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台登录</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, "Microsoft YaHei", sans-serif;
            background: #f5f7fb;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        .desc {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .error-message {
            background: #ffeaea;
            color: #d93025;
            border: 1px solid #f5b5b5;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
            font-weight: bold;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dcdfe6;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        input[type="password"]:focus {
            border-color: #007bff;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0069d9;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h1>后台登录</h1>
    <div class="desc">请输入固定密码进入后台</div>

    <?php if (!empty($message)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="login_process.php" method="post">
        <div class="form-group">
            <label for="password">后台密码</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">登录</button>
    </form>

    <a href="index.php" class="back-link">返回首页</a>
</div>

</body>
</html>
<?php include 'footer.php'; ?>