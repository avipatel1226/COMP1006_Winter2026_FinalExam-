<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/connect.php';

$id = $_GET['id'] ?? '';

if (empty($id) || !is_numeric($id)) {
    $_SESSION['error'] = "Invalid image id.";
    header("Location: gallery.php");
    exit;
}

$sql = "SELECT * FROM images WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$image = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$image) {
    $_SESSION['error'] = "Image not found.";
    header("Location: gallery.php");
    exit;
}

if (file_exists($image['image_path'])) {
    unlink($image['image_path']);
}

$deleteSql = "DELETE FROM images WHERE id = :id";
$deleteStmt = $pdo->prepare($deleteSql);
$deleteStmt->execute([':id' => $id]);

$_SESSION['success'] = "Image deleted successfully.";
header("Location: gallery.php");
exit;