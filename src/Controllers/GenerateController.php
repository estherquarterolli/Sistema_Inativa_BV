<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\MatchingService;
use App\Services\OutputBuilder;

class GenerateController
{
    public function gerar(): void
    {
        if (empty($_SESSION['index'])) {
            header('Location: /');
            exit;
        }

        $index = $_SESSION['index'];
        $allNames = [];

        foreach ($_POST['docs'] ?? [] as $texto) {
            $lines = preg_split('/\r\n|\r|\n/', (string) $texto) ?: [];
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line !== '') {
                    $allNames[] = $line;
                }
            }
        }

        $matcher = new MatchingService();
        $resultado = $matcher->match($allNames, $index);

        $token = bin2hex(random_bytes(8));
        $outputPath = __DIR__ . '/../../storage/output/' . $token . '.xlsx';

        $builder = new OutputBuilder();
        $builder->build($resultado['matched'], $resultado['unmatched'], $outputPath);

        unset($_SESSION['index'], $_SESSION['docs']);

        View::render('resultado', [
            'total' => count($resultado['matched']) + count($resultado['unmatched']),
            'encontrados' => count($resultado['matched']),
            'naoEncontrados' => $resultado['unmatched'],
            'token' => $token,
        ]);
    }
}
