<?php 

require("include/authenticate.php");

$userId = $_GET["userId"] ?? -1;

$errors = [];

$stmt = $db->prepare("
	SELECT 1
    FROM socialUsers
    WHERE id = :userId
    LIMIT 1
");

$stmt->bindParam(":userId", $userId);

$stmt->execute();

$user = $stmt->fetch();

if(!$user)
{
    $errors["userId"] = "Invalid user id";
}
else if ($userId === $currentUserId) 
{
    $errors["userId"] = "You can not follow yourself";
}

if(count($errors) > 0)
{
    http_response_code(422);

    echo json_encode($errors);

    die;
}

$stmt = $db->prepare("
	SELECT 1
    FROM socialFollowers
    WHERE followerId = :currentUserId AND followingId = :userId
    LIMIT 1
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->bindParam(":userId", $userId);

$stmt->execute();

$follower = $stmt->fetch(PDO::FETCH_ASSOC);

if($follower != null) 
{
    $stmt = $db->prepare("
        DELETE FROM socialFollowers
        WHERE followerId = :currentUserId AND followingId = :userId
    ");

    $stmt->bindParam(':currentUserId', $currentUserId);

    $stmt->bindParam(':userId', $userId);

    $stmt->execute();   
} 
else 
{
    $stmt = $db->prepare("
        INSERT INTO socialFollowers (followerId, followingId)
        VALUES (:currentUserId, :userId)
    ");

    $stmt->bindParam(':currentUserId', $currentUserId);

    $stmt->bindParam(':userId', $userId);

    $stmt->execute();
}


