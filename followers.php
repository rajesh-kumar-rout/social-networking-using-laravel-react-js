<?php 

require("include/authenticate.php");

$userId = $_GET["userId"] ?? $currentUserId;

$stmt = $db->prepare("
	SELECT
        socialUsers.id,
        CONCAT(socialUsers.firstName, ' ', socialUsers.lastName) AS userName,
        socialUsers.profileImageUrl
    FROM
        socialFollowers
    INNER JOIN socialUsers ON socialUsers.id = socialFollowers.followerId
    WHERE socialFollowers.followingId = :userId
");

$stmt->bindParam(":userId", $userId);

$stmt->execute();

$followers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($followers);
