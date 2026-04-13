<?php
require_once __DIR__ . '/db.php';

// 判断 id 是否存在且为数字
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('项目ID无效');
}

$id = (int)$_GET['id'];

// 查询单个项目
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? LIMIT 1");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - 项目详情</title>
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
            line-height: 1.6;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
        }

        .detail-card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .project-image {
            width: 100%;
            max-height: 420px;
            border-radius: 16px;
            overflow: hidden;
            background: #e9edf5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
        }

        .project-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .placeholder {
            color: #888;
            font-size: 14px;
        }

        .description {
            font-size: 16px;
            color: #555;
            white-space: pre-wrap;
            margin-bottom: 25px;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background: #007bff;
            padding: 10px 18px;
            border-radius: 8px;
        }

        .btn.github {
            background: #24292e;
        }

        .meta {
            margin-top: 20px;
            color: #777;
            font-size: 14px;
        }

        .project-title-link {
            color: #333;
            text-decoration: none;
        }

        .project-title-link:hover {
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="detail-card">
        <a href="index.php" class="back-link">← 返回首页</a>

        <h1><?php echo htmlspecialchars($project['title']); ?></h1>

        <div class="project-image">
            <?php if (!empty($project['image'])): ?>
                <img src="<?php echo htmlspecialchars($project['image']); ?>" alt="项目图片">
            <?php else: ?>
                <div class="placeholder">暂无项目图片</div>
            <?php endif; ?>
        </div>

        <div class="description">
            <?php echo !empty($project['description']) ? htmlspecialchars($project['description']) : '暂无项目介绍'; ?>
        </div>

        <div class="btn-group">
            <?php if (!empty($project['project_url'])): ?>
                <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank" class="btn">
                    打开项目链接
                </a>
            <?php endif; ?>
        </div>

        <div class="meta">
            项目 ID：<?php echo $project['id']; ?>
        </div>
    </div>
</div>

</body>
</html>