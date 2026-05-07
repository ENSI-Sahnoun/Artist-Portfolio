<?php
if (($_SERVER["REQUEST_METHOD"] ?? "") !== "POST") {
    header("Location: testimonials.html");
    exit();
}

$host = "localhost";
$dbname = "portfolio";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $artworkType = trim($_POST["artwork_type"] ?? "");
    $rating = (int) ($_POST["rating"] ?? 0);
    $review = trim($_POST["review"] ?? "");
    $medium = trim($_POST["medium"] ?? "");

    $visitationValues = $_POST["visitation"] ?? [];
    if (!is_array($visitationValues)) {
        $visitationValues = [$visitationValues];
    }
    $visitation = array_sum(array_map("intval", $visitationValues));

    if ($name === "" || $email === "" || $rating <= 0 || $review === "") {
        header("Location: testimonials.html?error=1");
        exit();
    }

    $sql = "INSERT INTO testimonials (name, email, location, artwork_type, rating, review, visitation, medium)
            VALUES (:name, :email, :location, :artwork_type, :rating, :review, :visitation, :medium)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":location", $location);
    $stmt->bindParam(":artwork_type", $artworkType);
    $stmt->bindParam(":rating", $rating, PDO::PARAM_INT);
    $stmt->bindParam(":review", $review);
    $stmt->bindParam(":visitation", $visitation, PDO::PARAM_INT);
    $stmt->bindParam(":medium", $medium);

    if ($stmt->execute()) {
        header("Location: testimonials.html?success=1");
        exit();
    }

    header("Location: testimonials.html?error=1");
    exit();
} catch (PDOException $e) {
    header("Location: testimonials.html?error=1");
    exit();
}
