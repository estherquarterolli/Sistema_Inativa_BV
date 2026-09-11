<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View nao encontrada: {$view}");
        }

        require $viewPath;
    }
}
