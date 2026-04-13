<?php
require_once 'config.php';
require_once 'db.php';
require_once 'auth.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('非法请求');
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$link = trim($_POST['project_url'] ?? '');
$old_image = trim($_POST['old_image'] ?? '');

if ($id <= 0) {
    exit('项目ID无效');
}

if ($title === '' || $description === '') {
    exit('标题和描述不能为空');
}

// 默认保留旧图
$image_path = $old_image;

// 如果上传了新图片
if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        exit('图片上传失败');
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($_FILES['image']['tmp_name']);

    if (!in_array($file_type, $allowed_types)) {
        exit('只允许上传 jpg、png、gif、webp 格式图片');
    }

    $uploadDir = __DIR__ . '/uploads/projects/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            header('Location: project_add.php?error=upload_failed');
            exit;
        }
    }

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $new_filename = 'project_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target_path = $uploadDir . $new_filename;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        exit('保存图片失败');
    }

    $image_path = 'uploads/projects/' . $new_filename;

    // 删除旧图片
    if (!empty($old_image)) {
        $old_full_path = __DIR__ . '/' . $old_image;
        if (file_exists($old_full_path)) {
            unlink($old_full_path);
        }
    }
}

$stmt = $pdo->prepare("
    UPDATE projects 
    SET title = ?, description = ?, image = ?, project_url = ?
    WHERE id = ?
");

try {
    $stmt->execute([$title, $description, $image_path, $link, $id]);
    header("Location: admin_dashboard.php");
    exit;
} catch (PDOException $e) {
    exit("更新失败：" . $e->getMessage());
}