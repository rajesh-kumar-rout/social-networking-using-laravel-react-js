<?php 

require("include/authenticate.php");

$oldPassword = $_POST["oldPassword"] ?? "";

$newPassword = $_POST["newPassword"] ?? "";

$errors = [];

if(strlen(trim($newPassword)) == 0)
{
    $errors["newPassword"] = "New password should not be only empty string";
}
else if(strlen($newPassword) < 6)
{
    $errors["newPassword"] = "New password must be at least 6 characters";
}
else if(strlen($newPassword) > 6)
{
    $errors["newPassword"] = "New password must be within 20 characters";
}

if(count($errors) > 0)
{
    http_response_code(422);

    echo json_encode($errors);

    die;
}

$stmt = $db->prepare("
	SELECT password
    FROM socialUsers
    WHERE id = :currentUserId
    LIMIT 1
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!password_verify($oldPassword, $user["password"])) 
{
    http_response_code(422);

    echo json_encode(["error" => "Old password does not match"]);

    die;
}

$newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $db->prepare("
	UPDATE socialUsers
    SET password = :password
    WHERE id = :currentUserId
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->bindParam(":password", $newPassword);

$stmt->execute();
