<?php 

require("include/authenticate.php");

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
            WHERE socialLikes.userId = :currentUserId AND socialLikes.postId = socialPosts.id
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

        0 AS isPosted
    FROM 
    	socialFollowers
    INNER JOIN socialUsers ON socialUsers.id = socialFollowers.followingId
    INNER JOIN socialPosts ON socialPosts.userId = socialUsers.id
    WHERE socialFollowers.followerId = :currentUserId
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->execute();

$feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($feeds);