<?php

namespace App\Services\Extractors;

class CsvExtractor implements ExtractorInterface
{
    public function extract(string $filePath): array
    {
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return [];
        }

        $header = fgetcsv($handle) ?: [];
        $nameColumn = $this->findNameColumn($header);

        // Não presume mais que a coluna A contém nomes. Um CSV sem um
        // cabeçalho reconhecível é ignorado para evitar contagens falsas.
        if ($nameColumn === null) {
            fclose($handle);
            return [];
        }

        $lines = [];

        while (($row = fgetcsv($handle)) !== false) {
            $value = $row[$nameColumn] ?? ($row[0] ?? '');
            if ($value !== '') {
                $lines[] = $value;
            }
        }

        fclose($handle);

        return NameLineFilter::filter($lines);
    }

    private function findNameColumn(array $header): ?int
    {
        foreach ($header as $index => $value) {
            $value = mb_strtolower(trim((string) $value), 'UTF-8');
            if (str_contains($value, 'nome') || str_contains($value, 'aluno')) {
                return $index;
            }
        }

        return null;
    }
}
