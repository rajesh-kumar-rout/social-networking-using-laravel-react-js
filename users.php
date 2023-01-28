<?php 

require("include/authenticate.php");

$query = $_GET["query"] ?? "";

$stmt = $db->prepare("
    SELECT
        id,
        CONCAT(firstName, ' ', lastName) AS fullName,
        profileImageUrl
    FROM 
        socialUsers
    WHERE 
        firstName LIKE CONCAT('%', :query, '%') OR
        lastName LIKE CONCAT('%', :query, '%') 
");

$stmt->bindParam(":query", $query);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
