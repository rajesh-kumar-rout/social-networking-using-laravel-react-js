<?php 

require("include/db.php");

$token = $_GET["token"] ?? "";

$stmt = $db->prepare("
	SELECT
        socialUsers.id,
        socialUsers.firstName,
        socialUsers.lastName,
        CONCAT(socialUsers.firstName, ' ', socialUsers.lastName) AS fullName,
        socialUsers.email,
        socialUsers.bio,
        socialUsers.birthDate,
        socialUsers.work,
        socialUsers.currentCity,
        socialUsers.homeTown,
        socialUsers.school,
        socialUsers.college,
        socialUsers.gender,
        socialUsers.relationship,
        socialUsers.profileImageUrl,
        socialUsers.coverImageUrl,
        socialUsers.createdAt,
        socialUsers.updatedAt
    FROM
        socialTokens
    INNER JOIN socialUsers ON socialUsers.id = socialTokens.userId
    WHERE socialTokens.token = :token
    LIMIT 1
");

$stmt->bindParam(":token", $token);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($user);
