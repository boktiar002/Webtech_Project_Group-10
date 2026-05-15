<?php

require_once __DIR__ . '/../../Config/Database.php';

$config = $config ?? json_decode(file_get_contents(__DIR__ . '/../../data.json'), true);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Author ID Missing");
}

$authorId = (int) $_GET['id'];
$connection = (new Database())->getConnection();

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $authorId);
$stmt->execute();
$author = $stmt->get_result()->fetch_assoc();

if (!$author) {
    die("Author Not Found");
}

$socialLinks = [];
if (!empty($author['social_links'])) {
    $socialLinks = json_decode($author['social_links'], true) ?: [];
}

$articles = [];
$articleQuery = "SELECT id, title
    FROM articles
    WHERE author_id = ?
      AND status = 'published'
    ORDER BY created_at DESC";
$articleStmt = $connection->prepare($articleQuery);
$articleStmt->bind_param("i", $authorId);
$articleStmt->execute();
$articleResult = $articleStmt->get_result();

while ($article = $articleResult->fetch_assoc()) {
    $articles[] = $article;
}

if (!empty($author['profile_pic_path'])) {
    $image = "/Webtech_Project_Group-10/" . ltrim($author['profile_pic_path'], '/');
} else {
    $image = "https://placehold.co/150x150";
}

include __DIR__ . '/../Layouts/header.php';
?>

<h2>Author Profile</h2>

<img
src="<?php echo htmlspecialchars($image); ?>"
width="150"
height="150"
alt="Profile Image"
>

<br><br>

<h3><?php echo htmlspecialchars($author['name']); ?></h3>

<h3>Bio</h3>
<p><?php echo htmlspecialchars($author['bio'] ?? "No bio added"); ?></p>

<h3>Social Links</h3>

<p>
Twitter:
<a href="<?php echo htmlspecialchars($socialLinks['twitter'] ?? '#'); ?>" target="_blank">
<?php echo htmlspecialchars($socialLinks['twitter'] ?? "No Twitter"); ?>
</a>
</p>

<p>
GitHub:
<a href="<?php echo htmlspecialchars($socialLinks['github'] ?? '#'); ?>" target="_blank">
<?php echo htmlspecialchars($socialLinks['github'] ?? "No GitHub"); ?>
</a>
</p>

<h3>Published Articles</h3>

<?php if (!empty($articles)) { ?>
    <?php foreach ($articles as $article) { ?>
        <p>
            <a href="/Webtech_Project_Group-10/index.php?page=article&id=<?php echo $article['id']; ?>">
                <?php echo htmlspecialchars($article['title']); ?>
            </a>
        </p>
    <?php } ?>
<?php } else { ?>
    <p>No published articles found</p>
<?php } ?>

<br><br>

<a href="/Webtech_Project_Group-10/index.php">Home</a>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
