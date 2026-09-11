<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\Extractors\ExtractorFactory;
use App\Services\SpreadsheetReader;

class UploadController
{
    private const MAX_DOCS = 10;

    public function home(): void
    {
        View::render('home');
    }

    public function processar(): void
    {
        try {
            $this->validate();

            $mainFile = $this->moveUploaded('planilha_principal');
            $reader = new SpreadsheetReader();
            $index = $reader->buildIndex($mainFile);
            @unlink($mainFile);

            if (empty($index)) {
                $this->fail('Não foi possível ler nomes e IDs da planilha principal. Verifique se ela tem colunas de nome e de ID.');
                return;
            }

            $docs = [];
            $files = $_FILES['documentos'];
            $count = count($files['name']);

            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                    continue;
                }

                $tmpPath = $this->moveUploadedIndexed($files, $i);

                try {
                    $extractor = ExtractorFactory::make($tmpPath);
                    $docs[$files['name'][$i]] = $extractor->extract($tmpPath);
                } catch (\Throwable $e) {
                    $docs[$files['name'][$i]] = [];
                } finally {
                    @unlink($tmpPath);
                }
            }

            $_SESSION['index'] = $index;
            $_SESSION['docs'] = $docs;

            header('Location: /revisar');
            exit;
        } catch (\Throwable $e) {
            $this->fail('Erro ao processar arquivos: ' . $e->getMessage());
        }
    }

    private function validate(): void
    {
        if (!isset($_FILES['planilha_principal']) || $_FILES['planilha_principal']['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Envie a planilha principal de alunos.');
        }

        if (!isset($_FILES['documentos']) || count($_FILES['documentos']['name']) === 0) {
            throw new \RuntimeException('Envie ao menos um documento.');
        }

        if (count($_FILES['documentos']['name']) > self::MAX_DOCS) {
            throw new \RuntimeException('Envie no máximo ' . self::MAX_DOCS . ' documentos por vez.');
        }
    }

    private function moveUploaded(string $field): string
    {
        $dest = __DIR__ . '/../../storage/uploads/' . uniqid('main_', true) . '_' . basename($_FILES[$field]['name']);
        move_uploaded_file($_FILES[$field]['tmp_name'], $dest);
        return $dest;
    }

    private function moveUploadedIndexed(array $files, int $i): string
    {
        $dest = __DIR__ . '/../../storage/uploads/' . uniqid('doc_', true) . '_' . basename($files['name'][$i]);
        move_uploaded_file($files['tmp_name'][$i], $dest);
        return $dest;
    }

    private function fail(string $message): void
    {
        View::render('home', ['erro' => $message]);
    }
}
