<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if (is_file($file)) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        require $file;
    } else {
        return false; // serve static file as-is
    }
} else {
    // try with .php extension
    if (is_file($file . '.php')) {
        require $file . '.php';
    } elseif (is_file($file . '/index.php')) {
        require $file . '/index.php';
    } else {
        http_response_code(404);
        echo '404 Not Found';
    }
}
