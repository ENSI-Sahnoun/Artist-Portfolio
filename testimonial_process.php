<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/notify.php';

if (($_SERVER["REQUEST_METHOD"] ?? "") !== "POST") {
    header("Location: testimonials.php");
    exit();
}

if (!csrf_validate()) {
    header("Location: testimonials.php?error=1");
    exit();
}

// Rate limiting: max 3 testimonials per IP per hour
$ip_key = 'test_' . md5($_SERVER['REMOTE_ADDR']);
if (!isset($_SESSION[$ip_key])) {
    $_SESSION[$ip_key] = ['count' => 0, 'reset' => time() + 3600];
}
if (time() > $_SESSION[$ip_key]['reset']) {
    $_SESSION[$ip_key] = ['count' => 0, 'reset' => time() + 3600];
}
if ($_SESSION[$ip_key]['count'] >= 3) {
    header("Location: testimonials.php?error=1");
    exit();
}
$_SESSION[$ip_key]['count']++;

$name        = trim($_POST["name"] ?? "");
$email       = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
$location    = trim($_POST["location"] ?? "");
$artworkType = trim($_POST["artwork_type"] ?? "");
$rating      = (int) ($_POST["rating"] ?? 0);
$review      = trim($_POST["review"] ?? "");
$medium      = trim($_POST["medium"] ?? "");

$visitationValues = $_POST["visitation"] ?? [];
if (!is_array($visitationValues)) {
    $visitationValues = [$visitationValues];
}
$visitation = array_sum(array_map("intval", $visitationValues));

if ($name === "" || !$email || $rating <= 0 || $rating > 5 || $review === "") {
    header("Location: testimonials.php?error=1");
    exit();
}

try {
    $conn = get_db();
    $sql  = "INSERT INTO testimonials (name, email, location, artwork_type, rating, review, visitation, medium)
             VALUES (:name, :email, :location, :artwork_type, :rating, :review, :visitation, :medium)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":name"         => $name,
        ":email"        => $email,
        ":location"     => $location,
        ":artwork_type" => $artworkType,
        ":rating"       => $rating,
        ":review"       => $review,
        ":visitation"   => $visitation,
        ":medium"       => $medium,
    ]);
    $e = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    send_notification(
        'New Testimonial — ' . $e($name),
        "<h2>New Testimonial Submitted</h2>
        <p><strong>Name:</strong> {$e($name)}</p>
        <p><strong>Email:</strong> {$e($email)}</p>
        <p><strong>Rating:</strong> {$e($rating)} / 5</p>
        <p><strong>Review:</strong> {$e($review)}</p>
        <p><strong>Location:</strong> {$e($location)}</p>
        <p><strong>Artwork Type:</strong> {$e($artworkType)}</p>
        <p><strong>Medium:</strong> {$e($medium)}</p>"
    );
    header("Location: testimonials.php?success=1");
    exit();
} catch (PDOException $e) {
    error_log("testimonial_process PDO error: " . $e->getMessage());
    header("Location: testimonials.php?error=1");
    exit();
}
