<?php

require("include/authenticate.php");

$userId = $_GET["userId"] ?? $cuurentUserId;

$limit = $_GET["limit"] ?? -1;

$sql = "
    SELECT 
        socialPosts.imageUrl
    FROM 
        socialPosts
    WHERE socialPosts.userId = :userId AND socialPosts.imageUrl IS NOT NULL
";

if($limit != -1) 
{
    $sql = $sql . " LIMIT :limit";
}

$stmt = $db->prepare($sql);

$stmt->bindParam(":userId", $userId);

if($limit != -1) 
{
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
}

$stmt->execute();

$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($photos);
