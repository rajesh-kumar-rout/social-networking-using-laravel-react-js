<?php

require("include/authenticate.php");

$userId = $_GET['userId'] ?? $currentUserId;

$stmt = $db->prepare("
	SELECT 
    	socialUsers.id AS userId,
        CONCAT(socialUsers.firstName, ' ', socialUsers.lastName) AS userName,
        socialUsers.profileImageUrl,
        socialPosts.id,
        socialPosts.description,
        socialPosts.imageUrl,
        socialPosts.videoUrl,
        socialPosts.createdAt,

        EXISTS
        (
        	SELECT 1
            FROM socialLikes
            WHERE socialLikes.userId = :userId AND socialLikes.postId = socialPosts.id
            LIMIT 1
        ) AS isLiked,

        (
        	SELECT COUNT(*)
            FROM socialLikes
            WHERE socialLikes.postId = socialPosts.id
        ) AS totalLikes,

        (
        	SELECT COUNT(*)
            FROM socialComments
            WHERE socialComments.postId = socialPosts.id
        ) AS totalComments,

        1 AS isPosted
    FROM 
    	socialUsers
    INNER JOIN socialPosts ON socialPosts.userId = socialUsers.id
    WHERE socialUsers.id = :userId
    ORDER BY socialPosts.id DESC
");

$stmt->bindParam(":userId", $userId);

$stmt->execute();

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($posts);
