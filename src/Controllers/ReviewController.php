<?php

namespace App\Controllers;

use App\Core\View;

class ReviewController
{
    public function index(): void
    {
        if (empty($_SESSION['docs']) || empty($_SESSION['index'])) {
            header('Location: /');
            exit;
        }

        View::render('review', [
            'docs' => $_SESSION['docs'],
            'totalAlunos' => count($_SESSION['index']),
        ]);
    }
}
