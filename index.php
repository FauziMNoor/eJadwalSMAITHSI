<?php
// PHP fallback for SPA routing on Hostinger
// This file handles all routes and serves index.html for React Router

$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove query string and fragments
$path = strtok($path, '?');

// Define routes that should serve index.html
$spa_routes = [
    '/external/',
    '/dashboard',
    '/login',
    '/register',
    '/schedules',
    '/classes',
    '/teachers',
    '/subjects',
    '/events',
    '/settings',
    '/profile'
];

// Check if the request is for a SPA route
$is_spa_route = false;
foreach ($spa_routes as $route) {
    if (strpos($path, $route) === 0) {
        $is_spa_route = true;
        break;
    }
}

// Check if file exists (for static assets)
$file_path = __DIR__ . $path;
if (file_exists($file_path) && !is_dir($file_path)) {
    // Serve the actual file
    return false;
}

// For SPA routes or non-existent files, serve index.html
if ($is_spa_route || !file_exists($file_path)) {
    // Set proper content type
    header('Content-Type: text/html; charset=UTF-8');
    
    // Serve index.html
    $index_file = __DIR__ . '/index.html';
    if (file_exists($index_file)) {
        readfile($index_file);
    } else {
        http_response_code(404);
        echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>404 Not Found</h1><p>The requested resource was not found.</p></body></html>";
    }
    exit;
}

// If we get here, let Apache handle it normally
return false;
?>
