<?php

include("../../Config/database.php");

// URL থেকে Author ID

if(!isset($_GET['id'])){

    die("Author ID Missing");

}

$authorId = $_GET['id'];


// AUTHOR QUERY

$query = "

SELECT *
FROM users
WHERE id=?

";

$stmt = $conn->prepare($query);

$stmt->execute([

    $authorId

]);

$author = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$author){

    die("Author Not Found");

}


// SOCIAL LINKS

$socialLinks = [];

if(!empty($author['social_links'])){

    $socialLinks = json_decode(

        $author['social_links'],
        true

    );

}


// ARTICLES

$articleQuery = "

SELECT *
FROM articles
WHERE author_id=?
AND status='published'
ORDER BY created_at DESC

";

$articleStmt =

$conn->prepare(

    $articleQuery

);

$articleStmt->execute([

    $authorId

]);

$articles =

$articleStmt->fetchAll(

    PDO::FETCH_ASSOC

);


// IMAGE PATH

$image =

!empty(

    $author['profile_pic_path']

)

?

"../../".$author['profile_pic_path']

:

"../../Public/uploads/avatars/default.png";

?>

<!DOCTYPE html>

<html>

<head>

<title>Author Profile</title>

</head>

<body>

<h2>Author Profile</h2>

<img

src="<?php echo $image; ?>"

width="150"

height="150"

alt="Profile Image"

>

<br><br>

<h3>

<?php

echo $author['name'];

?>

</h3>

<p>

<?php

echo $author['bio'];

?>

</p>


<h3>

Social Links

</h3>

<p>

Twitter:

<a

href="<?php echo $socialLinks['twitter'] ?? '#'; ?>"

target="_blank"

>

<?php

echo $socialLinks['twitter']

??

'No Twitter';

?>

</a>

</p>


<p>

GitHub:

<a

href="<?php echo $socialLinks['github'] ?? '#'; ?>"

target="_blank"

>

<?php

echo $socialLinks['github']

??

'No Github';

?>

</a>

</p>


<h3>

Published Articles

</h3>

<?php

if(!empty($articles)){

foreach(

$articles as $article

){

?>

<p>

•

<?php

echo $article['title'];

?>

</p>

<?php

}

}else{

echo

"No published articles found";

}

?>

<br><br>

<a href="../../Public/index.php">

Home

</a>

</body>

</html>