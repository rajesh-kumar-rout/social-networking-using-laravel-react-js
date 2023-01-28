<?php 

require("include/authenticate.php");

$token = $_GET["token"] ?? "";

$stmt = $db->prepare("
	DELETE FROM socialTokens
    WHERE token = :token
");

$stmt->bindParam(":token", $token);

$stmt->execute();

