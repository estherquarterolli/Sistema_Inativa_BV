<?php

namespace App\Services\Extractors;

class CsvExtractor implements ExtractorInterface
{
    public function extract(string $filePath): array
    {
        $lines = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return [];
        }

        $header = fgetcsv($handle) ?: [];
        $nameColumn = $this->findNameColumn($header);

        while (($row = fgetcsv($handle)) !== false) {
            $value = $row[$nameColumn] ?? ($row[0] ?? '');
            if ($value !== '') {
                $lines[] = $value;
            }
        }

        fclose($handle);

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
