<?php

namespace App\Services\Extractors;

use Smalot\PdfParser\Parser;

class PdfExtractor implements ExtractorInterface
{
    public function extract(string $filePath): array
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();
        $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];

        return NameLineFilter::filter($lines);
    }
}
