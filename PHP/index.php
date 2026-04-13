<?php include 'header.php'; ?>
<?php
require_once __DIR__ . '/db.php';

// 读取个人信息（默认只取 profile 表第一条）
$stmt = $pdo->query("SELECT * FROM profile ORDER BY id ASC LIMIT 1");
$profile = $stmt->fetch();

// 读取项目列表
$stmt = $pdo->query("SELECT * FROM projects ORDER BY sort_order ASC, id DESC");
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的个人主页</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        .project-title-link {
            color: #333;
            text-decoration: none;
        }

        .project-title-link:hover {
            color: #007bff;
        }
        body {
            font-family: Arial, "Microsoft YaHei", sans-serif;
            background: #f5f7fb;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
        }

        .profile-section {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: flex-start;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }

        .avatar-box {
            width: 220px;
            height: 220px;
            border-radius: 16px;
            overflow: hidden;
            background: #e9edf5;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            color: #888;
            font-size: 14px;
        }

        .profile-info {
            flex: 1;
            min-width: 280px;
            
        }

        .profile-info h1 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .profile-info p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
            white-space: pre-wrap;
            
        }

        .github-link a {
            display: inline-block;
            padding: 10px 18px;
            background: #24292e;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
        }

        .section-title {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .project-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .project-image {
            width: 100%;
            height: 180px;
            background: #e9edf5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .project-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .project-placeholder {
            color: #888;
            font-size: 14px;
        }

        .project-content {
            padding: 18px;
        }

        .project-content h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .project-content p {
            font-size: 15px;
            color: #666;
            margin-bottom: 15px;
            min-height: 72px;
            white-space: pre-wrap;
            word-break: break-all;
            overflow-wrap: anywhere;
        }

        .project-content a {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background: #007bff;
            padding: 8px 14px;
            border-radius: 8px;
        }

        .empty-box {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            color: #777;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .text-link {
            color: #007bff;
            word-break: break-all;
        }
    </style>
</head>
<body>

<div class="container">

    <section class="profile-section">
        <div class="avatar-box">
            <?php if (!empty($profile) && !empty($profile['avatar'])): ?>
                <img src="<?php echo htmlspecialchars($profile['avatar']); ?>" alt="头像">
            <?php else: ?>
                <div class="avatar-placeholder">暂无头像</div>
            <?php endif; ?>
        </div>

        <div class="profile-info">
            <h1>
                <?php echo !empty($profile['name']) ? htmlspecialchars($profile['name']) : '未设置姓名'; ?>
            </h1>

            <p><?php echo !empty($profile['bio']) ? htmlspecialchars(trim($profile['bio'])) : '暂无简介'; ?></p>

            <div class="github-link">
                <?php if (!empty($profile['github_url'])): ?>
                    <a href="<?php echo htmlspecialchars($profile['github_url']); ?>" target="_blank">
                        查看 GitHub
                    </a>
                <?php else: ?>
                    <span>暂无 GitHub 链接</span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section>
        <h2 class="section-title">我的项目</h2>

        <?php if (!empty($projects)): ?>
            <div class="projects-grid">
                <?php foreach ($projects as $project): ?>
                    <div class="project-card">
                        <div class="project-image">
                            <?php if (!empty($project['image'])): ?>
                                <img src="<?php echo htmlspecialchars($project['image']); ?>" alt="项目图片">
                            <?php else: ?>
                                <div class="project-placeholder">暂无项目图片</div>
                            <?php endif; ?>
                        </div>

                        <div class="project-content">
                            <h3>
                                <a href="project_detail.php?id=<?php echo $project['id']; ?>" class="project-title-link">
                                    <?php echo htmlspecialchars($project['title']); ?>
                                </a>
                            </h3>
                        
                            <p><?php echo !empty($project['description']) ? htmlspecialchars($project['description']) : '暂无项目介绍'; ?></p>
                        
                            <a href="project_detail.php?id=<?php echo $project['id']; ?>">
                                查看详情
                            </a>
                        
                            <?php if (!empty($project['project_url'])): ?>
                                <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank"
                                    style="margin-left:10px; background:#24292e;">
                                    项目链接
                                </a>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">暂时还没有项目数据</div>
        <?php endif; ?>
    </section>

</div>



</body>
</html>
<?php include 'footer.php'; ?>