<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

// 必须登录后才能删除
require_login();

// 1. 获取项目ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('无效的项目ID');
}

// 是否删除图片文件：
// 访问方式示例：project_delete.php?id=3&delete_image=1
$deleteImage = isset($_GET['delete_image']) && $_GET['delete_image'] == '1';

try {
    // 2. 先查询项目，拿到图片路径
    $sql = "SELECT id, image FROM projects WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $project = $stmt->fetch();

    if (!$project) {
        die('项目不存在');
    }

    // 3. 删除数据库记录
    $deleteSql = "DELETE FROM projects WHERE id = :id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->execute([':id' => $id]);

    // 4. 如果用户要求删除图片文件，则再删除服务器上的图片
    if ($deleteImage && !empty($project['image'])) {
        /*
         * 假设数据库里存的是类似：
         * uploads/projects/abc.jpg
         * 那么要转成服务器绝对路径：
         * __DIR__ . '/uploads/projects/abc.jpg'
         */
        $imagePath = $project['image'];

        // 去掉开头可能存在的 /，避免路径拼接出错
        $relativePath = ltrim($imagePath, '/\\');

        $fullPath = __DIR__ . '/' . $relativePath;

        if (file_exists($fullPath) && is_file($fullPath)) {
            unlink($fullPath);
        }
    }

    // 5. 删除成功后跳回后台列表
    header('Location: admin_dashboard.php');
    exit;

} catch (PDOException $e) {
    die('删除失败：' . $e->getMessage());
}