<?php 

require("include/authenticate.php");

$stmt = $db->prepare("
    SELECT
        id,
        CONCAT(firstName, ' ', lastName) AS fullName,
        profileImageUrl
    FROM 
        socialUsers
    WHERE id != :currentUserId
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
