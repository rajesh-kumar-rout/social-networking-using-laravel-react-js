<?php

require("include/authenticate.php");

$userId = $_GET['userId'] ?? $currentUserId;

$stmt = $db->prepare("
	SELECT 
    	socialUsers.id,
        socialUsers.firstName,
        socialUsers.lastName,
        CONCAT(socialUsers.firstName, ' ', socialUsers.lastName) AS fullName,
        socialUsers.bio,
        socialUsers.birthDate,
        socialUsers.profileImageUrl,
        socialUsers.coverImageUrl,
        socialUsers.work,
        socialUsers.currentCity,
        socialUsers.homeTown,
        socialUsers.school,
        socialUsers.college,
        socialUsers.gender,
        socialUsers.relationship,
        socialUsers.createdAt,
        socialUsers.updatedAt,

        EXISTS
      	(
        	SELECT COUNT(*)
            FROM socialPosts
            WHERE socialPosts.userId = :userId
        ) AS totalPosts,

        (
        	SELECT COUNT(*)
            FROM socialFollowers
            WHERE socialFollowers.followerId = :userId
        ) AS totalFollowings,

      	(
        	SELECT COUNT(*)
            FROM socialFollowers
            WHERE socialFollowers.followingId = :userId
        ) AS totalFollowers
    FROM 
    	socialUsers
    WHERE socialUsers.id = :userId
    LIMIT 1
");

$stmt->bindParam(":userId", $userId);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($user);
