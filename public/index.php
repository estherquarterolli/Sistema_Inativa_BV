<?php

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$staticPath = realpath(__DIR__ . $requestPath);

if (PHP_SAPI === 'cli-server'
    && $staticPath !== false
    && str_starts_with($staticPath, __DIR__ . DIRECTORY_SEPARATOR)
    && is_file($staticPath)) {
    return false;
}

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\DownloadController;
use App\Controllers\UploadController;
use App\Core\Router;

$upload = new UploadController();
$download = new DownloadController();

$router = new Router();

$router->get('/', fn () => $upload->home());
$router->post('/processar', fn () => $upload->processar());
$router->get('/baixar', fn () => $download->baixar($_GET['token'] ?? ''));

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
