<?php 

require("include/authenticate.php");

$postId = $_GET["postId"] ?? -1;

$stmt = $db->prepare("
	SELECT imageUrl
    FROM socialPosts
    WHERE id = :postId AND userId = :currentUserId
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->bindParam(":postId", $postId);

$stmt->execute();

$post = $stmt->fetch();

if(!$post)
{
    http_response_code(404);

    echo json_encode(["error" => "Post not found"]);

    die;
}

if($post["imageUrl"])
{
    $previousImage = str_replace($baseUrl . "/", "", $post["imageUrl"]);

    unlink($previousImage);
}

$stmt = $db->prepare("
	DELETE FROM socialPosts
    WHERE id = :postId AND userId = :currentUserId
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->bindParam(":postId", $postId);

$stmt->execute();

