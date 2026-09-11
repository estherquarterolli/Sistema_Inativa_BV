<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\DownloadController;
use App\Controllers\GenerateController;
use App\Controllers\ReviewController;
use App\Controllers\UploadController;
use App\Core\Router;

session_start();

$upload = new UploadController();
$review = new ReviewController();
$generate = new GenerateController();
$download = new DownloadController();

$router = new Router();

$router->get('/', fn () => $upload->home());
$router->post('/processar', fn () => $upload->processar());
$router->get('/revisar', fn () => $review->index());
$router->post('/gerar', fn () => $generate->gerar());
$router->get('/baixar', fn () => $download->baixar($_GET['token'] ?? ''));

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
