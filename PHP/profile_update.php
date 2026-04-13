<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('非法请求');
}

$id         = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$name       = trim($_POST['name'] ?? '');
$bio        = trim($_POST['bio'] ?? '');
$github_url = trim($_POST['github_url'] ?? '');
$oldAvatar  = trim($_POST['old_avatar'] ?? '');

if ($id <= 0) {
    die('资料 ID 无效');
}

if ($name === '' || $bio === '') {
    die('姓名和简介不能为空');
}

// 默认使用旧头像
$avatarPath = $oldAvatar;

// 处理新头像上传
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        die('头像上传失败');
    }

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    $fileTmp  = $_FILES['avatar']['tmp_name'];
    $fileType = mime_content_type($fileTmp);

    if (!isset($allowedTypes[$fileType])) {
        die('只允许上传 jpg、png、gif、webp 格式图片');
    }

    $extension = $allowedTypes[$fileType];

    // 上传目录
    $uploadDir = __DIR__ . '/uploads/projects/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die('创建上传目录失败');
        }
    }

    // 新文件名
    $newFileName = 'project_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $targetFile  = $uploadDir . $newFileName;

    if (!move_uploaded_file($fileTmp, $targetFile)) {
        die('保存头像失败');
    }

    // 数据库存相对路径
    $avatarPath = 'uploads/projects/' . $newFileName;

    // 删除旧头像文件（可选）
    if (!empty($oldAvatar)) {
        $oldFile = __DIR__ . '/' . $oldAvatar;

        // 防止把默认图片误删，你可以按自己需要改条件
        if (file_exists($oldFile) && is_file($oldFile)) {
            @unlink($oldFile);
        }
    }
}

// 更新数据库
$sql = "UPDATE profile 
        SET avatar = :avatar,
            name = :name,
            bio = :bio,
            github_url = :github_url,
            updated_at = NOW()
        WHERE id = :id";

$stmt = $pdo->prepare($sql);
$result = $stmt->execute([
    'avatar'     => $avatarPath,
    'name'       => $name,
    'bio'        => $bio,
    'github_url' => $github_url,
    'id'         => $id
]);

if ($result) {
    header('Location: profile_edit.php?success=1');
    exit;
} else {
    die('更新失败');
}