<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadsheetReader
{
    /**
     * Le a planilha principal de alunos (a mesma usada no IMPORTRANGE)
     * e devolve um indice: nome_normalizado => id
     *
     * @return array<string, string>
     */
    public function buildIndex(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);

        $sheet = $spreadsheet->sheetNameExists('Dados')
            ? $spreadsheet->getSheetByName('Dados')
            : $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            return [];
        }

        [$nameCol, $idCol] = $this->detectColumns($rows[0]);

        $index = [];

        foreach ($rows as $i => $row) {
            if ($i === 0) {
                continue; // cabecalho
            }

            $name = trim((string) ($row[$nameCol] ?? ''));
            $id   = trim((string) ($row[$idCol] ?? ''));

            if ($name === '' || $id === '') {
                continue;
            }

            $index[NameNormalizer::normalize($name)] = $id;
        }

        return $index;
    }

    /**
     * Tenta descobrir automaticamente a coluna de nome e a de ID pelo
     * cabecalho. Se nao encontrar, cai no padrao da planilha original:
     * coluna A = nome, coluna C = id.
     *
     * @return array{0:int,1:int}
     */
    private function detectColumns(array $header): array
    {
        $nameCol = 0;
        $idCol = 2;
        $foundName = false;
        $foundId = false;

        foreach ($header as $index => $value) {
            $value = mb_strtolower(trim((string) $value), 'UTF-8');

            if (!$foundName && (str_contains($value, 'nome') || str_contains($value, 'aluno') || str_contains($value, 'estudante'))) {
                $nameCol = $index;
                $foundName = true;
            }

            if (!$foundId && str_contains($value, 'id')) {
                $idCol = $index;
                $foundId = true;
            }
        }

        return [$nameCol, $idCol];
    }
}
