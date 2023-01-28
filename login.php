<?php 

require("include/db.php");

$email = $_POST["email"] ?? "";

$password = $_POST["password"] ?? "";

$stmt = $db->prepare("
	SELECT 
        id,
        password
    FROM 
        socialUsers
    WHERE email = :email
    LIMIT 1
");

$stmt->bindParam(":email", $email);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!($user && password_verify($password,  $user["password"]))) 
{
    http_response_code(422);

    echo json_encode(["error" => "Invalid email or password"]);

    die;
}

$token = bin2hex(random_bytes(32));

$stmt = $db->prepare("
	INSERT INTO socialTokens
    (
        token,
        userId
    )
    VALUES
    (
        :token,
        :userId
    )
");

$stmt->bindParam(":token", $token);

$stmt->bindParam(":userId", $user["id"]);

$stmt->execute();

echo json_encode(["token" => $token]);