<?php
session_start();
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

// Simple routing
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = rtrim($path, '/');

// Remove base path if exists
$basePath = '/yereltanitim';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Route handling
switch ($path) {
    case '':
    case '/':
        include 'pages/home.php';
        break;
    case '/sehirler':
        include 'pages/cities.php';
        break;
    case '/blog':
        include 'pages/blog.php';
        break;
    case '/iletisim':
        include 'pages/contact.php';
        break;
    case '/admin':
        include 'admin/index.php';
        break;
    default:
        // Check for city pages
        if (preg_match('/^\/sehir\/([a-z0-9-]+)$/', $path, $matches)) {
            $citySlug = $matches[1];
            include 'pages/city.php';
        }
        // Check for district pages
        elseif (preg_match('/^\/sehir\/([a-z0-9-]+)\/([a-z0-9-]+)$/', $path, $matches)) {
            $citySlug = $matches[1];
            $districtSlug = $matches[2];
            include 'pages/district.php';
        }
        // Check for blog post pages
        elseif (preg_match('/^\/blog\/([a-z0-9-]+)$/', $path, $matches)) {
            $postSlug = $matches[1];
            include 'pages/blog-post.php';
        }
        // Check for admin pages
        elseif (strpos($path, '/admin/') === 0) {
            include 'admin/index.php';
        }
        else {
            include 'pages/404.php';
        }
        break;
}
?>