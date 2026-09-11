<?php

namespace App\Services\Extractors;

class TxtExtractor implements ExtractorInterface
{
    public function extract(string $filePath): array
    {
        $content = file_get_contents($filePath) ?: '';
        $lines = preg_split('/\r\n|\r|\n/', $content) ?: [];

        return NameLineFilter::filter($lines);
    }
}
