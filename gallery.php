<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/connect.php';

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';

unset($_SESSION['success'], $_SESSION['error']);

$sql = "SELECT * FROM images ORDER BY id DESC";
$stmt = $pdo->query($sql);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h2>

<p><a href="logout.php">Logout</a></p>

<?php if (!empty($success)): ?>
    <p><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <p><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<h3>Upload New Image</h3>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="title">Image Title</label><br>
    <input type="text" name="title" id="title"><br><br>

    <label for="image">Select Image</label><br>
    <input type="file" name="image" id="image"><br><br>

    <button type="submit">Upload Image</button>
</form>

<h3>Gallery</h3>

<?php if ($images): ?>
    <?php foreach ($images as $image): ?>
        <div>
            <h4><?php echo htmlspecialchars($image['title']); ?></h4>

            <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                 alt="<?php echo htmlspecialchars($image['title']); ?>" 
                 width="250">

            <p>
                <a href="delete.php?id=<?php echo $image['id']; ?>" onclick="return confirm('Are you sure you want to delete this image?');">
                    Delete
                </a>
            </p>

            <hr>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No images uploaded yet.</p>
<?php endif; ?>

</body>
</html>