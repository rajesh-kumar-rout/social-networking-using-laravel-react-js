<?php 

require("include/authenticate.php");

$stmt = $db->prepare("
	DELETE FROM socialUsers
    WHERE id = :currentUserId
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->execute();

