<?php

namespace App\Services\Extractors;

class ExtractorFactory
{
    public static function make(string $filePath): ExtractorInterface
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($ext) {
            'txt'         => new TxtExtractor(),
            'csv'         => new CsvExtractor(),
            'xlsx', 'xls' => new XlsxExtractor(),
            'docx'        => new DocxExtractor(),
            'pdf'         => new PdfExtractor(),
            default       => throw new \InvalidArgumentException("Formato nao suportado: .{$ext}"),
        };
    }
}
