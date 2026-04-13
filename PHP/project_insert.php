<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: project_add.php');
    exit;
}

$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$project_url = isset($_POST['project_url']) ? trim($_POST['project_url']) : '';
$sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
$imagePath = '';

if ($title === '') {
    header('Location: project_add.php?error=empty_title');
    exit;
}

if ($project_url !== '' && !filter_var($project_url, FILTER_VALIDATE_URL)) {
    $project_url = '';
}

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        header('Location: project_add.php?error=upload_failed');
        exit;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $originalName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        header('Location: project_add.php?error=invalid_image');
        exit;
    }

    $uploadDir = __DIR__ . '/uploads/projects/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            header('Location: project_add.php?error=upload_failed');
            exit;
        }
    }

    $newFileName = 'project_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $targetPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($tmpName, $targetPath)) {
        header('Location: project_add.php?error=upload_failed');
        exit;
    }

    $imagePath = 'uploads/projects/' . $newFileName;
}

try {
    $sql = "INSERT INTO projects (title, description, image, project_url, sort_order, created_at)
            VALUES (:title, :description, :image, :project_url, :sort_order, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':image' => $imagePath,
        ':project_url' => $project_url,
        ':sort_order' => $sort_order,
    ]);

    header('Location: admin_dashboard.php');
    exit;
} catch (PDOException $e) {
    if ($imagePath !== '') {
        $fullImagePath = __DIR__ . '/' . $imagePath;
        if (is_file($fullImagePath)) {
            unlink($fullImagePath);
        }
    }

    if (DEBUG_MODE) {
        exit('项目写入失败：' . $e->getMessage());
    }

    header('Location: project_add.php?error=insert_failed');
    exit;
}
