<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Config/Database.php';

$config = $config ?? json_decode(file_get_contents(__DIR__ . '/../../data.json'), true);
$appRoot = str_replace('\\', '/', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

$authorId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : $_SESSION['user_id'] ?? null;
if (!$authorId) {
    header('Location: ' . $appRoot . '/index.php');
    exit;
}

$connection = (new Database())->getConnection();

$stmt = $connection->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $authorId);
$stmt->execute();
$author = $stmt->get_result()->fetch_assoc();

if (!$author) {
    include __DIR__ . '/../Layouts/header.php';
    echo '<div class="container"><p>Author not found.</p></div>';
    include __DIR__ . '/../Layouts/footer.php';
    exit;
}

$articleStmt = $connection->prepare(
    'SELECT id, title, featured_image_path, created_at, status
     FROM articles
     WHERE author_id = ?
     ORDER BY created_at DESC'
);
$articleStmt->bind_param('i', $authorId);
$articleStmt->execute();
$articleResult = $articleStmt->get_result();
$articles = [];
while ($row = $articleResult->fetch_assoc()) {
    $articles[] = $row;
}

$appRoot = str_replace('\\', '/', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
include __DIR__ . '/../Layouts/header.php';
?>

<div class="container" style="padding: 40px 20px;">
    <div style="display:flex; gap: 24px; flex-wrap:wrap; align-items:center; margin-bottom: 32px;">
        <div style="width:120px; height:120px; border-radius:999px; overflow:hidden; background:#f0f0f0;">
            <img src="<?= !empty($author['profile_pic_path']) ? htmlspecialchars($author['profile_pic_path']) : 'https://placehold.co/120x120' ?>" alt="<?= htmlspecialchars($author['name']) ?>" style="width:100%; height:100%; object-fit:cover;">
        </div>
        <div>
            <h1 style="margin-bottom:8px; font-size:2rem;"><?= htmlspecialchars($author['name']) ?></h1>
            <p style="color:#555; max-width:720px; font-size:1rem; line-height:1.6;"><?= nl2br(htmlspecialchars($author['bio'] ?? 'This author has not added a bio yet.')) ?></p>
            <p style="margin-top:12px; color:#777;">Role: <?= htmlspecialchars($author['role'] ?? 'reader') ?></p>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $authorId): ?>
                <p><a href="<?= $appRoot ?>/index.php?page=dashboard" style="color:#1a73e8;">Manage your articles</a></p>
            <?php endif; ?>
        </div>
    </div>

    <h2 style="margin-bottom:18px;">Published Articles</h2>
    <?php if (!empty($articles)): ?>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:20px;">
            <?php foreach ($articles as $article): ?>
                <div style="background:white; border-radius:16px; box-shadow:0 10px 25px rgba(0,0,0,0.05); overflow:hidden;">
                    <div style="height:180px; overflow:hidden;">
                        <img src="<?= !empty($article['featured_image_path']) ? htmlspecialchars($article['featured_image_path']) : 'https://placehold.co/400x180' ?>" alt="<?= htmlspecialchars($article['title']) ?>" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div style="padding:16px;">
                        <h3 style="margin:0 0 10px;"><a href="<?= $appRoot ?>/index.php?page=article&id=<?= $article['id'] ?>" style="color:#111; text-decoration:none;"><?= htmlspecialchars($article['title']) ?></a></h3>
                        <span style="color:#555; font-size:13px;"><?= htmlspecialchars($article['status']) ?> · <?= date('M d, Y', strtotime($article['created_at'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color:#555;">No articles published yet.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../Layouts/footer.php';
