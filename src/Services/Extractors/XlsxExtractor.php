<?php

namespace App\Services\Extractors;

use App\Services\NameNormalizer;
use PhpOffice\PhpSpreadsheet\IOFactory;

class XlsxExtractor implements ExtractorInterface
{
    public function extract(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $lines = [];

        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $rows = $sheet->toArray(null, true, true, false);
            $header = $this->findNameHeader($rows);

            // Uma aba sem coluna de nome não deve contribuir para a contagem.
            if ($header === null) {
                continue;
            }

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex <= $header['row']) {
                    continue;
                }

                $value = $row[$header['column']] ?? '';
                if ($value !== '') {
                    $lines[] = (string) $value;
                }
            }
        }

        return NameLineFilter::filter($lines);
    }

    /**
     * Procura o cabeçalho nas primeiras linhas, pois muitas planilhas têm
     * título, data ou identificação da turma antes da tabela.
     *
     * @param array<int, array<int, mixed>> $rows
     * @return array{row: int, column: int}|null
     */
    private function findNameHeader(array $rows): ?array
    {
        foreach (array_slice($rows, 0, 30, true) as $rowIndex => $row) {
            foreach ($row as $columnIndex => $value) {
                if ($this->isNameHeader((string) $value)) {
                    return ['row' => $rowIndex, 'column' => $columnIndex];
                }
            }
        }

        return null;
    }

    private function isNameHeader(string $value): bool
    {
        $value = NameNormalizer::normalize($value);

        if (in_array($value, [
            'nome',
            'nome completo',
            'nome do aluno',
            'nome da aluna',
            'nome aluno',
            'nome do estudante',
            'nome da estudante',
            'nome estudante',
            'aluno',
            'aluna',
            'estudante',
        ], true)) {
            return true;
        }

        return str_contains($value, 'nome')
            && (str_contains($value, 'aluno') || str_contains($value, 'estudante'));
    }
}
