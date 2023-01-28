<?php 

require("include/authenticate.php");

$commentId = $_GET["commentId"] ?? -1;

$stmt = $db->prepare("
	DELETE FROM socialComments
    WHERE id = :commentId AND userId = :currentUserId
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->bindParam(":commentId", $commentId);

$stmt->execute();

