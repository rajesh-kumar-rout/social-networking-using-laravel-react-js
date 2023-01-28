<?php 

require("include/authenticate.php");

$postId = $_GET["postId"] ?? -1;

$errors = [];

$stmt = $db->prepare("
	SELECT 1
    FROM socialPosts
    WHERE id = :postId
    LIMIT 1
");

$stmt->bindParam(":postId", $postId);

$stmt->execute();

$post = $stmt->fetch();

if(!$post)
{
    $errors["postId"] = "Invalid post id";
}

if(count($errors) > 0)
{
    http_response_code(422);

    echo json_encode($errors);

    die;
}

$stmt = $db->prepare("
	SELECT 1
    FROM socialLikes
    WHERE userId = :currentUserId AND postId = :postId
    LIMIT 1
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->bindParam(":postId", $postId);

$stmt->execute();

$like = $stmt->fetch(PDO::FETCH_ASSOC);

if($like != null)
{
    $stmt = $db->prepare("
        DELETE FROM socialLikes
        WHERE userId = :currentUserId AND postId = :postId
    ");

    $stmt->bindParam(':currentUserId', $currentUserId);

    $stmt->bindParam(':postId', $postId);

    $stmt->execute();   
} 
else 
{
    $stmt = $db->prepare("
        INSERT INTO socialLikes (postId, userId)
        VALUES (:postId, :currentUserId)
    ");

    $stmt->bindParam(':currentUserId', $currentUserId);

    $stmt->bindParam(':postId', $postId);

    $stmt->execute();
}

