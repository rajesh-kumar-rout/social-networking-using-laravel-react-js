<?php 

require("include/authenticate.php");

$userId = $_GET['userId'] ?? $currentUserId;

$limit = $_GET['limit'] ?? -1;

$sql = "
    SELECT
        socialUsers.id,
        CONCAT(socialUsers.firstName, ' ', socialUsers.lastName) AS fullName,
        socialUsers.profileImageUrl
    FROM
        socialFollowers
    INNER JOIN socialUsers ON socialUsers.id = socialFollowers.followingId
    WHERE socialFollowers.followerId = :userId
";

if($limit != -1) 
{
    $sql += " LIMIT :limit";
}

$stmt = $db->prepare($sql);

$stmt->bindParam(":userId", $userId);

if($limit != -1) 
{
    $stmt->bindParam(":limit", $limit);
}

$stmt->execute();

$followings = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($followings);
