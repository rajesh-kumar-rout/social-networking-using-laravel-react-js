<?php 

require("include/authenticate.php");

$postId = $_GET["postId"] ?? -1;

$stmt = $db->prepare("
	SELECT
        socialUsers.id AS userId,
        CONCAT(socialUsers.firstName, ' ', socialUsers.lastName) AS userName,
        socialUsers.profileImageUrl,
        socialComments.id,
        socialComments.comment,
        socialComments.createdAt,
        IF(socialUsers.id = :currentUserId, 1, 0) AS isCommented
    FROM
        socialComments
    INNER JOIN socialUsers ON socialUsers.id = socialComments.userId
    WHERE socialComments.postId = :postId
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->bindParam(":postId", $postId);

$stmt->execute();

$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($comments);
