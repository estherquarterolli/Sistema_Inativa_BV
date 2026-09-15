<?php

namespace App\Controllers;

use App\Core\Storage;

class DownloadController
{
    public function baixar(string $token): void
    {
        $token = preg_replace('/[^a-f0-9]/', '', $token) ?? '';
        $path = Storage::outputPath($token);

        if ($token === '' || !is_file($path)) {
            http_response_code(404);
            echo 'Arquivo não encontrado ou expirado.';
            return;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="inativacao.xlsx"');
        header('Content-Length: ' . filesize($path));
        readfile($path);

        @unlink($path);
    }
}
