<?php 

require("db.php");

$token = $_GET["token"] ?? "";

$stmt = $db->prepare("
	SELECT
        userId
    FROM
        socialTokens
    WHERE token = :token
");

$stmt->bindParam(":token", $token);

$stmt->execute();

$tokenRow = $stmt->fetch(PDO::FETCH_ASSOC);

if($tokenRow == null) 
{
    http_response_code(401);

    echo json_encode(["error" => "Unauthenticated"]);

    die;
}

$currentUserId = $tokenRow["userId"];