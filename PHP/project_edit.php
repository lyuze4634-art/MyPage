<?php
require_once 'config.php';
require_once 'db.php';
require_once 'auth.php';

require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('项目ID无效');
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if (!$project) {
    exit('未找到该项目');
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>编辑项目</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f7fb;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-top: 16px;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="url"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            font-size: 14px;
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        .preview {
            margin-top: 10px;
        }

        .preview img {
            max-width: 220px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .btn-group {
            margin-top: 24px;
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-save {
            background: #2563eb;
            color: #fff;
        }

        .btn-back {
            background: #e5e7eb;
            color: #111827;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>编辑项目</h1>

        <form action="project_update.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo (int)$project['id']; ?>">
            <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($project['image']); ?>">

            <label for="title">项目标题</label>
            <input
                type="text"
                id="title"
                name="title"
                value="<?php echo htmlspecialchars($project['title']); ?>"
                required
            >

            <label for="description">项目描述</label>
            <textarea
                id="description"
                name="description"
                required
            ><?php echo htmlspecialchars($project['description']); ?></textarea>

            <label for="project_url">项目链接</label>
            <input
                type="url"
                id="project_url"
                name="project_url"
                value="<?php echo htmlspecialchars($project['project_url']); ?>"
            >

            <label>当前图片</label>
            <div class="preview">
                <?php if (!empty($project['image'])): ?>
                    <img src="<?php echo htmlspecialchars($project['image']); ?>" alt="当前项目图片">
                <?php else: ?>
                    <p>当前没有图片</p>
                <?php endif; ?>
            </div>

            <label for="image">更换图片（不选则保留原图）</label>
            <input type="file" id="image" name="image" accept="image/*">

            <div class="btn-group">
                <button type="submit" class="btn btn-save">保存修改</button>
                <a href="admin_dashboard.php" class="btn btn-back">返回后台</a>
            </div>
        </form>
    </div>
</body>
</html>