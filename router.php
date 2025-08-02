<?php
// SPA Router for Hostinger - handles React Router routes
// This file is called by .htaccess for SPA routing

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Input validation and sanitization
$request_uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove query string and fragments
$path = strtok($path, '?');

// Validate path to prevent directory traversal
if (strpos($path, '..') !== false || strpos($path, '\\') !== false) {
    http_response_code(400);
    exit('Invalid path');
}

// Define allowed SPA routes (whitelist approach for security)
$allowed_spa_routes = [
    '/external',
    '/dashboard',
    '/login',
    '/register',
    '/schedules',
    '/classes',
    '/teachers',
    '/subjects',
    '/events',
    '/settings',
    '/profile',
    '/public'
];

// Additional security: block access to sensitive paths
$blocked_paths = [
    '/admin',
    '/config',
    '/database',
    '/api',
    '/.env',
    '/wp-admin',
    '/phpmyadmin'
];

// Check for blocked paths
foreach ($blocked_paths as $blocked) {
    if (strpos($path, $blocked) === 0) {
        http_response_code(403);
        exit('Access denied');
    }
}

// Check if the request is for an allowed SPA route
$is_spa_route = false;
foreach ($allowed_spa_routes as $route) {
    if (strpos($path, $route) === 0) {
        $is_spa_route = true;
        break;
    }
}

// Check if file exists (for static assets)
$file_path = __DIR__ . $path;
$file_exists = file_exists($file_path) && !is_dir($file_path);

// If it's a static file that exists, serve it
if ($file_exists && !$is_spa_route) {
    // Let Apache handle static files
    return false;
}

// For SPA routes or non-existent files, serve index.html
if ($is_spa_route || !$file_exists) {
    // Set proper headers
    header('Content-Type: text/html; charset=UTF-8');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Serve index.html
    $index_file = __DIR__ . '/index.html';
    if (file_exists($index_file)) {
        readfile($index_file);
    } else {
        http_response_code(404);
        echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>404 Not Found</h1><p>React app not found.</p></body></html>";
    }
    exit;
}

// If we get here, let Apache handle it normally
return false;
?>
