<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

// 必须登录后才能进入后台
require_login();

try {
    $sql = "SELECT id, title, description, image, project_url, created_at 
            FROM projects 
            ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $projects = $stmt->fetchAll();
} catch (PDOException $e) {
    die('项目查询失败：' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, "Microsoft YaHei", sans-serif;
            background: #f5f7fb;
            color: #333;
        }

        .container {
            max-width: 1100px;
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

        .top-bar h1 {
            margin: 0;
            font-size: 28px;
        }

        .action-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #fff;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn-add {
            background: #28a745;
        }

        .btn-profile {
            background: #17a2b8;
        }

        .btn-logout {
            background: #dc3545;
        }

        .btn-edit {
            background: #007bff;
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-delete {
            background: #dc3545;
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-view {
            background: #6c757d;
            padding: 6px 12px;
            font-size: 13px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th, td {
            border-bottom: 1px solid #eee;
            padding: 14px 10px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #f8f9fa;
            font-size: 15px;
        }

        td {
            font-size: 14px;
        }

        .thumb {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: #fafafa;
        }

        .empty-image {
            width: 80px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed #ccc;
            border-radius: 6px;
            color: #999;
            font-size: 12px;
            background: #fafafa;
        }

        .project-title {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .project-desc {
            color: #666;
            line-height: 1.5;
            max-width: 280px;
            word-break: break-word;
        }

        .link-text {
            color: #007bff;
            text-decoration: none;
            word-break: break-all;
        }

        .link-text:hover {
            text-decoration: underline;
        }

        .operation-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .empty-box {
            padding: 40px 20px;
            text-align: center;
            color: #666;
            background: #fafafa;
            border: 1px dashed #ddd;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="top-bar">
        <h1>后台总管理页</h1>
        <div class="action-group">
            <a href="project_add.php" class="btn btn-add">+ 新增项目</a>
            <a href="project_insert.php" class="btn btn-profile">编辑个人资料</a>
            <a href="logout.php" class="btn btn-logout">退出登录</a>
        </div>
    </div>

    <?php if (!empty($projects)): ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>图片</th>
                        <th>项目名称</th>
                        <th>项目链接</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $row): ?>
                        <tr>
                            <td><?php echo (int)$row['id']; ?></td>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img class="thumb" src="<?php echo htmlspecialchars($row['image']); ?>" alt="项目图片">
                                <?php else: ?>
                                    <div class="empty-image">无图片</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="project-title">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </div>
                                <div class="project-desc">
                                    <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($row['project_url'])): ?>
                                    <a class="link-text" href="<?php echo htmlspecialchars($row['project_url']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($row['project_url']); ?>
                                    </a>
                                <?php else: ?>
                                    <span>无链接</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['created_at']); ?>
                            </td>
                            <td>
                                <div class="operation-group">
                                    <a href="project_detail.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-view">查看</a>
                                    <a href="project_edit.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-edit">编辑</a>
                                    <a href="project_delete.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-delete" onclick="return confirm('确定要删除这个项目吗？');">删除</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-box">
            目前还没有项目，先点击上方“新增项目”添加一个吧。
        </div>
    <?php endif; ?>
</div>

</body>
</html>