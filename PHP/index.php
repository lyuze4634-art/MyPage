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
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
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
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
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
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .text-link {
            color: #007bff;
            word-break: break-all;
        }

        .chat-section {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 24px;
            margin-bottom: 40px;
        }

        .chat-title {
            font-size: 28px;
            margin-bottom: 18px;
        }

        .chat-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 18px;
        }

        .chat-box {
            height: 360px;
            overflow-y: auto;
            background: #f7f9fc;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            border: 1px solid #e5e7eb;
        }

        .chat-message {
            display: flex;
            margin-bottom: 14px;
        }

        .chat-message.user {
            justify-content: flex-start;
        }

        .chat-message.bot {
            justify-content: flex-end;
        }

        .chat-bubble {
            max-width: 75%;
            padding: 12px 14px;
            border-radius: 14px;
            line-height: 1.7;
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .chat-message.user .chat-bubble {
            background: #e8f0ff;
            color: #222;
            border-bottom-left-radius: 6px;
        }

        .chat-message.bot .chat-bubble {
            background: #24292e;
            color: #fff;
            border-bottom-right-radius: 6px;
        }

        .chat-form {
            display: flex;
            gap: 12px;
            align-items: stretch;
        }

        .chat-form textarea {
            flex: 1;
            min-height: 90px;
            resize: vertical;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 12px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
        }

        .chat-form textarea:focus {
            border-color: #007bff;
        }

        .chat-form button {
            width: 120px;
            border: none;
            border-radius: 12px;
            background: #24292e;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
        }

        .chat-form button:hover {
            opacity: 0.92;
        }

        .chat-form button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .chat-tips {
            margin-top: 10px;
            font-size: 12px;
            color: #777;
        }

        .split-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            align-items: stretch;
        }

        .chat-left,
        .chat-right {
            min-width: 0;
        }

        @media (max-width: 768px) {
            .chat-form {
                flex-direction: column;
            }

            .chat-form button {
                width: 100%;
                height: 46px;
            }

            .chat-bubble {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- 这里是名片card -->
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

        <!-- 这里是AIcard和介绍card -->
        <section class="chat-section split-section">


            <!-- 左组件 -->
            <div class="chat-left">
                <h2 class="chat-title">聊天助手</h2>
                <p class="chat-subtitle">左边是你的问题，右边是机器人的回答。</p>

                <div id="chatBox" class="chat-box">
                    <div class="chat-message bot">
                        <div class="chat-bubble">你好，可以问我网站开发、PHP、数据库、服务器部署等相关问题。</div>
                    </div>
                </div>

                <form id="chatForm" class="chat-form">
                    <textarea id="messageInput" placeholder="请输入你的问题"></textarea>
                    <button type="submit" id="sendBtn">发送</button>
                </form>

                <div class="chat-tips">按 Enter 发送，Shift + Enter 换行。</div>

            </div>

            <!-- 右组件 -->
            <div class="chat-right">
                <h2 class="motivation-title">给自己的话</h2>
                <div class="motivation-box">
                    <p>先完成，再完美。</p>
                    <p>每天进步一点点，时间会给你答案。</p>
                    <p>会写、会改、会部署，就是竞争力。</p>
                    <p>不要怕慢，怕的是停下来。</p>
                    <p>把想法做成作品，比空想更重要。</p>
                </div>

            </div>


        </section>

        <!-- 这里是项目card -->
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

    <script>
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const chatBox = document.getElementById('chatBox');
        const sendBtn = document.getElementById('sendBtn');

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function addMessage(role, text) {
            const message = document.createElement('div');
            message.className = 'chat-message ' + role;

            const bubble = document.createElement('div');
            bubble.className = 'chat-bubble';
            bubble.innerHTML = escapeHtml(text);

            message.appendChild(bubble);
            chatBox.appendChild(message);
            chatBox.scrollTop = chatBox.scrollHeight;

            return bubble;
        }

        if (chatForm) {
            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const message = messageInput.value.trim();
                if (!message) return;

                addMessage('user', message);
                messageInput.value = '';
                sendBtn.disabled = true;

                const loadingBubble = addMessage('bot', '思考中...');

                try {
                    const response = await fetch('chat.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    });

                    const data = await response.json();
                    loadingBubble.innerHTML = escapeHtml(data.reply || '没有返回内容');
                } catch (error) {
                    loadingBubble.innerHTML = '请求失败，请检查 chat.php、config.php 或服务器环境。';
                }

                sendBtn.disabled = false;
                chatBox.scrollTop = chatBox.scrollHeight;
            });

            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    chatForm.dispatchEvent(new Event('submit'));
                }
            });
        }
    </script>

</body>

</html>
<?php include 'footer.php'; ?>