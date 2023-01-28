<?php 

require("include/authenticate.php");

$image = $_FILES["image"] ?? [];

$description = trim($_POST["description"] ?? "");

$videoUrl = trim($_POST["videoUrl"] ?? "");

$errors = [];

if(count($image) == 0 && strlen($description) == 0 && strlen($videoUrl) == 0)
{
    $errors["error"] = "Either image, description or videoUrl required";
}

if(strlen($description) > 255) 
{
    $errors["description"] = "Description must be within 255 characters";
}

if($videoUrl)
{
    if(str_starts_with($videoUrl, "https://youtu.be/"))
    {
        $videoUrl = "https://www.youtube.com/embed/" . explode("be/", $videoUrl)[1];
    }
    else 
    {
        $errors["videoUrl"] = "Only youtube videos are supported";
    }
}

if(count($errors) > 0)
{
    http_response_code(422);

    echo json_encode($errors);

    die;
}

if(count($image) > 0) 
{
    $destination = "uploads/" . bin2hex(random_bytes(12)) . "." . pathinfo($image["name"], PATHINFO_EXTENSION);

    move_uploaded_file($image["tmp_name"], $destination);

    $image = $baseUrl . "/" . $destination;
}

$stmt = $db->prepare("
	INSERT INTO socialPosts
    (
        description,
        imageUrl,
        videoUrl,
        userId
    )
    VALUES
    (
        :description,
        :imageUrl,
        :videoUrl,
        :currentUserId
    )
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->bindParam(":description", $description);

$stmt->bindParam(":imageUrl", $image);

$stmt->bindParam(":videoUrl", $videoUrl);

$stmt->execute();
