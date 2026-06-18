<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path === '/' || $path === '') {
    require __DIR__ . '/index.html';
    exit();
}

if (is_file($file)) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    if ($ext === 'php') {
        require $file;
    } else {
        return false; // let built-in server serve static file
    }
} elseif (is_file($file . '.php')) {
    require $file . '.php';
} elseif (is_file($file . '/index.html')) {
    require $file . '/index.html';
} else {
    http_response_code(404);
    require __DIR__ . '/404.php';
}
