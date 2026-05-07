<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $dbname = "portfolio";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $name = htmlspecialchars(strip_tags($_POST["name"]));
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(strip_tags($_POST["phone"]));
        $location = htmlspecialchars(strip_tags($_POST["location"]));
        $address = htmlspecialchars(strip_tags($_POST["address"]));
        $notes = htmlspecialchars(strip_tags($_POST["notes"]));
        $cart = isset($_POST["cart_data"]) ? htmlspecialchars($_POST["cart_data"]) : "No items";

        $sql = "INSERT INTO clients (name, email, phone, location, address, cart, notes) 
                VALUES (:name, :email, :phone, :location, :address, :cart, :notes)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':cart', $cart);
        $stmt->bindParam(':notes', $notes);

        if ($stmt->execute()) {

            header("Location: thank-you.html");
            exit();
        } else {
            header("Location: sorry.html");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: sorry.html");
        exit();
    }
} else {
    header("Location: catalog.html");
    exit();
}
?>