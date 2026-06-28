<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/notify.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: catalog.php");
    exit();
}

if (!csrf_validate()) {
    header("Location: sorry.html");
    exit();
}

// Rate limiting: max 5 submissions per IP per hour
$ip_key = 'buy_' . md5($_SERVER['REMOTE_ADDR']);
if (!isset($_SESSION[$ip_key])) {
    $_SESSION[$ip_key] = ['count' => 0, 'reset' => time() + 3600];
}
if (time() > $_SESSION[$ip_key]['reset']) {
    $_SESSION[$ip_key] = ['count' => 0, 'reset' => time() + 3600];
}
if ($_SESSION[$ip_key]['count'] >= 5) {
    header("Location: sorry.html");
    exit();
}
$_SESSION[$ip_key]['count']++;

// Validate & sanitize — strip tags only for security, htmlspecialchars on OUTPUT not here
$name     = trim($_POST["name"] ?? "");
$email    = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
$phone    = trim($_POST["phone"] ?? "");
$location = trim($_POST["location"] ?? "");
$address  = trim($_POST["address"] ?? "");
$notes    = trim($_POST["notes"] ?? "");
$cart     = isset($_POST["cart_data"]) ? trim($_POST["cart_data"]) : "No items";

if (!$name || !$email || !$phone || !$address) {
    header("Location: sorry.html");
    exit();
}

// Validate cart is valid JSON if provided
if ($cart !== "No items" && json_decode($cart) === null) {
    $cart = "Invalid cart data";
}

try {
    $conn = get_db();
    $sql  = "INSERT INTO clients (name, email, phone, location, address, cart, notes)
             VALUES (:name, :email, :phone, :location, :address, :cart, :notes)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name'     => $name,
        ':email'    => $email,
        ':phone'    => $phone,
        ':location' => $location,
        ':address'  => $address,
        ':cart'     => $cart,
        ':notes'    => $notes,
    ]);
    $e = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

    // Build cart HTML with images
    $base_url   = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    $cart_items = json_decode($cart, true);
    $cart_html  = '';
    if (is_array($cart_items)) {
        foreach ($cart_items as $item) {
            $img   = $e($base_url . '/' . ltrim($item['image'] ?? '', '/'));
            $iname = $e($item['name'] ?? '');
            $cart_html .= "
            <div style='display:flex;align-items:center;gap:12px;margin-bottom:12px;'>
                <img src='{$img}' alt='{$iname}' style='width:80px;height:80px;object-fit:cover;border-radius:6px;'>
                <div>
                    <strong>{$iname}</strong>
                </div>
            </div>";
        }
    } else {
        $cart_html = $e($cart);
    }

    send_notification(
        'New Purchase Request — ' . $e($name),
        "<h2>New Purchase Request</h2>
        <p><strong>Name:</strong> {$e($name)}</p>
        <p><strong>Email:</strong> {$e($email)}</p>
        <p><strong>Phone:</strong> {$e($phone)}</p>
        <p><strong>Location:</strong> {$e($location)}</p>
        <p><strong>Address:</strong> {$e($address)}</p>
        <p><strong>Notes:</strong> {$e($notes)}</p>
        <h3>Cart</h3>{$cart_html}"
    );
    header("Location: thank-you.html");
    exit();
} catch (PDOException $e) {
    error_log("buying_process PDO error: " . $e->getMessage());
    header("Location: sorry.html");
    exit();
}
