<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/connect.php';

$title = trim($_POST['title'] ?? '');

if (empty($title)) {
    $_SESSION['error'] = "Title is required.";
    header("Location: gallery.php");
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
    $_SESSION['error'] = "Please select an image.";
    header("Location: gallery.php");
    exit;
}

$fileName = $_FILES['image']['name'];
$tmpName = $_FILES['image']['tmp_name'];
$fileSize = $_FILES['image']['size'];

$allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($extension, $allowedTypes)) {
    $_SESSION['error'] = "Invalid file type.";
    header("Location: gallery.php");
    exit;
}

if ($fileSize > 5 * 1024 * 1024) {
    $_SESSION['error'] = "File too large.";
    header("Location: gallery.php");
    exit;
}

if (!is_dir('uploads')) {
    mkdir('uploads');
}

$newName = uniqid('img_', true) . '.' . $extension;
$targetPath = 'uploads/' . $newName;

if (move_uploaded_file($tmpName, $targetPath)) {

    $sql = "INSERT INTO images (title, image_path) VALUES (:title, :path)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':path' => $targetPath
    ]);

    $_SESSION['success'] = "Image uploaded successfully.";
} else {
    $_SESSION['error'] = "Upload failed.";
}

header("Location: gallery.php");
exit;