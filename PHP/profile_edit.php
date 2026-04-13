<?php include 'header.php'; ?>
<?php
require_once __DIR__ . '/db.php';

// 默认只管理 profile 表中的第 1 条数据
$profileId = 1;

$stmt = $pdo->prepare("SELECT * FROM profile WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $profileId]);
$profile = $stmt->fetch();

if (!$profile) {
    die('未找到个人资料，请先在 profile 表中插入一条数据。');
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑个人资料</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="url"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        .avatar-preview {
            margin-bottom: 10px;
        }
        .avatar-preview img {
            max-width: 180px;
            max-height: 180px;
            border: 1px solid #ccc;
            padding: 4px;
            display: block;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #555;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <a class="back-link" href="admin_dashboard.php">← 返回后台</a>

    <h1>编辑个人资料</h1>

    <form action="profile_update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo (int)$profile['id']; ?>">
        <input type="hidden" name="old_avatar" value="<?php echo htmlspecialchars($profile['avatar']); ?>">

        <div class="form-group">
            <label>当前头像</label>
            <div class="avatar-preview">
                <img src="<?php echo htmlspecialchars($profile['avatar']); ?>" alt="当前头像">
            </div>
        </div>

        <div class="form-group">
            <label for="avatar">更换头像</label>
            <input type="file" name="avatar" id="avatar" accept="image/*">
        </div>

        <div class="form-group">
            <label for="name">姓名</label>
            <input type="text" name="name" id="name"
                   value="<?php echo htmlspecialchars($profile['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="bio">个人简介</label>
            <textarea name="bio" id="bio" required><?php echo htmlspecialchars($profile['bio']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="github_url">GitHub 链接</label>
            <input type="url" name="github_url" id="github_url"
                   value="<?php echo htmlspecialchars($profile['github_url']); ?>">
        </div>

        <button type="submit" class="btn">保存修改</button>
    </form>

</body>
</html>
<?php include 'footer.php'; ?>