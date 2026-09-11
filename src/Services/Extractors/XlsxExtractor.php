<?php

namespace App\Services\Extractors;

use PhpOffice\PhpSpreadsheet\IOFactory;

class XlsxExtractor implements ExtractorInterface
{
    public function extract(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            return [];
        }

        $nameColumn = $this->findNameColumn($rows[0]);
        $lines = [];

        foreach ($rows as $i => $row) {
            if ($i === 0) {
                continue;
            }

            $value = $row[$nameColumn] ?? '';
            if ($value !== '') {
                $lines[] = (string) $value;
            }
        }

        return NameLineFilter::filter($lines);
    }

    private function findNameColumn(array $header): int
    {
        foreach ($header as $index => $value) {
            $value = mb_strtolower(trim((string) $value), 'UTF-8');
            if (str_contains($value, 'nome') || str_contains($value, 'aluno')) {
                return $index;
            }
        }

        return 0;
    }
}
