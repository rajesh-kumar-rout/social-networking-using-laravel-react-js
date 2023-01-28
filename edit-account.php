<?php 

require("include/authenticate.php");

$firstName = trim($_POST["firstName"] ?? "");

$lastName = trim($_POST["lastName"] ?? "");

$email = trim($_POST["email"] ?? "");

$password = $_POST["password"] ?? "";

$bio = trim($_POST["bio"] ?? "");

$birthDate = trim($_POST["birthDate"] ?? "");

$work = trim($_POST["work"] ?? "");

$currentCity = trim($_POST["currentCity"] ?? "");

$homeTown = trim($_POST["homeTown"] ?? "");

$school = trim($_POST["school"] ?? "");

$college = trim($_POST["college"] ?? "");

$gender = trim($_POST["gender"] ?? "");

$relationship = trim($_POST["relationship"] ?? "");

$profileImage = $_FILES["profileImage"] ?? [];

$coverImage = $_FILES["coverImage"] ?? [];

$errors = [];

if(strlen($firstName) == 0 || strlen($firstName) > 30)
{
    $errors["firstName"] = "First name must be within 1-30 characters";
}

if(strlen($lastName) == 0 || strlen($lastName) > 30)
{
    $errors["lastName"] = "Last name must be within 1-30 characters";
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    $errors["email"] = "Invalid email";
}
else if (strlen($email) > 30) 
{
    $errors["email"] = "Email must be within 30 characters";
}
else 
{
    $stmt = $db->prepare("
        SELECT 1
        FROM socialUsers
        WHERE email = :email AND id != :currentUserId
        LIMIT 1
    ");

    $stmt->bindParam(":email", $email);

    $stmt->bindParam(":currentUserId", $currentUserId);

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user != null) 
    {
        $errors["email"] = "Email already taken";
    }
}

if(strlen(trim($password)) == 0)
{
    $errors["password"] = "Password must not contain only empty space";
}
else if (strlen($password) < 6 || strlen($password) > 20) 
{
    $errors["password"] = "Password must be within 6-20 characters";
}

if(strlen($bio) == 0 || strlen($bio) > 255)
{
    $errors["bio"] = "Bio must be within 1-255 characters";
}

if (strlen($work) > 30) 
{
    $errors["work"] = "Work must be within 30 characters";
}

if (strlen($currentCity) > 30) 
{
    $errors["currentCity"] = "Current city must be within 30 characters";
}

if (strlen($homeTown) > 30) 
{
    $errors["homeTown"] = "Home town must be within 30 characters";
}

if (strlen($school) > 30) 
{
    $errors["school"] = "School must be within 30 characters";
}

if (strlen($college) > 30) 
{
    $errors["college"] = "College must be within 30 characters";
}

if(strlen($relationship) == 0)
{
    $errors["relationship"] = "Relationship is required";
}
else if (!in_array($relationship, ["Single", "In a relationship", "Married"])) 
{
    $errors["relationship"] = "Invalid relationship";
}

if(strlen($gender) == 0)
{
    $errors["gender"] = "Gender is required";
}
else if (!in_array($gender, ["Male", "Female"])) 
{
    $errors["gender"] = "Invalid gender";
}

if (count($profileImage) > 0) 
{
    if(!in_array($profileImage["type"], ["image/jpeg", "image/png", "image/jpg"]))
    {
        $errors["profileImage"] = "Profile image must be of type jpeg, jpg and png";
    }
    else if($profileImage["size"] > 2097152)
    {
        $errors["profileImage"] = "Profile image must be within 2 MB";
    }
}

if (count($coverImage) > 0) 
{
    if(!in_array($coverImage["type"], ["image/jpeg", "image/png", "image/jpg"]))
    {
        $errors["coverImage"] = "Cover image must be of type jpeg, jpg and png";
    }
    else if($coverImage["size"] > 2097152)
    {
        $errors["coverImage"] = "Cover image must be within 2 MB";
    }
}

if(count($errors) > 0)
{
    http_response_code(422);

    echo json_encode($errors);

    die;
}

$stmt = $db->prepare("
	SELECT
        profileImageUrl,
        coverImageUrl
    FROM
        socialUsers
    WHERE id = :currentUserId
");

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(count($profileImage) > 0) 
{
    $destination = "uploads/" . bin2hex(random_bytes(12)) . "." . pathinfo($profileImage["name"], PATHINFO_EXTENSION);

    move_uploaded_file($profileImage["tmp_name"], $destination);

    $previousProfileImage = str_replace($baseUrl . "/", "", $user["profileImageUrl"]);

    unlink($previousProfileImage);

    $user["profileImageUrl"] = $baseUrl . "/" . $destination;
} 

if(count($coverImage) > 0) 
{
    $destination = "uploads/" . bin2hex(random_bytes(12)) . "." . pathinfo($coverImage["name"], PATHINFO_EXTENSION);

    move_uploaded_file($coverImage["tmp_name"], $destination);

    $previousCoverImage = str_replace($baseUrl . "/", "", $user["coverImageUrl"]);

    unlink($previousCoverImage);

    $user["coverImageUrl"] = $baseUrl . "/" . $destination;
} 

$stmt = $db->prepare("
    UPDATE socialUsers
    SET
        firstName = :firstName,
        lastName = :lastName,
        email = :email,
        bio = :bio,
        birthDate = :birthDate,
        work = :work,
        currentCity = :currentCity,
        homeTown = :homeTown,
        school = :school,
        college = :college,
        gender = :gender,
        relationship = :relationship,
        profileImageUrl = :profileImageUrl,
        coverImageUrl = :coverImageUrl
    WHERE id = :currentUserId
");

$stmt->bindParam(":firstName", $firstName);

$stmt->bindParam(":lastName", $lastName);

$stmt->bindParam(":email", $email);

$stmt->bindParam(":bio", $bio);

$stmt->bindParam(":birthDate", $birthDate);

$stmt->bindParam(":work", $work);

$stmt->bindParam(":currentCity", $currentCity);

$stmt->bindParam(":homeTown", $homeTown);

$stmt->bindParam(":school", $school);

$stmt->bindParam(":college", $college);

$stmt->bindParam(":gender", $gender);

$stmt->bindParam(":relationship", $relationship);

$stmt->bindParam(":profileImageUrl", $user["profileImageUrl"]);

$stmt->bindParam(":coverImageUrl", $user["coverImageUrl"]);

$stmt->bindParam(":currentUserId", $currentUserId);

$stmt->execute();