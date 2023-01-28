<?php 

require("include/authenticate.php");

$comment = trim($_POST["comment"] ?? "");

$postId = $_GET["postId"] ?? -1;

$errors = [];

if(strlen($comment) == 0)
{
    $errors["comment"] = "Comment is required";
}
else if(strlen($comment) > 255)
{
    $errors["comment"] = "Comment must be within 255 characters";
}

$stmt = $db->prepare("
	SELECT 1 
    FROM socialPosts
    WHERE id = :postId
    LIMIT 1
");

$stmt->bindParam(":postId", $postId);

$stmt->execute();

$isPostExists = $stmt->fetch();

if(!$isPostExists)
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
	INSERT INTO socialComments
    (
        comment,
        userId,
        postId
    )
    VALUES
    (
        :comment,
        :currentUserId,
        :postId
    )
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->bindParam(":postId", $postId);

$stmt->bindParam(":comment", $comment);

$stmt->execute();
