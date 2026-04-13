<?php include 'header.php'; ?>
<?php
require_once __DIR__ . '/auth.php';

require_login();

$error = isset($_GET['error']) ? trim($_GET['error']) : '';
$message = '';

switch ($error) {
    case 'empty_title':
        $message = '项目名称不能为空';
        break;
    case 'upload_failed':
        $message = '图片上传失败，请重新上传';
        break;
    case 'invalid_image':
        $message = '只能上传 jpg、jpeg、png、gif、webp 格式的图片';
        break;
    case 'insert_failed':
        $message = '项目写入数据库失败';
        break;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增项目</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, "Microsoft YaHei", sans-serif;
            background: #f5f7fb;
            color: #333;
        }

        .container {
            max-width: 760px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        h1 {
            font-size: 28px;
        }

        .back-link {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error-box {
            margin-bottom: 20px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #ffeaea;
            color: #d93025;
            border: 1px solid #f4b9b9;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .required {
            color: #dc3545;
        }

        input[type="text"],
        input[type="url"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dcdfe6;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            background: #fff;
        }

        input[type="text"]:focus,
        input[type="url"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            border-color: #007bff;
        }

        textarea {
            resize: vertical;
            min-height: 140px;
        }

        .tip {
            margin-top: 6px;
            font-size: 13px;
            color: #777;
            line-height: 1.5;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 8px;
            padding: 12px 18px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-submit {
            background: #28a745;
            color: #fff;
        }

        .btn-cancel {
            background: #6c757d;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="top-bar">
        <h1>新增项目</h1>
        <a href="admin_dashboard.php" class="back-link">← 返回后台管理</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="error-box"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form action="project_insert.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">项目名称 <span class="required">*</span></label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="description">项目简介</label>
            <textarea id="description" name="description" placeholder="输入项目简介、技术栈、说明等"></textarea>
        </div>

        <div class="form-group">
            <label for="project_url">项目链接</label>
            <input type="url" id="project_url" name="project_url" placeholder="https://example.com">
        </div>

        <div class="form-group">
            <label for="sort_order">排序值</label>
            <input type="number" id="sort_order" name="sort_order" value="0" min="0" step="1">
            <div class="tip">数字越小越靠前显示。默认填 0 即可。</div>
        </div>

        <div class="form-group">
            <label for="image">项目图片</label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp">
            <div class="tip">支持 jpg、jpeg、png、gif、webp。可以先不传图片。</div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-submit">保存项目</button>
            <a href="admin_dashboard.php" class="btn btn-cancel">取消</a>
        </div>
    </form>
</div>

</body>
</html>
<?php include 'footer.php'; ?>